<?php

namespace App\Livewire\MasterData\ProyekPekerja;

use App\Enums\StatusPekerja;
use App\Helpers\MainHelper;
use App\Models\Proyek;
use App\Models\ProyekPekerja;
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
        'nama_pekerja',
        'status_jabatan',
        'tarif_harian',
        'created_at',
        'id',
    ];

    #[Locked]
    public ?Proyek $proyekData = null;

    #[Locked]
    public bool $form = false;

    /** @var array<string, mixed> */
    public array $pekerjaState = [];

    /** @var array<string, mixed> */
    #[Locked]
    public array $params = [
        'nama_pekerja' => null,
        'nomor_hp' => null,
        'status_jabatan' => null,
        'tarif_harian' => null,
        'tarif_harian_text' => null,
        'tarif_overtime' => null,
        'tarif_overtime_text' => null,
        'catatan' => null,
        'status' => StatusPekerja::AKTIF->value,
    ];

    #[Locked]
    public ?ProyekPekerja $editData = null;

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

        $this->pekerjaState = $this->params;
        $this->statusOptions = StatusPekerja::toSelectArray();
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

        $pekerjas = $this->proyekData->proyekPekerja();

        if ($this->search !== '') {
            $pekerjas->where(function ($query) {
                $query->where('nama_pekerja', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('nomor_hp', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('status_jabatan', 'LIKE', '%'.$this->search.'%');
            });
        }

        $data = $pekerjas->orderBy($orderBy, $orderType)->paginate(10);

        // Rich stats untuk header kaya
        $all = $this->proyekData->proyekPekerja()->get();
        $total = $all->count();
        $aktif = $all->where('status', StatusPekerja::AKTIF)->count();
        $nonaktif = $total - $aktif;
        $sumHarian = (float) $all->sum('tarif_harian');
        $sumOvertime = (float) $all->sum(fn ($p): float => (float) ($p->tarif_overtime ?? 0));
        $avgHarian = $total > 0 ? $sumHarian / $total : 0;
        $jabatans = $all->groupBy('status_jabatan')->map->count()->sortDesc()->take(3);

        // Durasi proyek (hari)
        $durasiHari = null;
        if ($this->proyekData->tanggal_mulai && $this->proyekData->tanggal_selesai) {
            $durasiHari = $this->proyekData->tanggal_mulai->diffInDays($this->proyekData->tanggal_selesai) + 1;
        }

        $stats = [
            'total' => $total,
            'aktif' => $aktif,
            'nonaktif' => $nonaktif,
            'sumHarian' => $sumHarian,
            'sumOvertime' => $sumOvertime,
            'avgHarian' => $avgHarian,
            'jabatans' => $jabatans,
            'durasiHari' => $durasiHari,
        ];

        return view('livewire.master-data.proyek-pekerja.main-index', [
            'data' => $data,
            'proyek' => $this->proyekData,
            'stats' => $stats,
        ]);
    }

    public function showForm(bool $open, bool $edit = false): void
    {
        $this->form = $open;
        $this->reset('pekerjaState');
        $this->resetErrorBag();
        $this->pekerjaState = $this->params;

        if ($edit && $this->editData !== null) {
            $this->pekerjaState['nama_pekerja'] = $this->editData->nama_pekerja;
            $this->pekerjaState['nomor_hp'] = $this->editData->nomor_hp;
            $this->pekerjaState['status_jabatan'] = $this->editData->status_jabatan;
            $this->pekerjaState['tarif_harian'] = $this->editData->tarif_harian;
            $this->pekerjaState['tarif_harian_text'] = number_format($this->editData->tarif_harian, 0, ',', '.');
            $this->pekerjaState['tarif_overtime'] = $this->editData->tarif_overtime;
            $this->pekerjaState['tarif_overtime_text'] = $this->editData->tarif_overtime !== null
                ? number_format($this->editData->tarif_overtime, 0, ',', '.')
                : '';
            $this->pekerjaState['catatan'] = $this->editData->catatan;
            $this->pekerjaState['status'] = $this->editData->status->value;
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

        $this->validate([
            'pekerjaState.nama_pekerja' => 'required|string',
            'pekerjaState.nomor_hp' => 'required|string',
            'pekerjaState.status_jabatan' => 'required|string',
            'pekerjaState.tarif_harian' => 'required|numeric|min:0',
            'pekerjaState.tarif_overtime' => 'nullable|numeric|min:0',
            'pekerjaState.catatan' => 'nullable|string',
            'pekerjaState.status' => ['required', Rule::enum(StatusPekerja::class)],
        ], [], [
            'pekerjaState.nama_pekerja' => 'Nama Pekerja',
            'pekerjaState.nomor_hp' => 'Nomor HP',
            'pekerjaState.status_jabatan' => 'Status Jabatan',
            'pekerjaState.tarif_harian' => 'Tarif Harian',
            'pekerjaState.tarif_overtime' => 'Tarif Overtime',
            'pekerjaState.catatan' => 'Catatan',
            'pekerjaState.status' => 'Status Pekerja',
        ]);

        try {
            $this->proyekData->proyekPekerja()->create($this->pekerjaState);

            (new MainHelper)->doAlert($this, 'success', 'Data Pekerja Berhasil di-Buat !');
            $this->showForm(false);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doEdit(int $id): void
    {
        $this->ensureCanManage();

        try {
            $this->editData = $this->proyekData->proyekPekerja()->where('id', '=', $id)->firstOrFail();
            $this->showForm(true, true);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doUpdate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'pekerjaState.nama_pekerja' => 'required|string',
            'pekerjaState.nomor_hp' => 'required|string',
            'pekerjaState.status_jabatan' => 'required|string',
            'pekerjaState.tarif_harian' => 'required|numeric|min:0',
            'pekerjaState.tarif_overtime' => 'nullable|numeric|min:0',
            'pekerjaState.catatan' => 'nullable|string',
            'pekerjaState.status' => ['required', Rule::enum(StatusPekerja::class)],
        ], [], [
            'pekerjaState.nama_pekerja' => 'Nama Pekerja',
            'pekerjaState.nomor_hp' => 'Nomor HP',
            'pekerjaState.status_jabatan' => 'Status Jabatan',
            'pekerjaState.tarif_harian' => 'Tarif Harian',
            'pekerjaState.tarif_overtime' => 'Tarif Overtime',
            'pekerjaState.catatan' => 'Catatan',
            'pekerjaState.status' => 'Status Pekerja',
        ]);

        try {
            $pekerja = $this->proyekData->proyekPekerja()->where('id', '=', $this->editData->id)->firstOrFail();
            $pekerja->update($this->pekerjaState);

            (new MainHelper)->doAlert($this, 'info', 'Perubahan Data Pekerja Berhasil di-Simpan !');
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
            $this->proyekData->proyekPekerja()->where('id', '=', $id)->firstOrFail()->delete();

            (new MainHelper)->doAlert($this, 'warning', 'Data Pekerja Berhasil di-Hapus !');

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
