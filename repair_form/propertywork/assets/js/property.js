// Search Table

document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("searchInput");

    if (!input) return;

    input.addEventListener("keyup", function () {

        const filter = this.value.toUpperCase();

        const tr = document.querySelectorAll("#propertyTable tbody tr");

        tr.forEach(row => {

            let text = row.innerText.toUpperCase();

            row.style.display = text.indexOf(filter) > -1 ? "" : "none";

        });

    });

});