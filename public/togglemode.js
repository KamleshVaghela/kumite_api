document.addEventListener("DOMContentLoaded", function (event) {
    const Tag = document.documentElement; /* Get <html> tag */
    const Switch = document.getElementById("darkSwitch");
    Tag.dataset.theme = "";
    Switch.addEventListener("click", () => {
        Switch.checked
            ? (Tag.dataset.theme = "dark")
            : (Tag.dataset.theme = "");
    });
});
