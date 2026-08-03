import "./bootstrap";
import "../css/app.css";

// ============================================================
// ALPINE.JS (untuk interaktivitas ringan)
// ============================================================
import Alpine from "alpinejs";
import focus from "@alpinejs/focus";

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.start();

// ============================================================
// AXIOS
// ============================================================
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ============================================================
// PRAYER TIMES DATA
// ============================================================
const PRAYER_SCHEDULES = [
    { name: "Subuh", time: "04:30" },
    { name: "Dzuhur", time: "11:45" },
    { name: "Ashar", time: "15:00" },
    { name: "Maghrib", time: "18:30" },
    { name: "Isya", time: "19:45" },
];

// ============================================================
// PRAYER TIMES FUNCTIONS
// ============================================================
function getCurrentPrayerIndex(timeStr) {
    for (let i = PRAYER_SCHEDULES.length - 1; i >= 0; i--) {
        if (timeStr >= PRAYER_SCHEDULES[i].time) return i;
    }
    return PRAYER_SCHEDULES.length - 1;
}

function formatTime(date) {
    return (
        date.getHours().toString().padStart(2, "0") +
        ":" +
        date.getMinutes().toString().padStart(2, "0")
    );
}

function updatePrayerDisplay() {
    const now = new Date();
    const currentTime = formatTime(now);
    const currentIdx = getCurrentPrayerIndex(currentTime);
    const nextIdx = (currentIdx + 1) % PRAYER_SCHEDULES.length;

    // Update prayer cards
    const container = document.getElementById("prayer-times-container");
    if (container) {
        container.innerHTML = PRAYER_SCHEDULES.map((prayer, idx) => {
            const isActive = idx === currentIdx;
            return `
                <div class="prayer-card rounded-xl p-3 text-center backdrop-blur-sm ${
                    isActive ? "active-prayer" : "bg-green-800/40"
                }">
                    <p class="text-xs text-green-200">${prayer.name}</p>
                    <p class="text-lg font-bold text-white">${prayer.time}</p>
                    ${
                        isActive
                            ? '<span class="text-[10px] text-yellow-200">● Sekarang</span>'
                            : ""
                    }
                </div>
            `;
        }).join("");
    }

    // Update current and next prayer info
    const currentPrayer = PRAYER_SCHEDULES[currentIdx];
    const nextPrayer = PRAYER_SCHEDULES[nextIdx];

    const elements = {
        currentName: document.getElementById("current-prayer-name"),
        currentTime: document.getElementById("current-prayer-time"),
        nextInfo: document.getElementById("next-prayer-info"),
        date: document.getElementById("prayer-date"),
        footer: document.getElementById("footer-prayer-times"),
    };

    if (elements.currentName) {
        elements.currentName.textContent = currentPrayer.name;
    }
    if (elements.currentTime) {
        elements.currentTime.textContent = currentPrayer.time;
    }
    if (elements.nextInfo) {
        elements.nextInfo.textContent = `Berikutnya: ${nextPrayer.name} ${nextPrayer.time}`;
    }

    if (elements.date) {
        const dateOptions = {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric",
        };
        elements.date.textContent = `Waktu Sholat Hari Ini - ${now.toLocaleDateString("id-ID", dateOptions)}`;
    }

    if (elements.footer) {
        elements.footer.innerHTML = PRAYER_SCHEDULES.map(
            (p) => `
                <li class="flex justify-between">
                    <span class="text-green-200">${p.name}</span>
                    <span class="text-white">${p.time}</span>
                </li>
            `,
        ).join("");
    }
}

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
// PRAYER REFRESH BUTTON
// ============================================================
function initPrayerRefresh() {
    const refreshPrayerBtn = document.getElementById("refresh-prayer");
    if (refreshPrayerBtn) {
        refreshPrayerBtn.addEventListener("click", function () {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            setTimeout(() => {
                this.innerHTML =
                    '<i class="fas fa-sync-alt"></i> <span class="hidden sm:inline">Perbarui</span>';
                updatePrayerDisplay();
            }, 1500);
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

    // ===== PRAYER TIMES =====
    updatePrayerDisplay();
    setInterval(updatePrayerDisplay, 30000);

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

    // ===== PRAYER REFRESH =====
    initPrayerRefresh();

    // ===== DONATION HANDLER =====
    initDonationHandler();

    // ===== LAZY LOADING =====
    initLazyLoading();

    // ===== SHIMMER LOADING =====
    initShimmerLoading();

    // ===== CONSOLE WELCOME =====
    showConsoleWelcome();
});
