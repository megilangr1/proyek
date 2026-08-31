@livewireScripts

<script>
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
    });
</script>

<script>
    document.addEventListener('livewire:navigated', () => {
        // When Navigated
    });
</script>


@yield('script')
@stack('script')
