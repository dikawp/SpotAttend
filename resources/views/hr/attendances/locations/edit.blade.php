@extends('layouts.app')
@section('title', 'Edit Office Location')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map { height: 400px; width: 100%; border-radius: 0.5rem; z-index: 1; margin-bottom: 1rem; }
    </style>
@endpush

@section('content')
    <div class="min-h-screen py-6">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Office Location
            </h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form action="{{ route('office-locations.update', $officeLocation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" value="{{ old('name', $officeLocation->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pick Location on Map</label>
                    <div id="map"></div>
                </div>

                <div class="mb-4 flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                        <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $officeLocation->latitude) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-600 dark:border-gray-600 dark:text-white cursor-not-allowed" required readonly>
                        @error('latitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                        <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $officeLocation->longitude) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-600 dark:border-gray-600 dark:text-white cursor-not-allowed" required readonly>
                        @error('longitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Radius (meters)</label>
                    <input type="number" id="radius" name="radius" value="{{ old('radius', $officeLocation->radius) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required min="1">
                    @error('radius') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default', $officeLocation->is_default) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Set as default location (will apply to employees without specific location/department rules)</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('office-locations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initial location
            const initialLat = {{ old('latitude', $officeLocation->latitude) }};
            const initialLng = {{ old('longitude', $officeLocation->longitude) }};
            const initialRadius = parseInt({{ old('radius', $officeLocation->radius) }}) || 100;

            const map = L.map('map').setView([initialLat, initialLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker;
            let circle;

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius');

            function updateMap(lat, lng, radius) {
                if (marker) {
                    map.removeLayer(marker);
                }
                if (circle) {
                    map.removeLayer(circle);
                }

                marker = L.marker([lat, lng]).addTo(map);
                circle = L.circle([lat, lng], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(map);

                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
            }

            // Draw initial map state
            updateMap(initialLat, initialLng, initialRadius);

            // Click on map to set location
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                const radius = parseInt(radiusInput.value) || 100;
                
                updateMap(lat, lng, radius);
            });

            // Update circle when radius changes
            radiusInput.addEventListener('input', function() {
                if (marker) {
                    const lat = marker.getLatLng().lat;
                    const lng = marker.getLatLng().lng;
                    const radius = parseInt(this.value) || 100;
                    updateMap(lat, lng, radius);
                }
            });
        });
    </script>
@endpush
