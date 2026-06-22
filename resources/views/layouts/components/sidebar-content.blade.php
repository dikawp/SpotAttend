<nav class="py-4 text-gray-500 dark:text-gray-400">
    {{-- Logo --}}
    <a href="" class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center">
        <svg class="w-8 h-8 me-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                d="M4 4h4v4H4zM4 10h4v4H4zM4 16h4v4H4zM10 4h4v4h-4zM10 10h4v4h-4zM10 16h4v4h-4zM16 4h4v4h-4zM16 10h4v4h-4zM16 16h4v4h-4z" />
        </svg>
        <span>HRIS GABUT</span>
    </a>

    @php
        $dashboardRouteName = auth()->user()->role === 1 ? 'hr.dashboard' : 'dashboard';
        $dashboardRouteUrl = route($dashboardRouteName);
    @endphp

    <ul class="mt-4 ms-1 space-y-1">
        {{-- Dashboard --}}
        <li class="relative px-6 py-3">
            <a href="{{ $dashboardRouteUrl }}" @class([
                'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                'text-blue-600 dark:text-blue-300' => request()->routeIs(
                    $dashboardRouteName),
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                    <rect width="7" height="9" x="3" y="3" rx="1" />
                    <rect width="7" height="5" x="14" y="3" rx="1" />
                    <rect width="7" height="9" x="14" y="12" rx="1" />
                    <rect width="7" height="5" x="3" y="16" rx="1" />
                </svg>
                <span class="ml-4">Dashboard</span>
            </a>
        </li>

        {{-- Admin Menu --}}
        @if (auth()->user()->role === 1)
            @php
                $isHrMenuActive = request()->routeIs(
                    'employees.*',
                    'departments.*',
                    'holidays.*',
                    'attendances.*',
                    'office-locations.*',
                    'leaves.*',
                );
                $isAttendanceMenuActive = request()->routeIs('attendances.*', 'office-locations.*');
            @endphp

            <li class="relative px-6 py-3" x-data="{ isOpen: @json($isHrMenuActive) }">
                <button @click="isOpen = !isOpen" @class([
                    'inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                    'text-blue-600 dark:text-blue-300' => $isHrMenuActive,
                ])>
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M10 15H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <circle cx="18" cy="15" r="3" />
                            <path d="m19.5 12.5.5-1m-3.5 6 .5 1m2 0h1m-7-6h1m.5-2.5-.5-1m.5 6.5-.5 1" />
                        </svg>
                        <span class="ml-4">HR Management</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': isOpen }"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Submenu HR Management --}}
                <ul x-show="isOpen" x-collapse class="mt-2 space-y-2 text-sm font-medium text-gray-500 rounded-md">
                    @foreach ([['route' => 'departments.index', 'label' => 'Departments'], ['route' => 'employees.index', 'label' => 'Employee Lists'], ['route' => 'holidays.index', 'label' => 'Holidays'], ['route' => 'leaves.index', 'label' => 'Leaves']] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" @class([
                                'flex items-center gap-2 py-2 rounded-md transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                                'text-blue-600 bg-blue-100 dark:text-blue-200 dark:bg-blue-900' => request()->routeIs(
                                    Str::before($item['route'], '.') . '.*'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-dot-icon lucide-dot">
                                    <circle cx="12.1" cy="12.1" r="1" />
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach

                    {{-- Attendance Nested Dropdown --}}
                    <li class="py-1" x-data="{ isOpen: @json($isAttendanceMenuActive) }">
                        <button @click="isOpen = !isOpen" @class([
                            'inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                            'text-blue-600 dark:text-blue-300' => $isAttendanceMenuActive,
                        ])>
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-dot-icon lucide-dot">
                                    <circle cx="12.1" cy="12.1" r="1" />
                                </svg>
                                Attendances
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': isOpen }"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Attendance submenu --}}
                        <ul x-show="isOpen" x-collapse class="ms-5 space-y-2">
                            @foreach ([['route' => 'attendances.monitor', 'label' => 'Monitoring'], ['route' => 'attendances.index', 'label' => 'Attendance (HR)'], ['route' => 'office-locations.index', 'label' => 'Locations']] as $item)
                                <li>
                                    <a href="{{ route($item['route']) }}" @class([
                                        'flex items-center gap-2 px-3 py-2 rounded-md transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                                        'text-blue-600 bg-blue-100 dark:text-blue-200 dark:bg-blue-900' => request()->routeIs(
                                            $item['route']),
                                    ])>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-dot-icon lucide-dot">
                                            <circle cx="12.1" cy="12.1" r="1" />
                                        </svg>
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </li>
        @endif

        {{-- User Menu --}}
        @if (auth()->user()->role === 0)
            <li class="relative px-6 py-3">
                <a href="{{ route('my.attendance.index') }}" @class([
                    'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                    'text-blue-600 dark:text-blue-300' => request()->routeIs('my.attendance.*'),
                ])>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-square-check-big-icon lucide-square-check-big">
                        <path d="M21 10.656V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12.344" />
                        <path d="m9 11 3 3L22 4" />
                    </svg>
                    <span class="ml-4">My Attendance</span>
                </a>
            </li>
            <li class="relative px-6 py-3">
                <a href="{{ route('my-leaves.index') }}" @class([
                    'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200',
                    'text-blue-600 dark:text-blue-300' => request()->routeIs('my-leaves.*'),
                ])>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clipboard-clock-icon lucide-clipboard-clock">
                        <path d="M16 14v2.2l1.6 1" />
                        <path d="M16 4h2a2 2 0 0 1 2 2v.832" />
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2" />
                        <circle cx="16" cy="16" r="6" />
                        <rect x="8" y="2" width="8" height="4" rx="1" />
                    </svg>
                    <span class="ml-4">My Leave</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
