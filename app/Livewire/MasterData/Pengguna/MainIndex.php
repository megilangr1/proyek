<?php

namespace App\Livewire\MasterData\Pengguna;

use App\Helpers\MainHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class MainIndex extends Component
{
    use WithPagination;

    private const ALLOWED_SORT_COLUMNS = ['name', 'email', 'created_at', 'id'];

    #[Locked]
    public bool $form = false;

    /** @var array<string, mixed> */
    public array $state = [];

    /** @var array<string, mixed> */
    #[Locked]
    public array $params = [
        'name' => null,
        'email' => null,
        'password' => null,
        'password_confirmation' => null,
        'roles' => 'Administrator',
    ];

    #[Locked]
    public ?User $editData = null;

    #[Locked]
    public array $staticData = [
        'roles' => [],
    ];

    #[Locked]
    public array $tomSelectData = [];

    #[Url(except: '')]
    public ?string $search = '';

    #[Url(except: '')]
    public string $order_by = 'created_at';

    #[Url(except: '')]
    public string $order_type = 'DESC';

    public function mount(): void
    {
        $this->ensureCanManage();

        $this->state = $this->params;
        $this->getStaticData();
    }

    private function ensureCanManage(): void
    {
        abort_unless((new MainHelper)->userData()->isAdmin(), 403);
    }

    public function getStaticData(): void
    {
        try {
            $this->staticData['roles'] = Role::where('name', '!=', 'MeGGi')->get();
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function render(): View
    {
        $orderBy = in_array($this->order_by, self::ALLOWED_SORT_COLUMNS, true) ? $this->order_by : 'created_at';
        $orderType = strtoupper($this->order_type) === 'ASC' ? 'ASC' : 'DESC';

        $users = User::query()
            ->with('roles')
            ->where('email', '!=', 'admin@mail.com')
            ->where('id', '!=', (new MainHelper)->userData()->id);

        if ($this->search !== '') {
            $users->where(function ($query) {
                $query->where('name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('email', 'LIKE', '%'.$this->search.'%');
            });
        }

        $data = $users->orderBy($orderBy, $orderType)->paginate(10);

        return view('livewire.master-data.pengguna.main-index', [
            'data' => $data,
        ]);
    }

    public function showForm(bool $open, bool $edit = false): void
    {
        $this->form = $open;
        $this->reset('state');
        $this->resetErrorBag();
        $this->state = $this->params;

        if ($edit && $this->editData !== null) {
            $this->state['name'] = $this->editData->name;
            $this->state['email'] = $this->editData->email;
            $this->state['roles'] = $this->editData->getRoleNames()->first() ?? 'Administrator';
        } else {
            $this->reset('editData');
        }

        $this->dispatch('setTomSelect', $this->tomSelectData);
    }

    public function actionForm(): void
    {
        if (isset($this->editData)) {
            $this->doUpdate();
        } else {
            $this->doCreate();
        }
    }

    public function doCreate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'state.name' => 'required|string',
            'state.email' => 'required|string|email|unique:users,email',
            'state.password' => 'required|string|min:8|confirmed',
            'state.roles' => 'required|string|exists:roles,name',
        ], [], [
            'state.name' => 'Nama Lengkap',
            'state.email' => 'Email',
            'state.password' => 'Password',
            'state.roles' => 'Hak Akses Pengguna',
        ]);

        try {
            DB::transaction(function () {
                $user = User::create([
                    'name' => $this->state['name'],
                    'email' => $this->state['email'],
                    'password' => Hash::make($this->state['password']),
                ]);
                $user->syncRoles($this->state['roles']);
            });

            (new MainHelper)->doAlert($this, 'success', 'Data Berhasil di-Buat !');
            $this->showForm(false);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doEdit(int $id): void
    {
        $this->ensureCanManage();

        try {
            $this->editData = User::with('roles')->where('id', '=', $id)->firstOrFail();
            $this->showForm(true, true);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doUpdate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'state.name' => 'required|string',
            'state.email' => 'required|string|email|unique:users,email,'.$this->editData->id,
            'state.password' => 'nullable|string|min:8|confirmed',
            'state.roles' => 'required|string|exists:roles,name',
        ], [], [
            'state.name' => 'Nama Lengkap',
            'state.email' => 'Email',
            'state.password' => 'Password',
            'state.roles' => 'Hak Akses Pengguna',
        ]);

        try {
            DB::transaction(function () {
                $user = User::query()->where('id', '=', $this->editData->id)->firstOrFail();
                $password = $this->state['password'] !== null ? Hash::make($this->state['password']) : $user->password;

                $user->update([
                    'name' => $this->state['name'],
                    'email' => $this->state['email'],
                    'password' => $password,
                ]);
                $user->syncRoles($this->state['roles']);
            });

            (new MainHelper)->doAlert($this, 'info', 'Perubahan Data Berhasil di-Simpan !');
            $this->showForm(false);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    #[On('doDelete')]
    public function doDelete(int $id): void
    {
        $this->ensureCanManage();

        if ($id === (new MainHelper)->userData()->id) {
            (new MainHelper)->doAlert($this, 'error', 'Tidak dapat menghapus akun Anda sendiri.');

            return;
        }

        try {
            DB::transaction(function () use ($id) {
                $user = User::where('id', '=', $id)->firstOrFail();
                $user->delete();
            });

            (new MainHelper)->doAlert($this, 'warning', 'Data Berhasil di-Hapus !');

            if ($this->form && optional($this->editData)->id === $id) {
                $this->showForm(false, false);
            }
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    #[On('setOrderBy')]
    public function setOrderBy(string $field): void
    {
        if (! in_array($field, self::ALLOWED_SORT_COLUMNS, true)) {
            return;
        }

        if ($this->order_by === $field) {
            $this->order_type = $this->order_type === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->order_by = $field;
            $this->order_type = 'DESC';
        }
    }

    public function updatedSearch($value): void
    {
        $this->resetPage();
    }
}
