<?php

namespace App\Livewire\Penggajian;

use App\Enums\StatusBayar;
use App\Helpers\MainHelper;
use App\Models\ProyekPenggajian;
use App\Models\ProyekPenggajianPekerja;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class MainDetail extends Component
{
    public $modal = false;

    public $state = [];

    #[Locked]
    public $params = [
        'proyek_pekerja_id' => null,
        'nama_pekerja' => null,
        'jabatan' => null,
        'tarif_harian' => null,
        'tarif_overtime' => null,

        'hari' => [],

        'total_hari' => null,
        'total_overtime' => null,

        'bonus' => null,
        'bonus_text' => null,
        'potongan' => null,
        'potongan_text' => null,
        'kasbon' => null,
        'kasbon_text' => null,

        'total_upah' => null,
    ];

    public $selectedPencatatanUpahId = null;

    #[Locked]
    public ?ProyekPenggajianPekerja $selectedProyekPenggajianPekerja;


    #[Locked]
    public ?ProyekPenggajian $proyekPenggajian = null;

    public function mount($penggajian): void
    {
        $this->ensureCanManage();
        $this->proyekPenggajian = ProyekPenggajian::query()->with([
            'proyek.proyekPekerja',
            'proyekPenggajianPekerja.proyekPekerja',
            'proyekPenggajianPekerja.proyekPenggajianPekerjaHari',
        ])->where('id', '=', $penggajian)->firstOrFail();

        $this->prepareState();
        $this->state = $this->params;
    }

    public function prepareState()
    {
        DB::beginTransaction();
        try {
            $pekerja = $this->proyekPenggajian->proyek?->proyekPekerja;

            foreach ($pekerja as $key => $value) {
                $this->proyekPenggajian->proyekPenggajianPekerja()->updateOrCreate([
                    'proyek_pekerja_id' => $value->id,
                ], [
                    'jabatan' => $value->status_jabatan,
                    'tarif_harian' => $value->tarif_harian,
                    'tarif_overtime' => $value->tarif_overtime,
                    'total_hari' => 0,
                    'total_overtime' => 0,
                    'gaji_normal' => 0,
                    'upah_overtime' => 0,
                    'bonus' => 0,
                    'potongan' => 0,
                    'kasbon' => 0,
                    'total_bersih' => 0,
                    'status_bayar' => StatusBayar::BELUM,
                    'tanggal_bayar' => null,
                    'keterangan' => null,
                ]);
            }

            DB::commit();

            $this->proyekPenggajian->load([
                'proyekPenggajianPekerja.proyekPekerja',
                'proyekPenggajianPekerja.proyekPenggajianPekerjaHari',
            ]);
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
            DB::rollback();
            dd($th);
        }
    }

    private function ensureCanManage(): void
    {
        $user = (new MainHelper)->userData();

        abort_unless($user->isAdmin() || $user->isOperator(), 403);
    }

    public function render(): View
    {
        return view('livewire.penggajian.main-detail');
    }


    // Modal Pencatatan Upah
    public function updatedModal($val)
    {
        if (!$val) $this->closeModalPencatatan();
    }

    public function openModalPencatatan($id)
    {
        $this->reset('state', 'selectedPencatatanUpahId', 'selectedProyekPenggajianPekerja');
        $this->state = $this->params;

        try {
            $this->selectedPencatatanUpahId = $id;

            $this->selectedProyekPenggajianPekerja = ProyekPenggajianPekerja::with([
                'proyekPekerja',
                'proyekPenggajianPekerjaHari',
            ])->where('id', '=', $id)->firstOrFail();

            $mulai = $this->proyekPenggajian->periode_mulai;
            $selesai = $this->proyekPenggajian->periode_selesai;

            if ($mulai->gt($selesai)) {
                (new MainHelper)->doAlert($this, 'error', 'Periode Penggajian Proyek Tidak Valid !');
                return;
            }

            $this->state['proyek_pekerja_id'] = $this->selectedProyekPenggajianPekerja->proyek_pekerja_id;
            $this->state['nama_pekerja'] = $this->selectedProyekPenggajianPekerja->proyekPekerja?->nama_pekerja;
            $this->state['jabatan'] = $this->selectedProyekPenggajianPekerja->proyekPekerja->status_jabatan;
            $this->state['tarif_harian'] = number_format($this->selectedProyekPenggajianPekerja->proyekPekerja?->tarif_harian, 0, ',', '.');
            $this->state['tarif_overtime'] = number_format($this->selectedProyekPenggajianPekerja->proyekPekerja?->tarif_overtime, 0, ',', '.');

            $this->state['bonus'] = $this->selectedProyekPenggajianPekerja->bonus;
            $this->state['bonus_text'] = number_format($this->selectedProyekPenggajianPekerja->bonus, 0, ',', '.');
            $this->state['potongan'] = $this->selectedProyekPenggajianPekerja->potongan;
            $this->state['potongan_text'] = number_format($this->selectedProyekPenggajianPekerja->potongan, 0, ',', '.');
            $this->state['kasbon'] = $this->selectedProyekPenggajianPekerja->kasbon;
            $this->state['kasbon_text'] = number_format($this->selectedProyekPenggajianPekerja->kasbon, 0, ',', '.');

            $hari = [];
            while ($mulai->lte($selesai)) {
                $hari[$mulai->format('Ymd')] = [
                    'proyek_penggajian_pekerja_id' => $this->proyekPenggajian->id,
                    'tanggal' => $mulai->format('Y-m-d'),
                    'tanggal_text' => $mulai->locale('id')->format('d M Y'),
                    'hari' => $mulai->locale('id')->translatedFormat('l'),
                    'hari_normal' => 0,
                    'jam_overtime' => 0,
                ];

                $mulai->addDay();
            }

            $this->state['hari'] = $hari;
            $this->modal = true;
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this);
            $this->closeModalPencatatan();
        }
    }

    public function closeModalPencatatan()
    {
        $this->reset('state', 'selectedPencatatanUpahId', 'selectedProyekPenggajianPekerja');
        $this->modal = false;
    }

    public function updatedState($value, $key)
    {
        // dd($this->state, $this->totalUpah);

        $totalHari = array_sum(array_column($this->state['hari'], 'hari_normal'));
        $totalOvertime = array_sum(array_column($this->state['hari'], 'jam_overtime'));
        // dd($totalHari, $totalOvertime);
    }
    // End Modal Pencatatan Upah

    public function getTotalUpahProperty()
    {
        if (isset($this->selectedProyekPenggajianPekerja) && !$this->selectedProyekPenggajianPekerja) return 0;

        $tarifHarian = $this->selectedProyekPenggajianPekerja?->proyekPekerja?->tarif_harian ?? 0;
        $tarifOvertime = $this->selectedProyekPenggajianPekerja?->proyekPekerja?->tarif_overtime ?? 0;

        $totalHari = array_sum(array_column($this->state['hari'], 'hari_normal'));
        $totalOvertime = array_sum(array_column($this->state['hari'], 'jam_overtime'));
        $bonus = (float) $this->state['bonus'];
        $potongan = (float) $this->state['potongan'];
        $kasbon = (float) $this->state['kasbon'];

        $totalUpah = (($tarifHarian * $totalHari) + ($tarifOvertime * $totalOvertime) + $bonus) - ($potongan + $kasbon);

        $this->state['total_hari'] = $totalHari;
        $this->state['total_overtime'] = $totalOvertime;
        $this->state['total_upah'] = $totalUpah;


        return $totalUpah;
    }

    public function saveData()
    {
        // dd($this->state);
        (new MainHelper)->doAlert($this . 'question', 'Masih dalam proses.');
    }
}
