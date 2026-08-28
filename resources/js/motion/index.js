import {
    fadeUp,
    fadeDown,
    fadeLeft,
    fadeRight,
    blurFade,
    scaleIn,
    popIn,
    rotateIn,
    slideScale,
    springIn,
    reveal,
    revealLeft,
    bounceIn,
    floatIn,
    blurScale,
    scrollParallax,
    scrollProgress,
    scrollFade,
    staggerReveal,
    textSplit,
    tiltIn,
    flipIn,
    wiggle,
    heartbeat,
    shake,
    shine,
    marquee,
    hoverLift,
    pressScale,
    splash,
} from "./animations";

const animations = {
    "fade-up": fadeUp,
    "fade-down": fadeDown,
    "fade-left": fadeLeft,
    "fade-right": fadeRight,

    "blur-fade": blurFade,
    "blur-scale": blurScale,

    "scale-in": scaleIn,
    "slide-scale": slideScale,
    "spring-in": springIn,

    "pop-in": popIn,
    "rotate-in": rotateIn,
    "bounce-in": bounceIn,

    "float-in": floatIn,

    reveal: reveal,
    "reveal-left": revealLeft,

    // Scroll-driven
    "scroll-parallax": scrollParallax,
    "scroll-progress": scrollProgress,
    "scroll-fade": scrollFade,

    // Stagger / Text
    stagger: staggerReveal,
    "text-split": textSplit,

    // Playful / 3D
    "tilt-in": tiltIn,
    "flip-in": flipIn,
    wiggle: wiggle,
    heartbeat: heartbeat,
    shake: shake,
    shine: shine,
    marquee: marquee,

    // Interactive
    "hover-lift": hoverLift,
    "press-scale": pressScale,

    splash: splash,
};

const registry = new WeakMap();

function getOptions(element) {
    return {
        // General
        duration: Number(element.dataset.motionDuration ?? 0.6),

        delay: Number(element.dataset.motionDelay ?? 0),

        // Movement
        distance: Number(element.dataset.motionDistance ?? 40),

        // Transform
        scale: Number(element.dataset.motionScale ?? 0.9),

        rotate: Number(element.dataset.motionRotate ?? -8),

        // Blur
        blur: Number(element.dataset.motionBlur ?? 10),

        // Spring
        stiffness: Number(element.dataset.motionStiffness ?? 180),

        damping: Number(element.dataset.motionDamping ?? 18),

        // Scroll
        speed: Number(element.dataset.motionSpeed ?? 0.3),

        axis: element.dataset.motionAxis ?? "y",

        // Stagger / Split
        stagger: Number(element.dataset.motionStagger ?? 0.08),

        split: element.dataset.motionSplit ?? "word",

        // Repetition
        repeat: element.dataset.motionRepeat ?? "infinite",
    };
}

function getMotionElements(root) {
    const elements = [];

    if (root instanceof Element && root.matches("[data-motion]")) {
        elements.push(root);
    }

    if (
        root instanceof Element ||
        root instanceof Document ||
        root instanceof DocumentFragment
    ) {
        elements.push(...root.querySelectorAll("[data-motion]"));
    }

    return elements;
}

function initializeElement(element) {
    if (!(element instanceof Element)) {
        return;
    }

    const type = element.dataset.motion;

    if (!type) {
        return;
    }

    const animation = animations[type];

    if (!animation) {
        console.warn(`[Motion] Unknown animation: ${type}`);

        return;
    }

    const existing = registry.get(element);

    /**
     * Already initialized.
     */
    if (existing?.type === type) {
        return;
    }

    /**
     * Animation type changed.
     */
    if (existing) {
        existing.cleanup?.();
        registry.delete(element);
    }

    const cleanup = animation(element, getOptions(element));

    registry.set(element, {
        type,
        cleanup,
    });
}

function initializeTree(root = document) {
    getMotionElements(root).forEach(initializeElement);
}

function cleanupElement(element) {
    const existing = registry.get(element);

    if (!existing) {
        return;
    }

    existing.cleanup?.();

    registry.delete(element);
}

function cleanupTree(root) {
    if (!(root instanceof Element)) {
        return;
    }

    cleanupElement(root);

    root.querySelectorAll("[data-motion]").forEach(cleanupElement);
}

export function initMotionSystem() {
    /**
     * Initial page load.
     */
    initializeTree(document);

    /**
     * Livewire.
     */
    document.addEventListener(
        "livewire:init",
        () => {
            Livewire.hook("morph.added", ({ el }) => {
                initializeTree(el);
            });

            Livewire.hook("morph.updated", ({ el }) => {
                initializeElement(el);
            });

            Livewire.hook("morph.removing", ({ el }) => {
                cleanupTree(el);
            });
        },
        { once: true },
    );

    /**
     * Livewire Navigate.
     */
    document.addEventListener("livewire:navigated", () => {
        initializeTree(document);
    });
}
