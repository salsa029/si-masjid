<!DOCTYPE html>
<html lang="id" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', config('app.name')) - Masjid An-Nur</title>
    <meta name="description" content="@yield('meta_description', 'Masjid An-Nur, pusat ibadah dan peradaban umat Islam.')" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Preconnect untuk performa -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Vite Asset -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        /* ================================================================
           RESET & BASE
           ================================================================ */
        * {
            font-family: "Poppins", sans-serif;
            scroll-behavior: smooth;
        }

        /* ================================================================
           PRAYER CARD
           ================================================================ */
        .prayer-card {
            transition: all 0.3s ease;
            cursor: default;
        }

        .prayer-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(4, 120, 87, 0.2);
        }

        .prayer-card.active-prayer {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 2px solid #fcd34d;
            box-shadow: 0 0 30px rgba(252, 211, 77, 0.15);
        }

        /* ================================================================
           CARD HOVER
           ================================================================ */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .card-hover .card-image {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover .card-image {
            transform: scale(1.05);
        }

        /* ================================================================
           GALLERY OVERLAY
           ================================================================ */
        .gallery-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-content {
            transform: translateY(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover .gallery-content {
            transform: translateY(0);
        }

        /* ================================================================
           LINE CLAMP
           ================================================================ */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ================================================================
           BACK TO TOP
           ================================================================ */
        #back-to-top {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.9);
        }

        #back-to-top.visible {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) scale(1);
        }

        /* ================================================================
           FLOATING PRAYER BUTTON
           ================================================================ */
        #floating-prayer-button button {
            min-width: 160px;
            box-shadow: 0 8px 32px rgba(4, 120, 87, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #floating-prayer-button button:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 40px rgba(4, 120, 87, 0.5);
        }

        /* ================================================================
           RESPONSIVE
           ================================================================ */
        @media (max-width: 640px) {
            .logo-text {
                font-size: 1.1rem;
            }

            .hero-title {
                font-size: 2rem !important;
            }

            #floating-prayer-button button {
                min-width: 120px;
                padding: 10px 16px;
            }

            .prayer-name {
                font-size: 9px;
            }

            .prayer-time {
                font-size: 1.1rem;
            }
        }

        /* ================================================================
           TOAST
           ================================================================ */
        [x-cloak] {
            display: none !important;
        }

        .toast-enter {
            transform: translateX(100%);
        }

        .toast-enter-active {
            transform: translateX(0);
            transition: transform 0.3s ease;
        }

        .toast-exit {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- ================================================================
    TOAST NOTIFICATION
    ================================================================ -->
    <x-toast />

    <!-- ================================================================
    NAVBAR
    ================================================================ -->
    @include('layouts.partials.navbar')

    <!-- ================================================================
    MAIN CONTENT
    ================================================================ -->
    <main class="pt-16 md:pt-20">
        @yield('content')
    </main>

    <!-- ================================================================
    FLOATING PRAYER BUTTON
    ================================================================ -->
    @include('layouts.partials.floating-prayer')

    <!-- ================================================================
    BACK TO TOP BUTTON
    ================================================================ -->
    @include('layouts.partials.back-to-top')

    <!-- ================================================================
    FOOTER
    ================================================================ -->
    @include('layouts.partials.footer')

    <!-- ================================================================
    SCRIPTS
    ================================================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            'use strict';

            // ============================================================
            // NAVBAR SCROLL EFFECT
            // ============================================================
            const navbar = document.getElementById('navbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    navbar.classList.toggle('scrolled', window.scrollY > 50);
                });
            }

            // ============================================================
            // BACK TO TOP
            // ============================================================
            const backToTopBtn = document.getElementById('back-to-top');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    backToTopBtn.classList.toggle('visible', window.scrollY > 400);
                });

                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // ============================================================
            // SMOOTH SCROLL FOR NAV LINKS
            // ============================================================
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return;
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.getBoundingClientRect().top + window.pageYOffset -
                            80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // ============================================================
            // ACTIVE NAV LINK
            // ============================================================
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link, .mobile-link');

            if (sections.length && navLinks.length) {
                function updateActiveLink() {
                    let current = '';
                    const scrollPosition = window.pageYOffset + 120;
                    sections.forEach(function(section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                            current = section.getAttribute('id');
                        }
                    });
                    navLinks.forEach(function(link) {
                        link.classList.remove('active');
                        const href = link.getAttribute('href');
                        if (href && href === '#' + current) {
                            link.classList.add('active');
                        }
                    });
                }
                window.addEventListener('scroll', updateActiveLink);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
