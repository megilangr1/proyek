<?php

namespace App\Livewire\MasterData\Proyek;

use App\Enums\StatusProyek;
use App\Helpers\MainHelper;
use App\Models\Proyek;
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
        'kode_proyek',
        'nama_proyek',
        'pemilik',
        'tanggal_mulai',
        'tanggal_selesai',
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
        'nama_proyek' => null,
        'pemilik' => null,
        'lokasi' => null,
        'tanggal_mulai' => null,
        'tanggal_selesai' => null,
        'status' => StatusProyek::AKTIF->value,
    ];

    #[Locked]
    public ?Proyek $editData = null;

    /** @var array<int, string> */
    public array $statusOptions = [];

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
        $this->statusOptions = StatusProyek::toSelectArray();
    }

    private function ensureCanManage(): void
    {
        $user = (new MainHelper)->userData();

        abort_unless($user->isAdmin() || $user->isOperator(), 403);
    }

    private function generateKodeProyek(): string
    {
        $max = Proyek::query()
            ->where('kode_proyek', 'LIKE', 'PRJ%')
            ->get()
            ->map(fn(Proyek $proyek) => (int) substr((string) $proyek->kode_proyek, 3))
            ->max();

        $next = ($max ?? 0) + 1;

        if ($next > 999) {
            throw new \RuntimeException('Kode proyek mencapai batas maksimum (PRJ999).');
        }

        return 'PRJ' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    public function render(): View
    {
        $orderBy = in_array($this->order_by, self::ALLOWED_SORT_COLUMNS, true) ? $this->order_by : 'created_at';
        $orderType = strtoupper($this->order_type) === 'ASC' ? 'ASC' : 'DESC';

        $proyeks = Proyek::query();

        if ($this->search !== '') {
            $proyeks->where(function ($query) {
                $query->where('kode_proyek', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('nama_proyek', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('pemilik', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('lokasi', 'LIKE', '%' . $this->search . '%');
            });
        }

        $data = $proyeks->orderBy($orderBy, $orderType)->paginate(10);

        return view('livewire.master-data.proyek.main-index', [
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
            $this->state['kode_proyek'] = $this->editData->kode_proyek;
            $this->state['nama_proyek'] = $this->editData->nama_proyek;
            $this->state['pemilik'] = $this->editData->pemilik;
            $this->state['lokasi'] = $this->editData->lokasi;
            $this->state['tanggal_mulai'] = $this->editData->tanggal_mulai?->format('Y-m-d');
            $this->state['tanggal_selesai'] = $this->editData->tanggal_selesai?->format('Y-m-d');
            $this->state['status'] = $this->editData->status->value;
        } else {
            $this->reset('editData');
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

        try {
            $this->state['kode_proyek'] = $this->generateKodeProyek();
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this, 'error', 'Gagal membuat kode proyek: batas PRJ999 tercapai.');

            return;
        }

        $this->validate([
            'state.kode_proyek' => 'unique:proyeks,kode_proyek',
            'state.nama_proyek' => 'required|string',
            'state.pemilik' => 'required|string',
            'state.lokasi' => 'required|string',
            'state.tanggal_mulai' => 'required|date',
            'state.tanggal_selesai' => 'required|date|after_or_equal:state.tanggal_mulai',
            'state.status' => ['required', Rule::enum(StatusProyek::class)],
        ], [], [
            'state.kode_proyek' => 'Kode Proyek',
            'state.nama_proyek' => 'Nama Proyek',
            'state.pemilik' => 'Pemilik',
            'state.lokasi' => 'Lokasi',
            'state.tanggal_mulai' => 'Tanggal Mulai',
            'state.tanggal_selesai' => 'Tanggal Selesai',
            'state.status' => 'Status Proyek',
        ]);

        try {
            Proyek::create($this->state);

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
            $this->editData = Proyek::where('id', '=', $id)->firstOrFail();
            $this->showForm(true, true);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doUpdate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'state.nama_proyek' => 'required|string',
            'state.pemilik' => 'required|string',
            'state.lokasi' => 'required|string',
            'state.tanggal_mulai' => 'required|date',
            'state.tanggal_selesai' => 'required|date|after_or_equal:state.tanggal_mulai',
            'state.status' => ['required', Rule::enum(StatusProyek::class)],
        ], [], [
            'state.nama_proyek' => 'Nama Proyek',
            'state.pemilik' => 'Pemilik',
            'state.lokasi' => 'Lokasi',
            'state.tanggal_mulai' => 'Tanggal Mulai',
            'state.tanggal_selesai' => 'Tanggal Selesai',
            'state.status' => 'Status Proyek',
        ]);

        try {
            $proyek = Proyek::query()->where('id', '=', $this->editData->id)->firstOrFail();
            $proyek->update($this->state);

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

        try {
            Proyek::query()->where('id', '=', $id)->firstOrFail()->delete();

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
