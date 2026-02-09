// resources/js/app.js
import "./bootstrap";
import "bootstrap";

// Alpine.js for interactive components
document.addEventListener("alpine:init", () => {
    Alpine.data("dashboard", () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
    }));
});

// Initialize components when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });

    // Navbar background on scroll
    window.addEventListener("scroll", function () {
        const navbar = document.querySelector(".navbar");
        if (navbar && window.scrollY > 100) {
            navbar.style.background = "rgba(0, 87, 81, 0.95)";
            navbar.style.backdropFilter = "blur(10px)";
        } else if (navbar) {
            navbar.style.background = "rgba(0, 87, 81, 0.9)";
            navbar.style.backdropFilter = "blur(10px)";
        }
    });

    // Profile image preview
    window.previewImage = function (input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById("profile-preview");
                const placeholder = document.getElementById(
                    "no-image-placeholder"
                );

                if (preview && placeholder) {
                    preview.src = e.target.result;
                    preview.classList.remove("d-none");
                    placeholder.classList.add("d-none");
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    };
});
