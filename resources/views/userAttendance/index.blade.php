@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map { height: 300px; width: 100%; border-radius: 0.5rem; z-index: 1; }
    </style>
@endpush

@section('content')
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        My Attendance
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Today's Attendance ({{ today()->format('d M Y') }})
        </h4>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>
        @endif

        {{-- Logika Tombol Check-in/Check-out --}}
        <div id="location-status" class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800" role="alert">
            Getting your location... Please allow location access.
        </div>

        <div id="map" class="mb-4"></div>

        @if (!$todayAttendance)
            {{-- Belum Check-in --}}
            <form action="{{ route('my.attendance.checkin') }}" method="POST">
                @csrf
                <button type="submit" id="btn-checkin" disabled
                    class="px-5 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    Check In Now
                </button>
            </form>
        @elseif($todayAttendance && is_null($todayAttendance->check_out))
            {{-- Sudah Check-in, tapi belum Check-out --}}
            <p class="mb-4">You checked in at: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}
            </p>

            @php
                $canCheckOut = true;
                $endTimeStr = $employee->schedule_end_time;
                if ($endTimeStr) {
                    $endTime = \Carbon\Carbon::parse($endTimeStr);
                    // Gunakan timezone aplikasi saat ini untuk perbandingan
                    $now = now(config('app.timezone'));
                    $endToday = $now->copy()->setTimeFromTimeString($endTimeStr);
                    
                    if ($now->isBefore($endToday)) {
                        $canCheckOut = false;
                    }
                }
            @endphp

            @if(!$canCheckOut)
                <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800" role="alert">
                    <span class="font-medium">Early Checkout Disabled!</span> You can only check out after your shift ends at {{ \Carbon\Carbon::parse($employee->schedule_end_time)->format('H:i') }}.
                </div>
            @endif

            <form action="{{ route('my.attendance.checkout') }}" method="POST">
                @csrf
                <button type="submit" id="btn-checkout" disabled
                    class="px-5 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if(!$canCheckOut) data-early="true" @endif>
                    Check Out Now
                </button>
            </form>
        @else
            {{-- Sudah Check-in dan Check-out --}}
            <p>Attendance for today is complete.</p>
            <p>Check In: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }} | Check Out:
                {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}</p>
        @endif
    </div>

    {{-- Riwayat Absensi Bulan Ini --}}
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Konfigurasi Lokasi Kantor
            @if($officeLocation)
                const officeLat = {{ $officeLocation->latitude }};
                const officeLng = {{ $officeLocation->longitude }};
                const maxDistance = {{ $officeLocation->radius }}; // Radius maksimal dalam meter
            @else
                // Fallback location if none is set
                const officeLat = -6.2088;
                const officeLng = 106.8456;
                const maxDistance = 100; // Radius maksimal dalam meter (misal: 100 meter)
            @endif

            const map = L.map('map').setView([officeLat, officeLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Tambahkan lingkaran untuk area kantor
            const officeCircle = L.circle([officeLat, officeLng], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.2,
                radius: maxDistance
            }).addTo(map);

            officeCircle.bindPopup("Office Area");

            const statusDiv = document.getElementById('location-status');
            const btnCheckin = document.getElementById('btn-checkin');
            const btnCheckout = document.getElementById('btn-checkout');

            if (!navigator.geolocation) {
                statusDiv.innerHTML = "Geolocation is not supported by your browser.";
                statusDiv.className = "p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800";
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    // Hitung jarak (distance) antara lokasi user dan kantor menggunakan metode bawaan Leaflet
                    const userLatLng = L.latLng(userLat, userLng);
                    const officeLatLng = L.latLng(officeLat, officeLng);
                    const distance = userLatLng.distanceTo(officeLatLng);

                    // Tambahkan marker untuk lokasi user
                    const userMarker = L.marker([userLat, userLng]).addTo(map);
                    userMarker.bindPopup("Your Location").openPopup();

                    // Sesuaikan view map agar muat lokasi user dan kantor
                    const bounds = L.latLngBounds([userLatLng, officeLatLng]);
                    map.fitBounds(bounds, { padding: [50, 50] });

                    if (distance <= maxDistance) {
                        statusDiv.innerHTML = "You are within the office area. You can now check in/out.";
                        statusDiv.className = "p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800";
                        
                        if (btnCheckin) btnCheckin.disabled = false;
                        if (btnCheckout) {
                            if (!btnCheckout.hasAttribute('data-early')) {
                                btnCheckout.disabled = false;
                            } else {
                                statusDiv.innerHTML += "<br><em>Note: Checkout button will be enabled after your shift ends.</em>";
                            }
                        }
                    } else {
                        statusDiv.innerHTML = "You are outside the office area. You must be within " + maxDistance + " meters to check in. (Distance: " + Math.round(distance) + "m)";
                        statusDiv.className = "p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800";
                    }
                },
                (error) => {
                    statusDiv.innerHTML = "Failed to get location: " + error.message;
                    statusDiv.className = "p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800";
                },
                {
                    enableHighAccuracy: true
                }
            );
        });
    </script>
@endpush
