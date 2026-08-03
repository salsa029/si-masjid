import "./bootstrap";
import "../css/app.css";

// ============================================================
// ALPINE.JS (untuk interaktivitas ringan)
// ============================================================
import Alpine from "alpinejs";
import focus from "@alpinejs/focus";
import collapse from "@alpinejs/collapse";

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.plugin(collapse);
Alpine.start();

// ============================================================
// AXIOS
// ============================================================
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ============================================================
// NAVBAR FUNCTIONS
// ============================================================
function initNavbar() {
    const navbar = document.getElementById("navbar");
    if (navbar) {
        window.addEventListener("scroll", function () {
            navbar.classList.toggle("is-scrolled", window.scrollY > 50);
        });
    }
}

// ============================================================
// BACK TO TOP
// ============================================================
function initBackToTop() {
    const backToTopBtn = document.getElementById("back-to-top");
    if (backToTopBtn) {
        window.addEventListener("scroll", function () {
            backToTopBtn.classList.toggle("visible", window.scrollY > 400);
        });

        backToTopBtn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
}

// ============================================================
// TOAST AUTO-HIDE
// ============================================================
function initToast() {
    const toast = document.getElementById("toast");
    if (toast) {
        setTimeout(function () {
            toast.classList.add("hide");
            setTimeout(function () {
                toast.remove();
            }, 400);
        }, 4000);
    }
}

// ============================================================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ============================================================
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (href === "#") return;

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const offsetTop =
                    target.getBoundingClientRect().top +
                    window.pageYOffset -
                    80;
                window.scrollTo({ top: offsetTop, behavior: "smooth" });
            }
        });
    });
}

// ============================================================
// ACTIVE NAV LINK (Intersection Observer)
// ============================================================
function initActiveNavLink() {
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link, .mobile-link");

    if (sections.length > 0 && navLinks.length > 0) {
        const observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        const currentId = entry.target.getAttribute("id");
                        navLinks.forEach(function (link) {
                            link.classList.remove("active");
                            if (link.getAttribute("href") === "#" + currentId) {
                                link.classList.add("active");
                            }
                        });
                    }
                });
            },
            { threshold: 0.3 },
        );

        sections.forEach(function (section) {
            observer.observe(section);
        });
    }
}

// ============================================================
// DONATION HANDLER
// ============================================================
function initDonationHandler() {
    window.handleDonation = function () {
        const target = document.querySelector("#donation");
        if (target) {
            const offsetTop =
                target.getBoundingClientRect().top + window.pageYOffset - 80;
            window.scrollTo({ top: offsetTop, behavior: "smooth" });
        }
    };
}

// ============================================================
// IMAGE LAZY LOADING
// ============================================================
function initLazyLoading() {
    if ("IntersectionObserver" in window) {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        const imageObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute("src");
                    if (src) {
                        img.src = src;
                    }
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(function (img) {
            imageObserver.observe(img);
        });
    }
}

// ============================================================
// SHIMMER LOADING
// ============================================================
function initShimmerLoading() {
    document.querySelectorAll(".skeleton").forEach(function (el) {
        setTimeout(() => {
            el.classList.remove("skeleton");
        }, 1000);
    });
}

// ============================================================
// CONSOLE WELCOME
// ============================================================
function showConsoleWelcome() {
    console.log(
        "🕌 " + (document.querySelector("title")?.textContent || "SI-MASJID"),
    );
    console.log("✅ Sistem Informasi Masjid loaded successfully!");
    console.log("📅 " + new Date().toLocaleString("id-ID"));
    console.log(
        "🔧 Mode: " + (import.meta.env.DEV ? "Development" : "Production"),
    );
}

// ============================================================
// DOM READY - INITIALIZE EVERYTHING
// ============================================================
document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    // ===== NAVBAR =====
    initNavbar();

    // ===== BACK TO TOP =====
    initBackToTop();

    // ===== TOAST =====
    initToast();

    // ===== SMOOTH SCROLL =====
    initSmoothScroll();

    // ===== ACTIVE NAV LINK =====
    initActiveNavLink();

    // ===== DONATION HANDLER =====
    initDonationHandler();

    // ===== LAZY LOADING =====
    initLazyLoading();

    // ===== SHIMMER LOADING =====
    initShimmerLoading();

    // ===== CONSOLE WELCOME =====
    showConsoleWelcome();
});
