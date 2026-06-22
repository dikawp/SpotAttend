@extends('layouts.app')
@section('title', 'Add New Department')

@section('content')
    <div class="container mx-auto py-8">
        {{-- Header Section --}}
        <div class="flex items-center mb-4">
            <a href="{{ route('departments.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="ml-4 text-2xl font-bold text-gray-800 dark:text-gray-100">
                Add New Department
            </h2>
        </div>

        {{-- Form Wrapper --}}
        <form action="{{ route('departments.store') }}" method="POST"
            class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 md:p-8">
            @csrf

            {{-- Department Details Section --}}
            <div class="space-y-4">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-gray-200 pb-3 border-b border-gray-200 dark:border-gray-700">
                    Department Details
                </h3>
                <div class="pt-2 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department
                            Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="e.g., Technology"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 p-2.5">
                    </div>
                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Describe the department's role..."
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 p-2.5">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label for="office_location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Office Location (Optional)</label>
                        <select name="office_location_id" id="office_location_id" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 p-2.5">
                            <option value="">-- None --</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('office_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Positions Section (Dynamic) --}}
            <div class="space-y-4 mt-8">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Positions</h3>
                    <button type="button" id="add-position-btn"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Add Position
                    </button>
                </div>

                <div id="positions-container" class="space-y-4 pt-2">
                    {{-- Position --}}
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('departments.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Save Department
                </button>
            </div>
        </form>
    </div>

    {{-- Template form position (hidden) --}}
    <template id="position-template">
        <div
            class="grid grid-cols-12 gap-4 p-4 border rounded-lg position-entry bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            <input type="hidden" data-name="positions[INDEX][id]" value="">
            <div class="col-span-12 md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position Name</label>
                <input type="text" data-name="positions[INDEX][name]" required placeholder="e.g., Backend Developer"
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 p-2.5">
            </div>
            <div class="col-span-12 md:col-span-7">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Job Description</label>
                <textarea data-name="positions[INDEX][job_description]" rows="1"
                    placeholder="Describe the position's responsibilities..."
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 p-2.5"></textarea>
            </div>
            <div class="col-span-12 md:col-span-1 flex items-end justify-end">
                <button type="button"
                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-100 dark:hover:bg-red-800/50 rounded-full remove-position-btn transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-position-btn');
            const container = document.getElementById('positions-container');
            const template = document.getElementById('position-template');
            let positionIndex = 0;

            function addPositionRow() {
                const newRow = template.content.cloneNode(true);
                const newIndex = positionIndex++;

                newRow.querySelector('[data-name="positions[INDEX][id]"]').name = `positions[${newIndex}][id]`;
                newRow.querySelector('[data-name="positions[INDEX][name]"]').name = `positions[${newIndex}][name]`;
                newRow.querySelector('[data-name="positions[INDEX][job_description]"]').name =
                    `positions[${newIndex}][job_description]`;

                container.appendChild(newRow);
            }

            addPositionRow();

            addBtn.addEventListener('click', addPositionRow);

            container.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-position-btn');
                if (removeBtn) {
                    removeBtn.closest('.position-entry').remove();
                }
            });
        });
    </script>
@endpush
