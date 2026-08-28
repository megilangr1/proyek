import Swiper from "swiper/bundle";
import "swiper/css/bundle";

function initBannerSwiper(root = document) {
    const swiper = new Swiper();

    const elements = [];

    if (root instanceof Element && root.matches("[data-store-banner-swiper]")) {
        elements.push(root);
    }

    if (root instanceof Element || root instanceof Document) {
        elements.push(...root.querySelectorAll("[data-store-banner-swiper]"));
    }

    elements.forEach((el) => {
        if (el.dataset.swiperInit) {
            return;
        }

        el.dataset.swiperInit = "1";

        const reduced = window.matchMedia(
            "(prefers-reduced-motion: reduce)",
        ).matches;

        new Swiper(el, {
            effect: "fade",
            fadeEffect: { crossFade: true },
            loop: true,
            speed: 700,
            autoplay: reduced
                ? false
                : { delay: 5000, disableOnInteraction: false },
            pagination: {
                el: el.querySelector(".swiper-pagination"),
                clickable: true,
            },
            navigation: {
                nextEl: el.querySelector(".swiper-button-next"),
                prevEl: el.querySelector(".swiper-button-prev"),
            },
        });
    });
}

export function initSwiperSystem() {
    initBannerSwiper(document);

    document.addEventListener(
        "livewire:init",
        () => {
            Livewire.hook("morph.added", ({ el }) => {
                initBannerSwiper(el);
            });

            Livewire.hook("morph.updated", ({ el }) => {
                initBannerSwiper(el);
            });
        },
        { once: true },
    );

    document.addEventListener("livewire:navigated", () => {
        initBannerSwiper(document);
    });
}
