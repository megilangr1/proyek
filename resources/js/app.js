import registerAlpineComponents from "./alpine";
import { initSwiperSystem } from "./components/swiper";
import { initMotionSystem } from "./motion";

// Init Alpine Data
registerAlpineComponents();

// Init Framer Motion
initMotionSystem();

// Init Swiper JS
initSwiperSystem();
