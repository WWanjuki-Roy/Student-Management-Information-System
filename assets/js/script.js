/* =========================================
   SIMS MAIN JAVASCRIPT FILE
========================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================
       ACTIVE SIDEBAR LINK
    ===================================== */
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-link");

    navLinks.forEach(link => {
        if (link.getAttribute("href").includes(currentPage)) {
            link.classList.add("active");
        }
    });

    /* =====================================
       AUTO DISMISS ALERTS
    ===================================== */
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    /* =====================================
       DELETE CONFIRMATION MODAL
    ===================================== */
    const deleteButtons = document.querySelectorAll(".btn-danger");

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function (e) {
            if (btn.dataset.confirm !== "true") {
                e.preventDefault();

                if (confirm("Are you sure you want to delete this record?")) {
                    btn.dataset.confirm = "true";
                    btn.click();
                }
            }
        });
    });

    /* =====================================
       SIMPLE TABLE SEARCH FILTER
    ===================================== */
    const searchInputs = document.querySelectorAll(".table-search");

    searchInputs.forEach(input => {
        input.addEventListener("keyup", function () {
            const filter = input.value.toLowerCase();
            const table = input.closest(".card").querySelector("table");
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    });

    /* =====================================
       DASHBOARD COUNTER ANIMATION
    ===================================== */
    const counters = document.querySelectorAll(".counter");

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute("data-target");
            const count = +counter.innerText;
            const increment = target / 50;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 20);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });

});
