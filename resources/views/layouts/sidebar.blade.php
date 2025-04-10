<div x-data="{ open: false }">
    <!-- Toggle Button for Mobile -->
    <button @click="open = !open" class="lg:hidden p-2 text-gray-700 dark:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

<aside class="w-56 bg-white dark:bg-gray-800 h-screen fixed top-0 left-0 shadow-md">
    <div class="p-4 text-xl font-semibold text-gray-900 dark:text-white">
        {{ config('app.name', 'Laravel') }}
    </div>
    <nav class="mt-5">
        <ul class="space-y-2">
            <li>
                @if(auth()->check())
                    @switch(auth()->user()->role)
                        @case(\App\Models\User::ROLE_SUPER_ADMIN)
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                                Dashboard
                            </a>
                            @break
                        @case(\App\Models\User::ROLE_MANAGER)
                            <a href="{{ route('manager.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                                Dashboard
                            </a>
                            @break
                        @case(\App\Models\User::ROLE_OWNER)
                            <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                                Dashboard
                            </a>
                            @break
                        @default
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                                Dashboard
                            </a>
                    @endswitch
                @endif
            </li>
            
            <!-- Properties Dropdown -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Properties
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul x-show="open" class="pl-4 space-y-1">
                    <li>
                        <a href="{{ route('properties.index') }}" class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                            View Properties
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('properties.create') }}" class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                            Add Property
                        </a>
                    </li>
                </ul>
            </li>
              <!-- Properties Dropdown -->
              <li x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Payments
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul x-show="open" class="pl-4 space-y-1">
                    <li>
                        <a href="{{ route('payments.index') }}" class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                            View Payments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payments.create1') }}" class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                            Add Payment
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Tenants Dropdown -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Tenants
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul x-show="open" class="pl-4 space-y-1">
                    <li>
                        <a href="{{ route('tenants.index') }}" class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                            View Tenants
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenants.create') }}" class="block px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                            Add Tenants
                        </a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="{{ route('leases.index') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Leases
                </a>
            </li>
            <li>
                <a href="{{ route('units.index') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Units
                </a>
            </li>
        </ul>
    </nav>
</aside>
