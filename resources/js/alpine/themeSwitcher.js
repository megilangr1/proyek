export default () => ({
    theme: "corporate",

    init() {
        this.theme = localStorage.getItem("theme") ?? "corporate";
    },

    toggle() {
        this.theme = this.theme === "corporate" ? "luxury" : "corporate";

        localStorage.setItem("theme", this.theme);
    },
});
