<?php

namespace App\Livewire\MasterData\Proyek;

use App\Enum\StatusProyek;
use App\Models\Proyek;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ProyekPickerModal extends Component
{
    public bool $open = false;

    public string $search = '';

    /** @var string|null context identifier ('form' or 'filter') */
    public ?string $context = null;

    /** @var Collection<int, Proyek> */
    public $results;

    public function mount(): void
    {
        $this->results = collect();
    }

    #[On('openProyekPicker')]
    public function openModal(string $context = 'form'): void
    {
        $this->context = $context;
        $this->search = '';
        $this->loadResults();
        $this->open = true;
    }

    public function updatedSearch(): void
    {
        $this->loadResults();
    }

    private function loadResults(): void
    {
        $query = Proyek::query()
            ->where('status', StatusProyek::AKTIF)
            ->whereNull('deleted_at');

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('kode_proyek', 'LIKE', $term)
                    ->orWhere('nama_proyek', 'LIKE', $term)
                    ->orWhere('pemilik', 'LIKE', $term)
                    ->orWhere('lokasi', 'LIKE', $term);
            });
        }

        $this->results = $query->orderBy('kode_proyek')->get();
    }

    public function selectProyek(int $id): void
    {
        $proyek = Proyek::findOrFail($id);

        $this->dispatch(
            'proyekSelected',
            id: $proyek->id,
            kode: $proyek->kode_proyek,
            nama: $proyek->nama_proyek,
            context: $this->context,
        );

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->search = '';
        $this->context = null;
        $this->results = collect();
    }

    public function render(): View
    {
        return view('livewire.master-data.proyek.proyek-picker-modal');
    }
}
