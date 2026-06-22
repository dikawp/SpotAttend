@extends('layouts.app')
@section('title', 'Assign Office Location')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 42px;
            border-color: #d1d5db; /* gray-300 */
            border-radius: 0.375rem;
        }
        .dark .select2-container .select2-selection--multiple {
            background-color: #374151; /* gray-700 */
            border-color: #4b5563; /* gray-600 */
        }
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #1f2937; /* gray-800 */
            border-color: #4b5563; /* gray-600 */
            color: #f3f4f6; /* gray-100 */
        }
        .dark .select2-dropdown {
            background-color: #374151;
            border-color: #4b5563;
        }
        .dark .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #4b5563;
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb; /* blue-600 */
            color: white;
        }
        .dark .select2-search__field {
            background-color: #374151;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen py-6">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Assign Location: {{ $officeLocation->name }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select whether to assign by department or by specific employees.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assignment Type</label>
                <select id="assign_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="department">By Department</option>
                    <option value="employee">By Employee</option>
                </select>
            </div>

            <form action="{{ route('office-locations.storeAssign', $officeLocation->id) }}" method="POST" id="assignForm">
                @csrf
                <input type="hidden" name="assign_type_submit" id="assign_type_submit" value="department">

                <div id="department_section" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Departments</label>
                    <select name="department_ids[]" id="department_select" multiple="multiple" class="select2-multiple w-full">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $dept->office_location_id == $officeLocation->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">You can search and select multiple departments. Employees without specific location overrides will follow their department's location.</p>
                </div>

                <div id="employee_section" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Specific Employees</label>
                    <select name="employee_ids[]" id="employee_select" multiple="multiple" class="select2-multiple w-full">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $emp->office_location_id == $officeLocation->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} ({{ $emp->department ? $emp->department->name : 'No Dept' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">You can search and select multiple employees. Specific employee assignment overrides their department assignment.</p>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('office-locations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Save Assignments</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2-multiple').select2({
                placeholder: "Click here to select options...",
                allowClear: true,
                width: '100%'
            });

            // Toggle logic
            const assignTypeSelect = document.getElementById('assign_type');
            const departmentSection = document.getElementById('department_section');
            const employeeSection = document.getElementById('employee_section');
            const assignTypeSubmit = document.getElementById('assign_type_submit');

            assignTypeSelect.addEventListener('change', function() {
                assignTypeSubmit.value = this.value;
                if (this.value === 'department') {
                    departmentSection.classList.remove('hidden');
                    employeeSection.classList.add('hidden');
                } else {
                    departmentSection.classList.add('hidden');
                    employeeSection.classList.remove('hidden');
                }
            });
            
            // Clean up empty form fields before submit so we don't accidentally send both arrays if user toggles
            $('#assignForm').on('submit', function() {
                if ($('#assign_type_submit').val() === 'department') {
                    $('#employee_select').val(null);
                } else {
                    $('#department_select').val(null);
                }
            });
        });
    </script>
@endpush
