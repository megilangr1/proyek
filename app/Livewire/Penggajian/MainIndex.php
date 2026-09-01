<?php

namespace App\Livewire\Penggajian;

use App\Enums\StatusPenggajian;
use App\Helpers\MainHelper;
use App\Models\ProyekPenggajian;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MainIndex extends Component
{
    use WithPagination;

    private const ALLOWED_SORT_COLUMNS = [
        'nama_periode',
        'periode_mulai',
        'periode_selesai',
        'jam_kerja',
        'created_at',
        'id',
    ];

    #[Locked]
    public bool $form = false;

    /** @var array<string, mixed> */
    public array $state = [];

    /** @var array<string, mixed> */
    #[Locked]
    public array $params = [
        'proyek_id' => null,
        'nama_periode' => null,
        'periode_mulai' => null,
        'periode_selesai' => null,
        'jam_kerja' => null,
        'keterangan' => null,
        'status' => StatusPenggajian::AKTIF->value,
    ];

    #[Locked]
    public ?ProyekPenggajian $editData = null;

    /** @var array<int, string> */
    public array $statusOptions = [];

    #[Locked]
    public ?int $selectedProyekId = null;

    #[Locked]
    public ?string $selectedProyekName = null;

    #[Url(except: '')]
    public ?string $search = '';

    #[Url(except: '')]
    public ?int $filterProyekId = null;

    #[Locked]
    public ?string $filterProyekName = null;

    #[Url(except: '')]
    public string $order_by = 'created_at';

    #[Url(except: '')]
    public string $order_type = 'DESC';

    public function mount(): void
    {
        $this->ensureCanManage();

        $this->state = $this->params;
        $this->statusOptions = StatusPenggajian::toSelectArray();
    }

    private function ensureCanManage(): void
    {
        $user = (new MainHelper)->userData();

        abort_unless($user->isAdmin() || $user->isOperator(), 403);
    }

    public function render(): View
    {
        $orderBy = in_array($this->order_by, self::ALLOWED_SORT_COLUMNS, true) ? $this->order_by : 'created_at';
        $orderType = strtoupper($this->order_type) === 'ASC' ? 'ASC' : 'DESC';

        $penggajians = ProyekPenggajian::query();

        if ($this->filterProyekId !== null) {
            $penggajians->where('proyek_id', '=', $this->filterProyekId);
        }

        if ($this->search !== '') {
            $penggajians->where(function ($query) {
                $query->where('nama_periode', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'LIKE', '%' . $this->search . '%');
            });
        }

        $data = $penggajians->orderBy($orderBy, $orderType)->paginate(10);

        return view('livewire.penggajian.main-index', [
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
            $this->state['proyek_id'] = $this->editData->proyek_id;
            $this->state['nama_periode'] = $this->editData->nama_periode;
            $this->state['periode_mulai'] = $this->editData->periode_mulai?->format('Y-m-d');
            $this->state['periode_selesai'] = $this->editData->periode_selesai?->format('Y-m-d');
            $this->state['jam_kerja'] = $this->editData->jam_kerja;
            $this->state['keterangan'] = $this->editData->keterangan;
            $this->state['status'] = $this->editData->status->value;

            $this->selectedProyekId = $this->editData->proyek_id;
            $this->selectedProyekName = $this->editData->proyek?->nama_proyek;
        } else {
            $this->reset('editData');
            $this->selectedProyekId = null;
            $this->selectedProyekName = null;
        }
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
            'state.proyek_id' => 'required|exists:proyeks,id',
            'state.nama_periode' => 'required|string',
            'state.periode_mulai' => 'required|date',
            'state.periode_selesai' => 'required|date|after_or_equal:state.periode_mulai',
            'state.jam_kerja' => 'required|integer|min:0|max:255',
            'state.keterangan' => 'nullable|string',
            'state.status' => ['required', Rule::enum(StatusPenggajian::class)],
        ], [], [
            'state.proyek_id' => 'Proyek',
            'state.nama_periode' => 'Nama Periode',
            'state.periode_mulai' => 'Periode Mulai',
            'state.periode_selesai' => 'Periode Selesai',
            'state.jam_kerja' => 'Jam Kerja',
            'state.keterangan' => 'Keterangan',
            'state.status' => 'Status Penggajian',
        ]);

        try {
            ProyekPenggajian::create($this->state);

            (new MainHelper)->doAlert($this, 'success', 'Data Penggajian Berhasil di-Buat !');
            $this->showForm(false);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doEdit(int $id): void
    {
        $this->ensureCanManage();

        try {
            $this->editData = ProyekPenggajian::query()->where('id', '=', $id)->firstOrFail();
            $this->showForm(true, true);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doUpdate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'state.proyek_id' => 'required|exists:proyeks,id',
            'state.nama_periode' => 'required|string',
            'state.periode_mulai' => 'required|date',
            'state.periode_selesai' => 'required|date|after_or_equal:state.periode_mulai',
            'state.jam_kerja' => 'required|integer|min:0|max:255',
            'state.keterangan' => 'nullable|string',
            'state.status' => ['required', Rule::enum(StatusPenggajian::class)],
        ], [], [
            'state.proyek_id' => 'Proyek',
            'state.nama_periode' => 'Nama Periode',
            'state.periode_mulai' => 'Periode Mulai',
            'state.periode_selesai' => 'Periode Selesai',
            'state.jam_kerja' => 'Jam Kerja',
            'state.keterangan' => 'Keterangan',
            'state.status' => 'Status Penggajian',
        ]);

        try {
            $penggajian = ProyekPenggajian::query()->where('id', '=', $this->editData->id)->firstOrFail();
            $penggajian->update($this->state);

            (new MainHelper)->doAlert($this, 'info', 'Perubahan Data Penggajian Berhasil di-Simpan !');
            $this->showForm(false);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    #[On('doDelete')]
    public function doDelete(int $id): void
    {
        $this->ensureCanManage();

        try {
            ProyekPenggajian::query()->where('id', '=', $id)->firstOrFail()->delete();

            (new MainHelper)->doAlert($this, 'warning', 'Data Penggajian Berhasil di-Hapus !');

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

    public function openProyekPicker(string $context = 'form'): void
    {
        $this->dispatch('openProyekPicker', context: $context);
    }

    public function clearProyekSelection(): void
    {
        $this->state['proyek_id'] = null;
        $this->selectedProyekId = null;
        $this->selectedProyekName = null;
        $this->filterProyekId = null;
        $this->filterProyekName = null;
    }

    #[On('proyekSelected')]
    public function handleProyekSelected(int $id, string $nama, string $context = 'form'): void
    {
        if ($context === 'filter') {
            $this->filterProyekId = $id;
            $this->filterProyekName = $nama;

            return;
        }

        $this->state['proyek_id'] = $id;
        $this->selectedProyekId = $id;
        $this->selectedProyekName = $nama;
    }
}
