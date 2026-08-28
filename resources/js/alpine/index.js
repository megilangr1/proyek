import themeSwitcher from "./themeSwitcher";

export default function registerAlpineComponents() {
    document.addEventListener("alpine:init", () => {
        Alpine.data("themeSwitcher", themeSwitcher);
    });
}
