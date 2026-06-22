@extends('layouts.app')
@section('title', 'Office Locations')

@section('content')
    <div class="min-h-screen py-6">
        <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Office Locations
            </h1>
            <a href="{{ route('office-locations.create') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Add New Location
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-4 py-3 lg:hidden"><span class="sr-only">Expand</span></th>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="hidden px-6 py-3 lg:table-cell">Coordinates</th>
                            <th scope="col" class="hidden px-6 py-3 lg:table-cell">Radius (m)</th>
                            <th scope="col" class="hidden px-6 py-3 lg:table-cell text-center">Assigned Employees</th>
                            <th scope="col" class="hidden px-6 py-3 text-center lg:table-cell">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($locations as $loc)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600/50">
                                <td class="px-4 py-4 lg:hidden">
                                    <button class="details-toggle" data-target="#details-{{ $loc->id }}">
                                        <svg class="w-5 h-5 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        <svg class="w-5 h-5 collapse-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $loc->name }}
                                    @if($loc->is_default)
                                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Default</span>
                                    @endif
                                </td>
                                <td class="hidden px-6 py-4 lg:table-cell">
                                    {{ $loc->latitude }}, {{ $loc->longitude }}
                                </td>
                                <td class="hidden px-6 py-4 lg:table-cell">
                                    {{ $loc->radius }}
                                </td>
                                <td class="hidden px-6 py-4 text-center lg:table-cell">
                                    {{ $loc->total_assigned_employees }}
                                </td>
                                <td class="hidden px-6 py-4 lg:table-cell">
                                    <div class="flex justify-center items-center space-x-4">
                                        <a href="{{ route('office-locations.assign', $loc->id) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Assign</a>
                                        <a href="{{ route('office-locations.edit', $loc->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                        <form action="{{ route('office-locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <tr id="details-{{ $loc->id }}" class="hidden border-b dark:border-gray-700 lg:hidden">
                                <td colspan="3" class="p-4 bg-gray-50 dark:bg-gray-800/50">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <h4 class="font-bold text-xs uppercase text-gray-500 dark:text-gray-400">Coordinates</h4>
                                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $loc->latitude }}, {{ $loc->longitude }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs uppercase text-gray-500 dark:text-gray-400">Radius (m)</h4>
                                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $loc->radius }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs uppercase text-gray-500 dark:text-gray-400">Assigned Employees</h4>
                                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $loc->total_assigned_employees }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs uppercase text-gray-500 dark:text-gray-400">Actions</h4>
                                            <div class="flex items-center space-x-4 mt-1">
                                                <a href="{{ route('office-locations.assign', $loc->id) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Assign</a>
                                                <a href="{{ route('office-locations.edit', $loc->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                                <form action="{{ route('office-locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    No locations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                const toggleButton = e.target.closest('.details-toggle');
                if (toggleButton) {
                    const targetId = toggleButton.dataset.target;
                    const detailsRow = document.querySelector(targetId);
                    const expandIcon = toggleButton.querySelector('.expand-icon');
                    const collapseIcon = toggleButton.querySelector('.collapse-icon');

                    if (detailsRow) {
                        detailsRow.classList.toggle('hidden');
                        expandIcon.classList.toggle('hidden');
                        collapseIcon.classList.toggle('hidden');
                    }
                }
            });
        });
    </script>
@endpush
