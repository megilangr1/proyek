import {
    animate,
    inView,
    scroll,
    scrollInfo,
    stagger as staggerFn,
    motionValue,
    hover,
    press,
    backOut,
} from "motion";

/**
 * -------------------------------------------------------
 * Shared helpers
 * -------------------------------------------------------
 */

function prefersReducedMotion() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}

function visible(element) {
    element.style.opacity = "1";
    element.style.transform = "none";
    element.style.filter = "none";
}

/**
 * -------------------------------------------------------
 * 1. Fade Up
 * -------------------------------------------------------
 *
 * data-motion="fade-up"
 */

export function fadeUp(element, options = {}) {
    const { duration = 0.6, delay = 0, distance = 40 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [distance, 0],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 2. Fade Down
 * -------------------------------------------------------
 *
 * data-motion="fade-down"
 */

export function fadeDown(element, options = {}) {
    const { duration = 0.6, delay = 0, distance = 40 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [-distance, 0],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 3. Fade Left
 * -------------------------------------------------------
 *
 * data-motion="fade-left"
 */

export function fadeLeft(element, options = {}) {
    const { duration = 0.6, delay = 0, distance = 40 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                x: [-distance, 0],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 4. Fade Right
 * -------------------------------------------------------
 *
 * data-motion="fade-right"
 */

export function fadeRight(element, options = {}) {
    const { duration = 0.6, delay = 0, distance = 40 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                x: [distance, 0],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 5. Blur Fade
 * -------------------------------------------------------
 *
 * data-motion="blur-fade"
 */

export function blurFade(element, options = {}) {
    const { duration = 0.7, delay = 0, distance = 20, blur = 10 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";
    element.style.filter = `blur(${blur}px)`;

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [distance, 0],
                filter: [`blur(${blur}px)`, "blur(0px)"],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 6. Scale In
 * -------------------------------------------------------
 *
 * data-motion="scale-in"
 */

export function scaleIn(element, options = {}) {
    const { duration = 0.6, delay = 0, scale = 0.9 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                scale: [scale, 1],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 7. Pop In
 * -------------------------------------------------------
 *
 * Lebih playful daripada scale-in.
 *
 * data-motion="pop-in"
 */

export function popIn(element, options = {}) {
    const { delay = 0 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                scale: [0.7, 1.05, 1],
            },
            {
                duration: 0.7,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 8. Rotate In
 * -------------------------------------------------------
 *
 * data-motion="rotate-in"
 */

export function rotateIn(element, options = {}) {
    const { duration = 0.7, delay = 0, rotate = -8 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                rotate: [rotate, 0],
                scale: [0.95, 1],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 9. Slide + Scale
 * -------------------------------------------------------
 *
 * data-motion="slide-scale"
 */

export function slideScale(element, options = {}) {
    const { duration = 0.7, delay = 0, distance = 50 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [distance, 0],
                scale: [0.95, 1],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 10. Spring In
 * -------------------------------------------------------
 *
 * data-motion="spring-in"
 */

export function springIn(element, options = {}) {
    const { delay = 0, distance = 50 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [distance, 0],
                scale: [0.9, 1],
            },
            {
                delay,
                type: "spring",
                stiffness: 180,
                damping: 18,
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 11. Reveal
 * -------------------------------------------------------
 *
 * data-motion="reveal"
 */

export function reveal(element, options = {}) {
    const { duration = 0.8, delay = 0 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.clipPath = "inset(0 0 100% 0)";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                clipPath: ["inset(0 0 100% 0)", "inset(0 0 0% 0)"],
            },
            {
                duration,
                delay,
                ease: "easeInOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 12. Reveal Left
 * -------------------------------------------------------
 *
 * data-motion="reveal-left"
 */

export function revealLeft(element, options = {}) {
    const { duration = 0.8, delay = 0 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.clipPath = "inset(0 100% 0 0)";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                clipPath: ["inset(0 100% 0 0)", "inset(0 0% 0 0)"],
            },
            {
                duration,
                delay,
                ease: "easeInOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 13. Bounce In
 * -------------------------------------------------------
 *
 * data-motion="bounce-in"
 */

export function bounceIn(element, options = {}) {
    const { delay = 0 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [80, -10, 0],
            },
            {
                delay,
                duration: 0.9,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 14. Float In
 * -------------------------------------------------------
 *
 * data-motion="float-in"
 */

export function floatIn(element, options = {}) {
    const { duration = 0.8, delay = 0, distance = 30 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                y: [distance, 0],
                rotateX: [15, 0],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 16. Scroll Parallax
 * -------------------------------------------------------
 *
 * Elemen bergerak dengan kecepatan berbeda dari scroll halaman.
 * Kecepatan via `data-motion-speed` (default 0.3), arah via `data-motion-axis`.
 *
 * data-motion="scroll-parallax"
 */
export function scrollParallax(element, options = {}) {
    const { speed = 0.3, axis = "y" } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    element.style.willChange = "transform";

    const offset = motionValue(0);
    const anim = animate(element, { [axis]: offset }, { ease: "linear" });

    const stopScroll = scrollInfo(({ y }) => {
        offset.set(y.current * speed);
    });

    return () => {
        stopScroll?.();
        anim?.stop();
        element.style.willChange = "none";
        element.style.transform = "none";
    };
}

/**
 * -------------------------------------------------------
 * 17. Scroll Progress
 * -------------------------------------------------------
 *
 * Progress bar `scaleX` 0 → 1 mengikuti scroll halaman.
 * Elemen disarankan: fixed/tipis dengan `transform-origin: left`.
 *
 * data-motion="scroll-progress"
 */
export function scrollProgress(element) {
    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.transformOrigin = "0 0";
    element.style.transform = "scaleX(0)";
    element.style.willChange = "transform";

    const scaleX = motionValue(0);
    const anim = animate(element, { scaleX }, { ease: "linear" });

    const stopScroll = scroll((progress) => {
        scaleX.set(progress);
    });

    return () => {
        stopScroll?.();
        anim?.stop();
        element.style.willChange = "none";
        element.style.transform = "none";
    };
}

/**
 * -------------------------------------------------------
 * 18. Scroll Fade
 * -------------------------------------------------------
 *
 * Opacity + translate mengikuti posisi elemen di viewport
 * (memudar saat keluar layar saat scroll).
 *
 * data-motion="scroll-fade"
 */
export function scrollFade(element) {
    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    const anim = animate(
        element,
        {
            opacity: [1, 0],
            y: [0, -80],
        },
        { ease: "linear" },
    );

    const stopScroll = scroll(anim, { target: element });

    return () => {
        stopScroll?.();
        anim?.stop();
    };
}

/**
 * -------------------------------------------------------
 * 19. Stagger Reveal
 * -------------------------------------------------------
 *
 * Di parent: semua direct child fade-up berurutan.
 * Jarak antar-child via `data-motion-stagger` (default 0.08).
 *
 * data-motion="stagger"
 */
export function staggerReveal(element, options = {}) {
    const {
        duration = 0.6,
        delay = 0,
        distance = 30,
        stagger = 0.08,
    } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    const children = Array.from(element.children);
    const delayFor = staggerFn(stagger, { startDelay: 0 });

    const animations = new Map();

    children.forEach((child) => {
        child.style.opacity = "0";
        child.style.transform = `translate3d(0, ${distance}px, 0)`;
    });

    const stopInView = inView(element, () => {
        children.forEach((child, index) => {
            animations.set(
                child,
                animate(
                    child,
                    {
                        opacity: [0, 1],
                        y: [distance, 0],
                    },
                    {
                        duration,
                        delay: delay + delayFor(index),
                        ease: "easeOut",
                    },
                ),
            );
        });
    });

    return () => {
        stopInView?.();
        animations.forEach((animation) => animation?.stop());
        animations.clear();
        children.forEach((child) => {
            child.style.opacity = "1";
            child.style.transform = "none";
        });
    };
}

/**
 * -------------------------------------------------------
 * 20. Text Split
 * -------------------------------------------------------
 *
 * Heading plain-text dipecah per kata (default) atau per karakter
 * (`data-motion-split="char"`), lalu blur-fade berurutan.
 * Hanya bekerja pada elemen tanpa child element (plain text).
 *
 * data-motion="text-split"
 */
export function textSplit(element, options = {}) {
    const {
        duration = 0.6,
        delay = 0,
        blur = 8,
        distance = 24,
        stagger = 0.05,
    } = options;

    if (prefersReducedMotion() || element.children.length > 0) {
        visible(element);
        return () => {};
    }

    const original = element.innerHTML;
    const splitChar = element.dataset.motionSplit === "char";
    const raw = element.textContent;
    const pieces = splitChar ? Array.from(raw) : raw.trim().split(/\s+/);
    const container = document.createElement("span");
    container.style.display = "inline-block";

    const spans = pieces.map((piece, index) => {
        const span = document.createElement("span");
        span.className = "motion-text-piece inline-block";
        span.style.display = "inline-block";
        span.style.opacity = "0";
        span.style.filter = `blur(${blur}px)`;
        span.style.transform = `translate3d(0, ${distance}px, 0)`;
        span.textContent = piece;
        container.appendChild(span);

        if (!splitChar && index < pieces.length - 1) {
            container.appendChild(document.createTextNode(" "));
        }

        return span;
    });

    element.innerHTML = "";
    element.appendChild(container);

    const delayFor = staggerFn(stagger, { startDelay: 0 });
    const animations = [];

    const stopInView = inView(element, () => {
        spans.forEach((span, index) => {
            animations.push(
                animate(
                    span,
                    {
                        opacity: [0, 1],
                        y: [distance, 0],
                        filter: [`blur(${blur}px)`, "blur(0px)"],
                    },
                    {
                        duration,
                        delay: delay + delayFor(index),
                        ease: "easeOut",
                    },
                ),
            );
        });
    });

    return () => {
        stopInView?.();
        animations.forEach((animation) => animation?.stop());
        element.innerHTML = original;
    };
}

/**
 * -------------------------------------------------------
 * 21. Tilt In
 * -------------------------------------------------------
 *
 * Entrance 3D: rotateX + rotateY + translateZ (perspective di parent).
 *
 * data-motion="tilt-in"
 */
export function tiltIn(element, options = {}) {
    const {
        duration = 0.8,
        delay = 0,
        scale = 1.05,
        rotateX = 12,
        rotateY = -14,
    } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";
    if (element.parentElement) {
        element.parentElement.style.perspective = "900px";
    }

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                rotateX: [rotateX, 0],
                rotateY: [rotateY, 0],
                scale: [scale, 1],
                z: [-40, 0],
            },
            {
                duration,
                delay,
                ease: backOut,
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
        if (element.parentElement) {
            element.parentElement.style.perspective = "none";
        }
    };
}

/**
 * -------------------------------------------------------
 * 22. Flip In
 * -------------------------------------------------------
 *
 * Entrance flip kartu: rotateY 90° → 0 dengan perspective.
 *
 * data-motion="flip-in"
 */
export function flipIn(element, options = {}) {
    const { duration = 0.8, delay = 0 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";
    element.style.transformStyle = "preserve-3d";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                rotateY: [90, 0],
                transformPerspective: 600,
            },
            {
                duration,
                delay,
                ease: backOut,
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
        element.style.transformStyle = "";
    };
}

/**
 * -------------------------------------------------------
 * 23. Wiggle
 * -------------------------------------------------------
 *
 * Goyang settle (rotasi ±) saat elemen masuk viewport. Playful.
 *
 * data-motion="wiggle"
 */
export function wiggle(element, options = {}) {
    const { duration = 0.8, delay = 0, distance = 8 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                rotate: [
                    0,
                    -distance,
                    distance,
                    -distance * 0.7,
                    distance * 0.4,
                    0,
                ],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 24. Heartbeat
 * -------------------------------------------------------
 *
 * Pulse scale berulang — cocok untuk ikon/badge/CTA.
 * Ulangan via `data-motion-repeat` (default "infinite").
 *
 * data-motion="heartbeat"
 */
export function heartbeat(element, options = {}) {
    const { duration = 0.6, delay = 0, repeat = "infinite" } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    const repetitions = repeat === "infinite" ? Infinity : Number(repeat);

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                scale: [1, 1.18, 1],
            },
            {
                duration,
                delay,
                repeat: repetitions,
                repeatType: "loop",
                ease: "easeInOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 25. Shake
 * -------------------------------------------------------
 *
 * Gegar horizontal sekali saat elemen masuk viewport.
 *
 * data-motion="shake"
 */
export function shake(element, options = {}) {
    const { duration = 0.5, delay = 0, distance = 8 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                x: [0, -distance, distance, -distance * 0.7, distance * 0.4, 0],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 26. Shine
 * -------------------------------------------------------
 *
 * Sweep shimmer berulang (background-position). Elemen disarankan
 * memakai `background-image` gradient + `background-size: 200% auto`.
 *
 * data-motion="shine"
 */
export function shine(element, options = {}) {
    const { duration = 1.8, delay = 0 } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                backgroundPosition: ["200% 50%", "-200% 50%"],
            },
            {
                duration,
                delay,
                repeat: Infinity,
                repeatType: "loop",
                ease: "linear",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 27. Marquee
 * -------------------------------------------------------
 *
 * Loop horizontal tak terbatas; konten diduplikasi agar seamless.
 * Elemen disarankan `flex` + `gap`, konten asli di-restore saat cleanup.
 *
 * data-motion="marquee"
 */
export function marquee(element, options = {}) {
    const { duration = 30, delay = 0 } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    const original = element.innerHTML;
    element.style.display = "flex";
    element.style.width = "max-content";
    element.innerHTML = original + original;

    let animation = null;

    animation = animate(
        element,
        {
            x: ["0%", "-50%"],
        },
        {
            duration,
            delay,
            repeat: Infinity,
            repeatType: "loop",
            ease: "linear",
        },
    );

    return () => {
        animation?.stop();
        element.style.display = "";
        element.style.width = "";
        element.innerHTML = original;
    };
}

/**
 * -------------------------------------------------------
 * 28. Hover Lift
 * -------------------------------------------------------
 *
 * Terangkat + sedikit membesar saat hover (spring). Tidak menghilangkan elemen.
 *
 * data-motion="hover-lift"
 */
export function hoverLift(element, options = {}) {
    const { lift = -8 } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    element.style.willChange = "transform";

    let enter = null;
    let leave = null;

    const stopHover = hover(element, () => {
        enter = animate(
            element,
            { y: [0, lift], scale: [1, 1.03] },
            { type: "spring", stiffness: 300, damping: 18 },
        );
    });

    const stopHoverEnd = hover(
        element,
        () => {},
        () => {
            leave = animate(
                element,
                { y: [lift, 0], scale: [1.03, 1] },
                { type: "spring", stiffness: 260, damping: 20 },
            );
        },
    );

    return () => {
        stopHover?.();
        stopHoverEnd?.();
        enter?.stop();
        leave?.stop();
        element.style.willChange = "none";
        element.style.transform = "none";
    };
}

/**
 * -------------------------------------------------------
 * 29. Press Scale
 * -------------------------------------------------------
 *
 * Menyusut saat ditekan (spring), kembali saat dilepas.
 *
 * data-motion="press-scale"
 */
export function pressScale(element, options = {}) {
    const { scale = 0.96 } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    let pressAnim = null;
    let releaseAnim = null;

    const stopPress = press(
        element,
        () => {
            pressAnim?.stop();
            pressAnim = animate(
                element,
                { scale },
                { type: "spring", stiffness: 400, damping: 18 },
            );
        },
        () => {
            releaseAnim?.stop();
            releaseAnim = animate(
                element,
                { scale: 1 },
                { type: "spring", stiffness: 300, damping: 20 },
            );
        },
    );

    return () => {
        stopPress?.();
        pressAnim?.stop();
        releaseAnim?.stop();
        element.style.transform = "none";
    };
}

/**
 * -------------------------------------------------------
 * 31. Blur + Scale
 * -------------------------------------------------------
 *
 * data-motion="blur-scale"
 */
export function blurScale(element, options = {}) {
    const { duration = 0.8, delay = 0, blur = 8 } = options;

    if (prefersReducedMotion()) {
        visible(element);
        return () => {};
    }

    element.style.opacity = "0";
    element.style.filter = `blur(${blur}px)`;

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(
            element,
            {
                opacity: [0, 1],
                filter: [`blur(${blur}px)`, "blur(0px)"],
                scale: [0.92, 1],
            },
            {
                duration,
                delay,
                ease: "easeOut",
            },
        );
    });

    return () => {
        stopInView?.();
        animation?.stop();
        animation = null;
    };
}

/**
 * -------------------------------------------------------
 * 32. Gradient Pan
 * -------------------------------------------------------
 *
 * Elemen dengan background gradient (background-size 200%) dipan
 * secara perlahan & berulang untuk kesan "aurora" hidup.
 *
 * data-motion="gradient-pan"
 */
export function gradientPan(element, options = {}) {
    const { duration = 9 } = options;

    if (prefersReducedMotion()) {
        return () => {};
    }

    const animation = animate(
        element,
        {
            backgroundPosition: ["0% 50%", "100% 50%", "0% 50%"],
        },
        {
            duration,
            repeat: Infinity,
            ease: "linear",
        },
    );

    return () => {
        animation?.stop();
        element.style.backgroundPosition = "";
    };
}

/**
 * -------------------------------------------------------
 * 33. Glow Pulse
 * -------------------------------------------------------
 *
 * Napas glow netral (box-shadow) yang aman di segala tema.
 *
 * data-motion="glow-pulse"
 */
export function glowPulse(element, options = {}) {
    const token = element.dataset.motionGlow ?? options.glow ?? "primary";
    const duration = Number(
        element.dataset.motionDuration ?? options.duration ?? 4.5,
    );

    if (prefersReducedMotion()) {
        return () => {};
    }

    const known = new Set([
        "primary",
        "secondary",
        "accent",
        "neutral",
        "base-content",
        "base-100",
        "base-200",
        "base-300",
        "info",
        "success",
        "warning",
        "error",
    ]);

    const colorRef = token.startsWith("--")
        ? `var(${token})`
        : known.has(token)
          ? `var(--color-${token})`
          : token;

    const glow = (pct) =>
        `0 0 ${28 + pct * 16}px ${2 + pct * 4}px ` +
        `color-mix(in oklch, ${colorRef} ${Math.round(pct * 45)}%, transparent)`;

    const animation = animate(
        element,
        {
            boxShadow: [glow(0), glow(1), glow(0)],
        },
        {
            duration,
            repeat: Infinity,
            ease: "easeInOut",
        },
    );

    return () => {
        animation?.stop();
        element.style.boxShadow = "";
    };
}

/**
 * -------------------------------------------------------
 * 34. Count Up
 * -------------------------------------------------------
 *
 * Hitung angka dari 0 → nilai `data-motion-to` saat masuk viewport.
 * Opsi: `data-motion-decimals` (default 0), `data-motion-suffix`
 * (mis. "rb+", "%").
 *
 * data-motion="count-up"
 */
export function countUp(element, options = {}) {
    const to = Number(element.dataset.motionTo ?? options.to ?? 0);
    const decimals = Number(
        element.dataset.motionDecimals ?? options.decimals ?? 0,
    );
    const suffix = element.dataset.motionSuffix ?? options.suffix ?? "";
    const duration = Number(options.duration ?? 1.4);

    const format = (value) =>
        decimals > 0
            ? Number(value).toFixed(decimals)
            : Math.round(value).toLocaleString("id-ID");

    if (prefersReducedMotion()) {
        element.textContent = format(to) + suffix;

        return () => {};
    }

    let animation = null;

    const stopInView = inView(element, () => {
        animation = animate(0, to, {
            duration,
            ease: "easeOut",
            onUpdate: (value) => {
                element.textContent = format(value) + suffix;
            },
        });
    });

    return () => {
        stopInView?.();
        animation?.stop?.();
        element.textContent = format(to) + suffix;
    };
}

/**
 * -------------------------------------------------------
 * 35. Tilt 3D
 * -------------------------------------------------------
 *
 * Kartu mengikuti kursor (rotateX/rotateY) untuk kesan "tech".
 * Reset halus saat kursor keluar. Hormati reduced-motion.
 *
 * data-motion="tilt-3d"
 */
export function tilt3d(element, options = {}) {
    const max = Number(options.max ?? 8);

    if (prefersReducedMotion()) {
        return () => {};
    }

    if (element.parentElement) {
        element.parentElement.style.perspective = "900px";
    }

    element.style.transformStyle = "preserve-3d";
    element.style.willChange = "transform";

    let animation = null;

    const onMove = (event) => {
        const rect = element.getBoundingClientRect();
        const px = (event.clientX - rect.left) / rect.width - 0.5;
        const py = (event.clientY - rect.top) / rect.height - 0.5;

        animation?.stop();
        animation = animate(
            element,
            {
                rotateY: px * max * 2,
                rotateX: -py * max * 2,
            },
            { type: "spring", stiffness: 200, damping: 18 },
        );
    };

    const onLeave = () => {
        animation?.stop();
        animation = animate(
            element,
            { rotateX: 0, rotateY: 0 },
            { type: "spring", stiffness: 150, damping: 18 },
        );
    };

    element.addEventListener("pointermove", onMove);
    element.addEventListener("pointerleave", onLeave);

    return () => {
        element.removeEventListener("pointermove", onMove);
        element.removeEventListener("pointerleave", onLeave);
        animation?.stop();
        element.style.transformStyle = "";
        element.style.willChange = "none";
        if (element.parentElement) {
            element.parentElement.style.perspective = "none";
        }
    };
}

export function splash(element, options = {}) {
    const { duration = 0.6, delay = 1 } = options;

    let exitAnimation = null;

    /**
     * Initial state
     */
    element.style.transform = "translateY(0)";
    element.style.opacity = "1";

    /**
     * Exit splash after delay
     */
    const timeout = setTimeout(() => {
        exitAnimation = animate(
            element,
            {
                x: "-100%",
            },
            {
                duration,
                ease: [0.1, 0.1, 1, 6.2],

                onComplete: () => {
                    element.style.display = "none";
                },
            },
        );
    }, delay * 1000);

    /**
     * Cleanup
     */
    return () => {
        clearTimeout(timeout);

        exitAnimation?.stop?.();
    };
}
