import Swal from "sweetalert2";
import registerAlpineComponents from "./alpine";
import { initSwiperSystem } from "./components/swiper";
import { initMotionSystem } from "./motion";
import TomSelect from "tom-select";

window.Swal = Swal;
window.TomSelect = TomSelect;

window.Toast = Swal.mixin({
    toast: true,
    position: "bottom-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

// Sweet Alert 2
window.deleteSwal = (event) => {
    Swal.fire({
        title: "Lakukan Penghapusan Data ?",
        text: "Data terhapus tidak dapat di-pulihkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd3333",
        cancelButtonColor: "#666666",
        confirmButtonText: "Ya, Hapus Data!",
        cancelButtonText: "Batalkan Aksi",
    }).then((result) => {
        if (result.isConfirmed) {
            event && event();
        }
    });
};

// Init Alpine Data
registerAlpineComponents();

// Init Framer Motion
initMotionSystem();

// Init Swiper JS
initSwiperSystem();
