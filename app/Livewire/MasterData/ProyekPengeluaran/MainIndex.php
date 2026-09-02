<?php

namespace App\Livewire\MasterData\ProyekPengeluaran;

use App\Enums\KategoriPengeluaran;
use App\Enums\StatusPengeluaran;
use App\Helpers\MainHelper;
use App\Models\Proyek;
use App\Models\ProyekPengeluaran;
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
        'tanggal',
        'kategori',
        'nama_item',
        'nominal',
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
        'tanggal' => null,
        'kategori' => null,
        'nama_item' => null,
        'nominal' => null,
        'nominal_text' => null,
        'keterangan' => null,
        'status' => null,
    ];

    #[Locked]
    public ?ProyekPengeluaran $editData = null;

    /** @var array<int, string> */
    public array $kategoriOptions = [];

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

        $this->params['status'] = StatusPengeluaran::AKTIF->value;
        $this->params['kategori'] = KategoriPengeluaran::BAHAN_BAKU->value;
        $this->state = $this->params;
        $this->kategoriOptions = KategoriPengeluaran::toSelectArray();
        $this->statusOptions = StatusPengeluaran::toSelectArray();
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

        $query = $this->proyekData->proyekPengeluaran();

        if ($this->search !== '') {
            $query->where(function ($q): void {
                $q->where('nama_item', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('keterangan', 'LIKE', '%'.$this->search.'%');
            });
        }

        $data = $query->orderBy($orderBy, $orderType)->paginate(10);

        $all = $this->proyekData->proyekPengeluaran()->get();
        $total = $all->count();
        $sumNominal = (float) $all->sum('nominal');
        $kategoriTop = $all->groupBy(fn ($p): string => $p->kategori->label())->map->count()->sortDesc()->take(3);

        $stats = [
            'total' => $total,
            'sumNominal' => $sumNominal,
            'kategoriTop' => $kategoriTop,
        ];

        return view('livewire.master-data.proyek-pengeluaran.main-index', [
            'data' => $data,
            'proyek' => $this->proyekData,
            'stats' => $stats,
        ]);
    }

    public function showForm(bool $open, bool $edit = false): void
    {
        $this->form = $open;
        $this->reset('state');
        $this->resetErrorBag();
        $this->state = $this->params;

        if ($edit && $this->editData !== null) {
            $this->state['tanggal'] = $this->editData->tanggal?->format('Y-m-d');
            $this->state['kategori'] = $this->editData->kategori->value;
            $this->state['nama_item'] = $this->editData->nama_item;
            $this->state['nominal'] = $this->editData->nominal;
            $this->state['nominal_text'] = number_format((float) $this->editData->nominal, 0, ',', '.');
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

        $this->validate([
            'state.tanggal' => ['required', 'date'],
            'state.kategori' => ['required', Rule::enum(KategoriPengeluaran::class)],
            'state.nama_item' => ['required', 'string', 'max:255'],
            'state.nominal' => ['required', 'numeric', 'min:0'],
            'state.keterangan' => ['nullable', 'string'],
            'state.status' => ['required', Rule::enum(StatusPengeluaran::class)],
        ], [], [
            'state.tanggal' => 'Tanggal',
            'state.kategori' => 'Kategori',
            'state.nama_item' => 'Nama Item',
            'state.nominal' => 'Nominal',
            'state.keterangan' => 'Keterangan',
            'state.status' => 'Status',
        ]);

        try {
            $payload = $this->state;
            unset($payload['nominal_text']);

            $this->proyekData->proyekPengeluaran()->create($payload);

            (new MainHelper)->doAlert($this, 'success', 'Pengeluaran berhasil dibuat!');
            $this->showForm(false);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doEdit(int $id): void
    {
        $this->ensureCanManage();

        try {
            $this->editData = $this->proyekData->proyekPengeluaran()->where('id', '=', $id)->firstOrFail();
            $this->showForm(true, true);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
        }
    }

    public function doUpdate(): void
    {
        $this->ensureCanManage();

        $this->validate([
            'state.tanggal' => ['required', 'date'],
            'state.kategori' => ['required', Rule::enum(KategoriPengeluaran::class)],
            'state.nama_item' => ['required', 'string', 'max:255'],
            'state.nominal' => ['required', 'numeric', 'min:0'],
            'state.keterangan' => ['nullable', 'string'],
            'state.status' => ['required', Rule::enum(StatusPengeluaran::class)],
        ], [], [
            'state.tanggal' => 'Tanggal',
            'state.kategori' => 'Kategori',
            'state.nama_item' => 'Nama Item',
            'state.nominal' => 'Nominal',
            'state.keterangan' => 'Keterangan',
            'state.status' => 'Status',
        ]);

        try {
            $row = $this->proyekData->proyekPengeluaran()->where('id', '=', $this->editData->id)->firstOrFail();
            $payload = $this->state;
            unset($payload['nominal_text']);
            $row->update($payload);

            (new MainHelper)->doAlert($this, 'info', 'Pengeluaran berhasil diperbarui!');
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
            $this->proyekData->proyekPengeluaran()->where('id', '=', $id)->firstOrFail()->delete();

            (new MainHelper)->doAlert($this, 'warning', 'Pengeluaran berhasil dihapus!');

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
