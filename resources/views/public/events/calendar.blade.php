@extends('layouts.app')

@section('title', 'Kalender Kegiatan')

@section('content')
    <section class="bg-white py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <span
                        class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Kalender</span>
                    <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Kalender <span
                            class="text-green-600">Kegiatan</span></h2>
                    <p class="mt-1 text-gray-500">Klik kegiatan pada kalender untuk melihat detail</p>
                </div>
                <a href="{{ route('public.events.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-green-600 px-5 py-2.5 text-sm font-semibold text-green-600 transition hover:bg-green-50 hover:shadow-lg">
                    <i class="fas fa-list" aria-hidden="true"></i>
                    Daftar Kegiatan
                </a>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-lg md:p-6">
                <div id="calendar"></div>
            </div>
        </div>
    </section>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth',
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    list: 'Daftar',
                },
                events: '{{ route('public.events.calendar-data') }}',
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
            });

            calendar.render();
        });
    </script>
@endsection
