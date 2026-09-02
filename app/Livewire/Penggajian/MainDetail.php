<?php

namespace App\Livewire\Penggajian;

use App\Enums\StatusBayar;
use App\Helpers\MainHelper;
use App\Models\ProyekPenggajian;
use App\Models\ProyekPenggajianPekerja;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
        'keterangan' => null,

        'total_upah' => null,
    ];

    public $selectedPencatatanUpahId = null;

    #[Locked]
    public ?ProyekPenggajianPekerja $selectedProyekPenggajianPekerja;

    public bool $summaryModal = false;

    #[Locked]
    public ?ProyekPenggajianPekerja $selectedSummary = null;

    public bool $bayarModal = false;

    public array $bayarState = [];

    #[Locked]
    public array $bayarParams = [
        'status_bayar' => null,
        'tanggal_bayar' => null,
        'keterangan' => null,
    ];

    #[Locked]
    public ?ProyekPenggajianPekerja $selectedBayar = null;

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
        $this->bayarState = $this->bayarParams;
    }

    public function prepareState(): void
    {
        DB::beginTransaction();
        try {
            $pekerja = $this->proyekPenggajian->proyek?->proyekPekerja;

            if ($pekerja) {
                foreach ($pekerja as $value) {
                    $existing = $this->proyekPenggajian->proyekPenggajianPekerja()
                        ->where('proyek_pekerja_id', $value->id)
                        ->first();

                    if ($existing) {
                        $existing->update([
                            'jabatan' => $value->status_jabatan,
                            'tarif_harian' => $value->tarif_harian,
                            'tarif_overtime' => $value->tarif_overtime,
                        ]);
                    } else {
                        $this->proyekPenggajian->proyekPenggajianPekerja()->create([
                            'proyek_pekerja_id' => $value->id,
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
                }
            }

            DB::commit();

            $this->proyekPenggajian->load([
                'proyekPenggajianPekerja.proyekPekerja',
                'proyekPenggajianPekerja.proyekPenggajianPekerjaHari',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            (new MainHelper)->doAlert($this, 'error', 'Gagal sinkronisasi pekerja.');
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
        if (! $val) {
            $this->closeModalPencatatan();
        }
    }

    public function openModalPencatatan($id): void
    {
        $this->ensureCanManage();

        // Lock saat sudah dibayar
        $candidate = ProyekPenggajianPekerja::where('id', $id)->first();
        if ($candidate && $candidate->status_bayar === StatusBayar::SUDAH) {
            (new MainHelper)->doAlert($this, 'warning', 'Pekerja sudah dibayar, pencatatan dikunci.');

            return;
        }

        // Close summary if open, avoid double modal
        if ($this->summaryModal) {
            $this->closeSummary();
        }

        $this->reset('state', 'selectedPencatatanUpahId', 'selectedProyekPenggajianPekerja');
        $this->state = $this->params;

        try {
            $this->selectedPencatatanUpahId = $id;

            $this->selectedProyekPenggajianPekerja = ProyekPenggajianPekerja::with([
                'proyekPekerja',
                'proyekPenggajianPekerjaHari',
            ])->where('id', '=', $id)->firstOrFail();

            $mulai = $this->proyekPenggajian->periode_mulai->copy();
            $selesai = $this->proyekPenggajian->periode_selesai->copy();

            if ($mulai->gt($selesai)) {
                (new MainHelper)->doAlert($this, 'error', 'Periode Penggajian Proyek Tidak Valid !');

                return;
            }

            $this->state['proyek_pekerja_id'] = $this->selectedProyekPenggajianPekerja->proyek_pekerja_id;
            $this->state['nama_pekerja'] = $this->selectedProyekPenggajianPekerja->proyekPekerja?->nama_pekerja;
            $this->state['jabatan'] = $this->selectedProyekPenggajianPekerja->proyekPekerja?->status_jabatan;
            $this->state['tarif_harian'] = number_format((float) ($this->selectedProyekPenggajianPekerja->proyekPekerja?->tarif_harian ?? 0), 0, ',', '.');
            $this->state['tarif_overtime'] = number_format((float) ($this->selectedProyekPenggajianPekerja->proyekPekerja?->tarif_overtime ?? 0), 0, ',', '.');

            $this->state['bonus'] = $this->selectedProyekPenggajianPekerja->bonus;
            $this->state['bonus_text'] = number_format((float) ($this->selectedProyekPenggajianPekerja->bonus ?? 0), 0, ',', '.');
            $this->state['potongan'] = $this->selectedProyekPenggajianPekerja->potongan;
            $this->state['potongan_text'] = number_format((float) ($this->selectedProyekPenggajianPekerja->potongan ?? 0), 0, ',', '.');
            $this->state['kasbon'] = $this->selectedProyekPenggajianPekerja->kasbon;
            $this->state['kasbon_text'] = number_format((float) ($this->selectedProyekPenggajianPekerja->kasbon ?? 0), 0, ',', '.');
            $this->state['keterangan'] = $this->selectedProyekPenggajianPekerja->keterangan;

            $existingMap = $this->selectedProyekPenggajianPekerja->proyekPenggajianPekerjaHari
                ->keyBy(fn ($h): string => $h->tanggal->format('Y-m-d'));

            $hari = [];
            while ($mulai->lte($selesai)) {
                $key = $mulai->format('Y-m-d');
                $existingHari = $existingMap->get($key);

                $hari[$mulai->format('Ymd')] = [
                    'proyek_penggajian_pekerja_id' => $this->selectedProyekPenggajianPekerja->id,
                    'tanggal' => $key,
                    'tanggal_text' => $mulai->locale('id')->format('d M Y'),
                    'hari' => $mulai->locale('id')->translatedFormat('l'),
                    'hari_normal' => $existingHari ? (float) $existingHari->hari_normal : 0,
                    'jam_overtime' => $existingHari ? (float) $existingHari->jam_overtime : 0,
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

    public function closeModalPencatatan(): void
    {
        $this->reset('state', 'selectedPencatatanUpahId', 'selectedProyekPenggajianPekerja');
        $this->modal = false;
        $this->state = $this->params;
    }

    public function updatedState($value, $key): void
    {
        // Keep total_* in sync without mutating inside getter (pure computed)
        if (str_starts_with((string) $key, 'hari') || in_array($key, ['bonus', 'potongan', 'kasbon'], true)) {
            $totalHari = array_sum(array_column($this->state['hari'] ?? [], 'hari_normal'));
            $totalOvertime = array_sum(array_column($this->state['hari'] ?? [], 'jam_overtime'));
            $this->state['total_hari'] = $totalHari;
            $this->state['total_overtime'] = $totalOvertime;
            $this->state['total_upah'] = $this->totalUpah;
        }
    }
    // End Modal Pencatatan Upah

    // Modal Summary (read-only)
    public function updatedSummaryModal($val): void
    {
        if (! $val) {
            $this->closeSummary();
        }
    }

    public function openSummary(int $id): void
    {
        $this->ensureCanManage();

        // Close pencatatan modal if open to avoid double overlay
        if ($this->modal) {
            $this->closeModalPencatatan();
        }

        try {
            $this->selectedSummary = ProyekPenggajianPekerja::with([
                'proyekPekerja',
                'proyekPenggajianPekerjaHari',
            ])->where('id', $id)->firstOrFail();

            $this->summaryModal = true;
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this, 'error', 'Gagal memuat ringkasan.');
            $this->closeSummary();
        }
    }

    public function closeSummary(): void
    {
        $this->summaryModal = false;
        $this->reset('selectedSummary');
    }

    public function getSummaryTotalHariProperty(): float
    {
        if (! $this->selectedSummary) {
            return 0;
        }

        return (float) $this->selectedSummary->proyekPenggajianPekerjaHari->sum('hari_normal');
    }

    public function getSummaryTotalOvertimeProperty(): float
    {
        if (! $this->selectedSummary) {
            return 0;
        }

        return (float) $this->selectedSummary->proyekPenggajianPekerjaHari->sum('jam_overtime');
    }
    // End Modal Summary

    // Modal Bayar (ubah status + tanggal + catatan)
    public function updatedBayarModal($val): void
    {
        if (! $val) {
            $this->closeBayarModal();
        }
    }

    public function openBayarModal(int $id): void
    {
        $this->ensureCanManage();

        if ($this->modal) {
            $this->closeModalPencatatan();
        }
        if ($this->summaryModal) {
            $this->closeSummary();
        }

        $this->reset('bayarState', 'selectedBayar');
        $this->bayarState = $this->bayarParams;

        try {
            $this->selectedBayar = ProyekPenggajianPekerja::with(['proyekPekerja'])->where('id', $id)->firstOrFail();

            $this->bayarState['status_bayar'] = $this->selectedBayar->status_bayar->value;
            $this->bayarState['tanggal_bayar'] = $this->selectedBayar->tanggal_bayar?->format('Y-m-d');
            $this->bayarState['keterangan'] = $this->selectedBayar->keterangan;

            $this->bayarModal = true;
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this, 'error', 'Gagal memuat data pembayaran.');
            $this->closeBayarModal();
        }
    }

    public function closeBayarModal(): void
    {
        $this->bayarModal = false;
        $this->reset('bayarState', 'selectedBayar');
        $this->bayarState = $this->bayarParams;
    }

    public function saveBayar(): void
    {
        $this->ensureCanManage();

        if (! $this->selectedBayar) {
            (new MainHelper)->doAlert($this, 'error', 'Data pembayaran tidak ditemukan.');

            return;
        }

        $this->validate([
            'bayarState.status_bayar' => ['required', 'integer', 'in:'.implode(',', array_column(StatusBayar::cases(), 'value'))],
            'bayarState.tanggal_bayar' => ['nullable', 'date', 'required_if:bayarState.status_bayar,'.StatusBayar::SUDAH->value],
            'bayarState.keterangan' => ['nullable', 'string', 'max:1000'],
        ], [
            'bayarState.tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika status sudah dibayar.',
        ]);

        try {
            DB::transaction(function (): void {
                $status = StatusBayar::from((int) $this->bayarState['status_bayar']);
                $tanggal = $this->bayarState['tanggal_bayar'] ? Carbon::parse($this->bayarState['tanggal_bayar'])->format('Y-m-d') : null;

                if ($status === StatusBayar::BELUM) {
                    $tanggal = null;
                }

                $this->selectedBayar->update([
                    'status_bayar' => $status,
                    'tanggal_bayar' => $tanggal,
                    'keterangan' => $this->bayarState['keterangan'],
                ]);
            });

            $this->proyekPenggajian->load([
                'proyekPenggajianPekerja.proyekPekerja',
                'proyekPenggajianPekerja.proyekPenggajianPekerjaHari',
            ]);

            // refresh selected if summary open
            if ($this->selectedSummary && $this->selectedSummary->id === $this->selectedBayar->id) {
                $this->selectedSummary->refresh();
                $this->selectedSummary->load(['proyekPekerja', 'proyekPenggajianPekerjaHari']);
            }

            (new MainHelper)->doAlert($this, 'success', 'Status bayar berhasil diperbarui.');
            $this->closeBayarModal();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this, 'error', 'Gagal menyimpan status bayar.');
        }
    }
    // End Modal Bayar

    public function getTotalUpahProperty(): float
    {
        if (! isset($this->selectedProyekPenggajianPekerja) || ! $this->selectedProyekPenggajianPekerja) {
            return 0;
        }

        $tarifHarian = (float) ($this->selectedProyekPenggajianPekerja?->proyekPekerja?->tarif_harian ?? 0);
        $tarifOvertime = (float) ($this->selectedProyekPenggajianPekerja?->proyekPekerja?->tarif_overtime ?? 0);

        $totalHari = array_sum(array_column($this->state['hari'] ?? [], 'hari_normal'));
        $totalOvertime = array_sum(array_column($this->state['hari'] ?? [], 'jam_overtime'));
        $bonus = (float) ($this->state['bonus'] ?? 0);
        $potongan = (float) ($this->state['potongan'] ?? 0);
        $kasbon = (float) ($this->state['kasbon'] ?? 0);

        return (($tarifHarian * $totalHari) + ($tarifOvertime * $totalOvertime) + $bonus) - ($potongan + $kasbon);
    }

    public function saveData(): void
    {
        $this->ensureCanManage();

        if ($this->selectedProyekPenggajianPekerja && $this->selectedProyekPenggajianPekerja->status_bayar === StatusBayar::SUDAH) {
            (new MainHelper)->doAlert($this, 'warning', 'Pekerja sudah dibayar, tidak bisa disimpan.');

            return;
        }

        $this->validate([
            'state.bonus' => ['required', 'numeric', 'min:0'],
            'state.potongan' => ['required', 'numeric', 'min:0'],
            'state.kasbon' => ['required', 'numeric', 'min:0'],
            'state.keterangan' => ['nullable', 'string', 'max:1000'],
            'state.hari' => ['required', 'array'],
            'state.hari.*.hari_normal' => ['required', 'numeric', 'in:0,0.5,1'],
            'state.hari.*.jam_overtime' => ['required', 'integer', 'min:0', 'max:24'],
            'state.hari.*.tanggal' => ['required', 'date'],
        ]);

        try {
            DB::transaction(function (): void {
                $mulai = $this->proyekPenggajian->periode_mulai->copy();
                $selesai = $this->proyekPenggajian->periode_selesai->copy();

                $allowedDates = [];
                $temp = $mulai->copy();
                while ($temp->lte($selesai)) {
                    $allowedDates[] = $temp->format('Y-m-d');
                    $temp->addDay();
                }

                // Prune Q7: hapus hari di luar range baru
                $this->selectedProyekPenggajianPekerja->proyekPenggajianPekerjaHari()
                    ->whereNotIn('tanggal', $allowedDates)
                    ->delete();

                foreach ($this->state['hari'] as $row) {
                    $this->selectedProyekPenggajianPekerja->proyekPenggajianPekerjaHari()->updateOrCreate(
                        ['tanggal' => $row['tanggal']],
                        [
                            'hari_normal' => $row['hari_normal'],
                            'jam_overtime' => $row['jam_overtime'],
                        ]
                    );
                }

                $totalHari = array_sum(array_column($this->state['hari'], 'hari_normal'));
                $totalOvertime = array_sum(array_column($this->state['hari'], 'jam_overtime'));
                $tarifHarian = (float) ($this->selectedProyekPenggajianPekerja->proyekPekerja?->tarif_harian ?? $this->selectedProyekPenggajianPekerja->tarif_harian ?? 0);
                $tarifOvertime = (float) ($this->selectedProyekPenggajianPekerja->proyekPekerja?->tarif_overtime ?? $this->selectedProyekPenggajianPekerja->tarif_overtime ?? 0);
                $bonus = (float) $this->state['bonus'];
                $potongan = (float) $this->state['potongan'];
                $kasbon = (float) $this->state['kasbon'];

                $gajiNormal = $tarifHarian * $totalHari;
                $upahOvertime = $tarifOvertime * $totalOvertime;
                $totalBersih = ($gajiNormal + $upahOvertime + $bonus) - ($potongan + $kasbon);

                $this->selectedProyekPenggajianPekerja->update([
                    'total_hari' => $totalHari,
                    'total_overtime' => $totalOvertime,
                    'gaji_normal' => $gajiNormal,
                    'upah_overtime' => $upahOvertime,
                    'bonus' => $bonus,
                    'potongan' => $potongan,
                    'kasbon' => $kasbon,
                    'total_bersih' => $totalBersih,
                    'keterangan' => $this->state['keterangan'],
                ]);
            });

            $this->proyekPenggajian->load([
                'proyekPenggajianPekerja.proyekPekerja',
                'proyekPenggajianPekerja.proyekPenggajianPekerjaHari',
            ]);

            $this->selectedProyekPenggajianPekerja->load(['proyekPekerja', 'proyekPenggajianPekerjaHari']);

            (new MainHelper)->doAlert($this, 'success', 'Pencatatan upah berhasil disimpan.');
            $this->closeModalPencatatan();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            (new MainHelper)->doAlert($this, 'error', 'Gagal menyimpan pencatatan upah.');
            dd($th);
        }
    }
}
