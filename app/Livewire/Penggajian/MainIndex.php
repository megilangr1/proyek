<?php

namespace App\Livewire\Penggajian;

use App\Enums\StatusPekerja;
use App\Enums\StatusPenggajian;
use App\Helpers\MainHelper;
use App\Models\Proyek;
use App\Models\ProyekPenggajian;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

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
    public ?Proyek $proyekData = null;

    #[Locked]
    public bool $form = false;

    /** @var array<string, mixed> */
    public array $state = [];

    /** @var array<string, mixed> */
    #[Locked]
    public array $params = [
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

    #[Url(except: '')]
    public ?string $search = '';

    #[Url(except: '')]
    public string $order_by = 'created_at';

    #[Url(except: '')]
    public string $order_type = 'DESC';

    public function mount($proyek): void
    {
        $this->proyekData = Proyek::query()->where('id', '=', $proyek)->firstOrFail();

        $this->ensureCanManage();

        $this->state = $this->params;
        $this->statusOptions = StatusPenggajian::toSelectArray();
    }

    private function ensureCanManage(): void
    {
        $user = (new MainHelper)->userData();

        abort_unless($user->isAdmin() || $user->isOperator(), 403);
    }

    private function ensureHasPekerjaAktif(): bool
    {
        return $this->proyekData->proyekPekerja()
            ->where('status', StatusPekerja::AKTIF)
            ->exists();
    }

    public function render(): View
    {
        $orderBy = in_array($this->order_by, self::ALLOWED_SORT_COLUMNS, true) ? $this->order_by : 'created_at';
        $orderType = strtoupper($this->order_type) === 'ASC' ? 'ASC' : 'DESC';

        $penggajians = $this->proyekData->proyekPenggajian();

        if ($this->search !== '') {
            $penggajians->where(function ($query) {
                $query->where('nama_periode', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('keterangan', 'LIKE', '%'.$this->search.'%');
            });
        }

        $data = $penggajians->orderBy($orderBy, $orderType)->paginate(10);

        return view('livewire.penggajian.main-index', [
            'data' => $data,
            'proyek' => $this->proyekData,
        ]);
    }

    public function showForm(bool $open, bool $edit = false): void
    {
        $this->form = $open;
        $this->reset('state');
        $this->resetErrorBag();
        $this->state = $this->params;

        if ($edit && $this->editData !== null) {
            $this->state['nama_periode'] = $this->editData->nama_periode;
            $this->state['periode_mulai'] = $this->editData->periode_mulai?->format('Y-m-d');
            $this->state['periode_selesai'] = $this->editData->periode_selesai?->format('Y-m-d');
            $this->state['jam_kerja'] = $this->editData->jam_kerja;
            $this->state['keterangan'] = $this->editData->keterangan;
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

        if (! $this->ensureHasPekerjaAktif()) {
            (new MainHelper)->doAlert($this, 'warning', 'Tambahkan pekerja aktif terlebih dahulu sebelum membuat penggajian.');

            return;
        }

        $this->validate([
            'state.nama_periode' => 'required|string',
            'state.periode_mulai' => 'required|date',
            'state.periode_selesai' => 'required|date|after_or_equal:state.periode_mulai',
            'state.jam_kerja' => 'required|integer|min:0|max:255',
            'state.keterangan' => 'nullable|string',
            'state.status' => ['required', Rule::enum(StatusPenggajian::class)],
        ], [], [
            'state.nama_periode' => 'Nama Periode',
            'state.periode_mulai' => 'Periode Mulai',
            'state.periode_selesai' => 'Periode Selesai',
            'state.jam_kerja' => 'Jam Kerja',
            'state.keterangan' => 'Keterangan',
            'state.status' => 'Status Penggajian',
        ]);

        try {
            $this->proyekData->proyekPenggajian()->create($this->state);

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
            $this->editData = $this->proyekData->proyekPenggajian()->where('id', '=', $id)->firstOrFail();
            $this->showForm(true, true);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doUpdate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'state.nama_periode' => 'required|string',
            'state.periode_mulai' => 'required|date',
            'state.periode_selesai' => 'required|date|after_or_equal:state.periode_mulai',
            'state.jam_kerja' => 'required|integer|min:0|max:255',
            'state.keterangan' => 'nullable|string',
            'state.status' => ['required', Rule::enum(StatusPenggajian::class)],
        ], [], [
            'state.nama_periode' => 'Nama Periode',
            'state.periode_mulai' => 'Periode Mulai',
            'state.periode_selesai' => 'Periode Selesai',
            'state.jam_kerja' => 'Jam Kerja',
            'state.keterangan' => 'Keterangan',
            'state.status' => 'Status Penggajian',
        ]);

        try {
            $penggajian = $this->proyekData->proyekPenggajian()->where('id', '=', $this->editData->id)->firstOrFail();
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
            $this->proyekData->proyekPenggajian()->where('id', '=', $id)->firstOrFail()->delete();

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
}
