@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <div class="my-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('employees.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Add New Employee</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fill in the details to create a new employee
                    account</p>
            </div>
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-2">
                        Please correct the following errors:
                    </h3>
                    <ul class="space-y-1 text-sm text-red-700 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <span class="text-red-500 dark:text-red-400">•</span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Card with Tabs --}}
    <div
        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
        <form id="employeeForm" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200 dark:border-gray-700 px-4 sm:px-8">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs" id="tab-navigation">
                    {{-- Tab 1: Login Information (Default Active) --}}
                    <a href="#" data-tab="login"
                        class="tab-item py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                            border-blue-500 text-blue-600 dark:text-blue-400 dark:border-blue-400"
                        aria-current="page">
                        Login
                    </a>

                    {{-- Tab 2: Personal Information --}}
                    <a href="#" data-tab="personal"
                        class="tab-item py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                            dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600">
                        Personal
                    </a>

                    {{-- Tab 3: Employment Information --}}
                    <a href="#" data-tab="employment"
                        class="tab-item py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                            dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600">
                        Employment 
                    </a>
                </nav>
            </div>

            {{-- Tab Content Container --}}
            <div id="tab-content-container">

                {{-- SECTION 1: Login Information --}}
                <div class="tab-content p-8" id="tab-login">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Login Information</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Account credentials for system access</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                placeholder="john.doe"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        @if(auth()->user()->role == 2)
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                System Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                                <option value="0" {{ old('role') == '0' ? 'selected' : '' }}>Employee (Role 0)</option>
                                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>HR Admin (Role 1)</option>
                            </select>
                        </div>
                        @endif

                        <div class="md:col-span-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                placeholder="Enter a strong password"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Personal Information --}}
                <div class="tab-content p-8 hidden" id="tab-personal">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Personal Information</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Basic personal details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="full_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}"
                                required placeholder="John Doe"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                NIK (ID Number) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required
                                placeholder="3578xxxxxxxxxxxx"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bank Account
                            </label>
                            <input type="text" id="bank" name="bank" value="{{ old('bank') }}"
                                placeholder="9018xxxxxxxxx"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="phone_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" id="phone_number" name="phone_number"
                                value="{{ old('phone_number') }}" placeholder="081234567890"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="emergency"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Emergency Contact
                            </label>
                            <input type="text" id="emergency" name="emergency" value="{{ old('emergency') }}"
                                placeholder="081234567890"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="place_of_birth"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Place of Birth
                            </label>
                            <input type="text" id="place_of_birth" name="place_of_birth"
                                value="{{ old('place_of_birth') }}" placeholder="Surabaya"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="date_of_birth"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="marital_status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Marital Status
                            </label>
                            <select id="marital_status" name="marital_status"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                                <option value="" disabled selected>Select Status</option>
                                @foreach (['Single', 'Married', 'Divorced', 'Widowed'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('marital_status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3" placeholder="Enter full address"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200 resize-none">{{ old('address') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profile Photo
                            </label>
                            <div class="flex items-start gap-6">
                                <div class="flex-shrink-0">
                                    <div id="photoPreviewContainer"
                                        class="w-24 h-24 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-900 overflow-hidden">
                                        <svg id="photoPlaceholder" class="w-8 h-8 text-gray-400 dark:text-gray-500"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <img id="photoPreview" class="w-full h-full object-cover hidden" alt="Preview">
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="photo" name="photo" accept="image/*"
                                        class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400 dark:hover:file:bg-blue-900/50 file:cursor-pointer cursor-pointer bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg transition-colors duration-200">
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 2MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Employment Information --}}
                <div class="tab-content p-8 hidden" id="tab-employment">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Employment Information</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Job position and department details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="department_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select id="department_id" name="department_id" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="position_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Position <span class="text-red-500">*</span>
                            </label>
                            <select id="position_id" name="position_id" required disabled
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                <option value="" disabled selected>Select Department First</option>
                            </select>
                        </div>

                        <div>
                            <label for="office_location_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Office Location (Optional - Overrides Department)
                            </label>
                            <select id="office_location_id" name="office_location_id"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                                <option value="">-- Use Department Default --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}"
                                        {{ old('office_location_id') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="hire_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Hire Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="hire_date" name="hire_date"
                                value="{{ old('hire_date', now()->toDateString()) }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="schedule_start_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Schedule Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="schedule_start_time" name="schedule_start_time"
                                value="{{ old('schedule_start_time', '08:00') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="schedule_end_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Schedule End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="schedule_end_time" name="schedule_end_time"
                                value="{{ old('schedule_end_time', '16:00') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>

                        <div>
                            <label for="contract_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contract Type <span class="text-red-500">*</span>
                            </label>
                            <select id="contract_type" name="contract_type" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                                <option value="0" {{ old('contract_type', 0) == 0 ? 'selected' : '' }}>Full-time
                                </option>
                                <option value="1" {{ old('contract_type') == 1 ? 'selected' : '' }}>Contract</option>
                                <option value="2" {{ old('contract_type') == 2 ? 'selected' : '' }}>Intern</option>

                            </select>
                        </div>

                        <div>
                            <label for="annual_leave_days"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Annual Leave Days <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="annual_leave_days" name="annual_leave_days"
                                value="{{ old('annual_leave_days', 12) }}" required min="0"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-shadow duration-200">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('employees.index') }}"
                        class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg shadow-sm hover:shadow-md focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-2D00">
                        <span class="flex items-center gap-2">
                            Save Employee
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Tab Functionality ---
            const tabItems = document.querySelectorAll('.tab-item');
            const tabContents = document.querySelectorAll('.tab-content');
            const activeClass = 'border-blue-500 text-blue-600 dark:text-blue-400 dark:border-blue-400';
            const inactiveClass =
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600';

            const showTab = (tabId) => {
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                const activeContent = document.getElementById(`tab-${tabId}`);
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }

                tabItems.forEach(item => {
                    item.classList.remove(...activeClass.split(' '));
                    item.classList.add(...inactiveClass.split(' '));
                });

                const activeTab = document.querySelector(`.tab-item[data-tab="${tabId}"]`);
                if (activeTab) {
                    activeTab.classList.remove(...inactiveClass.split(' '));
                    activeTab.classList.add(...activeClass.split(' '));
                }
            };

            tabItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const tabId = item.getAttribute('data-tab');
                    showTab(tabId);
                });
            });

            // --- Error Handling: Show tab with the first error ---
            let initialTab = 'login'; // Default tab
            const firstError = document.querySelector('.bg-red-50'); // Check for error alert

            if (firstError) {
                // Find the first input field with an error (assuming validation adds a class or is near error text)
                // This is a simple check, a more robust way would be to check for specific error messages near inputs
                const errorInput = document.querySelector('[name="{{ $errors->keys()[0] ?? '' }}"]');
                if (errorInput) {
                    const errorTab = errorInput.closest('.tab-content');
                    if (errorTab) {
                        initialTab = errorTab.id.replace('tab-', '');
                    }
                }
            }
            showTab(initialTab);


            // --- Existing Department/Position & Photo Preview Functionality ---
            const departmentSelect = document.getElementById('department_id');
            const positionSelect = document.getElementById('position_id');
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photoPreview');
            const photoPlaceholder = document.getElementById('photoPlaceholder');

            departmentSelect.addEventListener('change', async () => {
                const departmentId = departmentSelect.value;

                if (!departmentId) {
                    positionSelect.disabled = true;
                    positionSelect.innerHTML =
                        '<option value="" disabled selected>Select Department First</option>';
                    return;
                }

                positionSelect.disabled = true;
                positionSelect.innerHTML =
                    '<option value="" disabled selected>Loading positions...</option>';

                try {
                    // Assuming this route is defined in your Laravel application
                    const response = await fetch(`/departments/${departmentId}/positions`);
                    if (!response.ok) throw new Error('Failed to fetch positions');
                    const positions = await response.json();

                    // Keep the currently selected position if it exists after old() fails, otherwise default to "Select Position"
                    const oldPositionId = "{{ old('position_id') }}";
                    let optionsHtml = '<option value="" disabled selected>Select Position</option>';

                    positions.forEach(position => {
                        const isSelected = oldPositionId == position.id ? 'selected' : '';
                        optionsHtml +=
                            `<option value="${position.id}" ${isSelected}>${position.name}</option>`;
                    });

                    positionSelect.innerHTML = optionsHtml;
                    positionSelect.disabled = false;

                    // Manually trigger change if an old value was present to ensure correct state after re-populating
                    if (oldPositionId && positions.some(p => p.id == oldPositionId)) {
                        positionSelect.value = oldPositionId;
                    }

                } catch (error) {
                    console.error('Error loading positions:', error);
                    positionSelect.innerHTML =
                        '<option value="" disabled selected>Failed to load positions</option>';
                }
            });

            // Re-trigger on page load if old() data is present for department
            if (departmentSelect.value) {
                departmentSelect.dispatchEvent(new Event('change'));
            }


            // --- Photo Preview Logic ---
            photoInput.addEventListener('change', (e) => {
                const file = e.target.files[0];

                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        photoInput.value = '';
                        // Reset preview
                        photoPreview.src = '';
                        photoPreview.classList.add('hidden');
                        photoPlaceholder.classList.remove('hidden');
                        return;
                    }

                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file');
                        photoInput.value = '';
                        // Reset preview
                        photoPreview.src = '';
                        photoPreview.classList.add('hidden');
                        photoPlaceholder.classList.remove('hidden');
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = (event) => {
                        photoPreview.src = event.target.result;
                        photoPreview.classList.remove('hidden');
                        photoPlaceholder.classList.add('hidden');
                    };

                    reader.readAsDataURL(file);
                } else {
                    photoPreview.src = '';
                    photoPreview.classList.add('hidden');
                    photoPlaceholder.classList.remove('hidden');
                }
            });
        });
    </script>
@endpush
