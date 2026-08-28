<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = '';

    /** @var array<int, string> */
    public array $roles = [];

    public function mount(): void
    {
        abort_unless(Auth::user()->hasAnyRole(['administrator', 'meggi']), 403);

        $this->roles = Role::orderBy('name')->pluck('name')->toArray();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }

    /**
     * Bersihkan field Livewire (state UI dibiarkan di Alpine).
     */
    public function create(): void
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'password_confirmation', 'role']);
    }

    public function edit($id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = $user->getRoleNames()->first() ?? '';
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => [$this->editingId ? 'nullable' : 'required', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in($this->roles)],
        ]);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->name = $data['name'];
            $user->email = $data['email'];

            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }

            $user->save();
            $user->syncRoles([$data['role']]);
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $user->syncRoles([$data['role']]);
        }

        $this->reset(['editingId', 'name', 'email', 'password', 'password_confirmation', 'role']);

        // Beri tahu Alpine untuk menutup modal (boundary: UI = Alpine).
        $this->dispatch('saved');
    }

    public function delete($id): void
    {
        if ((int) $id === Auth::id()) {
            session()->flash('error', 'Tidak dapat menghapus akun Anda sendiri.');

            return;
        }

        User::findOrFail($id)->delete();
    }

    public function render()
    {
        return $this->view();
    }
};
?>

{{-- Boundary: state UI (buka/tutup modal) di Alpine; data & aksi DB di Livewire. --}}
<div x-data="{ open: false }" @saved.window="open = false" class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold">Akun Pengguna</h2>
            <p class="text-sm text-base-content/60">Kelola pengguna dan peran akses.</p>
        </div>
        <button type="button" class="btn btn-primary" wire:click="create" @click="open = true">
            <x-lucide-plus class="h-4 w-4" />
            Tambah Pengguna
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-soft text-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error alert-soft text-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card bg-base-100 shadow-sm border border-base-300">
        <div class="card-body">
            <div class="flex items-center gap-2">
                <x-lucide-search class="h-5 w-5 text-base-content/40" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                    class="input input-bordered input-sm w-full max-w-xs" />
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Dibuat</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td class="font-medium">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->getRoleNames() as $roleName)
                                        <span class="badge badge-soft badge-primary">{{ $roleName }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user->created_at?->format('d M Y') }}</td>
                                <td class="text-right">
                                    <div class="join">
                                        <button type="button" class="btn btn-sm btn-ghost join-item"
                                            wire:click="edit({{ $user->id }})" @click="open = true">
                                            <x-lucide-pencil class="h-4 w-4" />
                                        </button>
                                        <button type="button" class="btn btn-sm btn-ghost text-error join-item"
                                            wire:click="delete({{ $user->id }})"
                                            wire:confirm="Yakin hapus pengguna ini?">
                                            <x-lucide-trash-2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-base-content/60 py-6">
                                    Belum ada pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $this->users->links() }}
        </div>
    </div>

    <dialog class="modal" x-bind:class="open ? 'modal-open' : ''">
        <div class="modal-box">
            <h3 class="text-lg font-bold">{{ $editingId ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h3>

            <form wire:submit="save" class="mt-4 flex flex-col gap-4">
                <label class="form-control w-full">
                    <span class="label-text mb-1">Nama</span>
                    <input type="text" wire:model="name" placeholder="Nama lengkap"
                        class="input input-bordered w-full @error('name') input-error @enderror" />
                    @error('name')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <label class="form-control w-full">
                    <span class="label-text mb-1">Email</span>
                    <input type="email" wire:model="email" placeholder="you@example.com"
                        class="input input-bordered w-full @error('email') input-error @enderror" />
                    @error('email')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <label class="form-control w-full">
                    <span class="label-text mb-1">
                        Password {{ $editingId ? '(kosongkan jika tidak diubah)' : '' }}
                    </span>
                    <input type="password" wire:model="password" placeholder="••••••••"
                        class="input input-bordered w-full @error('password') input-error @enderror"
                        autocomplete="new-password" />
                    @error('password')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <label class="form-control w-full">
                    <span class="label-text mb-1">Konfirmasi Password</span>
                    <input type="password" wire:model="password_confirmation" placeholder="••••••••"
                        class="input input-bordered w-full" autocomplete="new-password" />
                </label>

                <label class="form-control w-full">
                    <span class="label-text mb-1">Peran</span>
                    <select wire:model="role"
                        class="select select-bordered w-full @error('role') select-error @enderror">
                        <option value="" disabled>Pilih peran</option>
                        @foreach ($roles as $roleOption)
                            <option value="{{ $roleOption }}" @selected($role === $roleOption)>{{ $roleOption }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" wire:click="create" @click="open = false">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading class="loading loading-spinner loading-sm"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </dialog>
</div>
