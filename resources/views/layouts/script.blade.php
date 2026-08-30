@livewireScripts

<script>
    function initTomSelect(selector, options = {}, customAction) {
        const el = document.querySelector(selector);
        if (!el) return null;

        if (!el.tomselect) {
            el.tomselect = new TomSelect(el, {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                plugins: ["dropdown_input"],
                maxItems: 1,
                allowEmptyOption: false,
                onChange(value) {
                    const model = el.getAttribute("wire:model");
                    if (model) {
                        Livewire.find(
                            el.closest("[wire\\:id]").getAttribute("wire:id")
                        ).set(model, value);
                    }

                    customAction?.(value, el.tomselect);
                },
                ...options,
            });
        }

        return el.tomselect;
    }

    function doSwal(event, info) {
        Swal.fire({
            title: info.title,
            text: info.text,
            icon: info.icon ?? "question",
            showCancelButton: true,
            confirmButtonColor: info.confirmButtonColor ?? "#fcb700",
            cancelButtonColor: "#666666",
            confirmButtonText: info.confirmButtonText ?? "Ya !",
            cancelButtonText: "Batalkan Aksi",
        }).then((result) => {
            if (result.isConfirmed) {
                event && event();
            }
        });
    };

    function waitForLivewireReady(callback) {
        if (window.Livewire) return callback();
        document.addEventListener('livewire:init', callback, {
            once: true
        });
    }

    waitForLivewireReady(() => {
        document.addEventListener('click', (e) => {
            // Delete Btn
            if (e.target.closest('.delete-btn')) { // aman walau ada <i> di dalam button
                const id = e.target.closest('.delete-btn').dataset.id;
                const compTarget = e.target.closest('.delete-btn').dataset.target;

                deleteSwal(() => {
                    Livewire.dispatchTo(compTarget, 'doDelete', {
                        id
                    });
                });
            }
        })
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // console.log("DOM siap");
    });
</script>

<script>
    document.addEventListener('livewire:init', () => {
        // Toast
        Livewire.on('toast', (event) => {
            Toast.fire({
                icon: event.type || "question",
                title: event.message || "Aksi Berhasil di-Lakukan !"
            });
        });

        // Tom Select
        Livewire.on('setTomSelect', (data) => {
            const selectData = Object.values(data[0] ?? []);

            if (selectData != null) {
                selectData.forEach(value => {
                    const el = document.getElementById(value.selectId);
                    if (el && el.tomselect) {
                        const ts = el.tomselect;
                        if (value.option) {
                            ts.addOption(value.option);
                        }
                        ts.setValue(value.value, true);
                    }
                });
            }
        });
    });
</script>

<script>
    document.addEventListener('livewire:navigated', () => {
        // When Navigated
    });
</script>


@yield('script')
@stack('script')
