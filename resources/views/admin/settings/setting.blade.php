@extends('layouts.pages')

@push('head')
    @include('layouts.parts.title', ['title' => __('Settings')])
@endpush

@section('content')
    <!-- SIDEBAR  -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow overflow-y-auto z-40 transform transition-transform duration-200 lg:translate-x-0 -translate-x-full touch-pan-y">

        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2 truncate">
                <i class="mdi mdi-cog-outline text-blue-600 dark:text-blue-400 text-lg"></i>
                {{ __('Settings') }}
            </h2>
            <button id="close-sidebar"
                class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 p-2 min-h-[44px] min-w-[44px] flex items-center justify-center">
                <i class="mdi mdi-close text-xl"></i>
            </button>
        </div>

        @php
            $routes = [
                'admin.settings.general' => ['icon' => 'mdi-cog-outline', 'label' => __('General')],
                'admin.settings.session' => ['icon' => 'mdi-clock-outline', 'label' => __('Session')],
                'admin.settings.email' => ['icon' => 'mdi-email-outline', 'label' => __('Email')],
                'admin.settings.routes' => ['icon' => 'mdi-routes', 'label' => __('Routes')],
                'admin.settings.redis' => ['icon' => 'mdi-database', 'label' => __('Redis')],
                'admin.settings.cache' => ['icon' => 'mdi-cached', 'label' => __('Cache')],
                'admin.settings.horizon' => ['icon' => 'mdi-cached', 'label' => __('Horizon')],
                'admin.settings.queues' => ['icon' => 'mdi-playlist-play', 'label' => __('Queues')],
                'admin.settings.scout' => ['icon' => 'mdi-search-web', 'label' => __('Scout')],
                'admin.settings.filesystem' => ['icon' => 'mdi-folder-outline', 'label' => __('Filesystem')],
                'admin.settings.rate_limit' => ['icon' => 'mdi-traffic-light', 'label' => __('Rate Limit')],
                'admin.settings.security' => ['icon' => 'mdi-shield-outline', 'label' => __('Security')],
            ];
        @endphp

        <ul class="py-1 overflow-y-auto">
            @foreach ($routes as $route => $data)
                <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <a href="{{ route($route) }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-colors min-h-[44px] touch-manipulation
                            {{ request()->routeIs($route) ? 'bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 font-semibold' : '' }}">
                        <i
                            class="mdi {{ $data['icon'] }} text-base shrink-0 {{ request()->routeIs($route) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        <span class="flex-1 truncate">{{ $data['label'] }}</span>
                        @if (request()->routeIs($route))
                            <i class="mdi mdi-check-circle text-green-600 dark:text-green-400 text-sm shrink-0"></i>
                        @else
                            <i class="mdi mdi-chevron-right text-gray-400 dark:text-gray-500 text-sm shrink-0"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64">

        <!-- HEADER -->
        <nav class="bg-white dark:bg-gray-800 py-3 shadow flex justify-between items-center px-4 sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <button id="open-sidebar"
                    class="lg:hidden p-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 min-h-[44px] min-w-[44px] flex items-center justify-center">
                    <i class="mdi mdi-menu text-xl"></i>
                </button>
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center space-x-2 font-medium cursor-pointer text-sm min-h-[44px]">
                    <i class="mdi mdi-arrow-left shrink-0"></i>
                    <span class="xs:inline truncate">{{ __('Back to Dashboard') }}</span>
                </a>
            </div>
            <div class="min-h-[44px] flex items-center gap-4">
                <x-theme />
                <x-profile />
            </div>
        </nav>

        <!-- SETTINGS CONTENT -->
        <main class="flex-1 p-3 sm:p-4 md:p-6 space-y-6  ">

            <!-- FORM SETTINGS -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                        <i class="mdi mdi-pencil-outline mr-2 text-blue-600 dark:text-blue-400 shrink-0"></i>
                        @yield('settings_title', __('Edit Settings'))
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
                        @yield('settings_description', __('Modify your application configuration'))
                    </p>
                </div>

                <form action="{{ route('admin.settings.update') }}" method="post" autocomplete="off"
                    enctype="multipart/form-data" class="p-3 sm:p-4 md:p-6">
                    <!-- These hidden inputs act as decoys to prevent the browser from autofilling real username and password fields. -->
                    <input id="name" type="text" class="hidden" />
                    <input id="password" type="password" class="hidden" />
                    <input type="hidden" name="current_route" value="{{ request()->route()->getName() }}">
                    <!-- Please do not remove-->

                    @method('put')
                    @csrf


                    <div>
                        @yield('form')
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-end">
                        <button type="submit"
                            class="flex items-center px-4 sm:px-6 py-2.5 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-800 active:bg-blue-800 dark:active:bg-blue-900 transition-colors text-sm sm:text-base min-h-[44px] touch-manipulation">
                            <i class="mdi mdi-content-save-all mr-2 text-sm shrink-0"></i>
                            <span>{{ __('Save Changes') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- CACHE SECTION -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center truncate">
                        <i class="mdi mdi-cached mr-2 text-blue-600 dark:text-blue-400 shrink-0"></i>
                        {{ __('Cache Management') }}
                    </h2>
                </div>

                <form action="{{ route('admin.settings.reload') }}" method="post" autocomplete="off" class="p-4 sm:p-6">
                    @method('put')
                    @csrf
                    <input type="hidden" name="current_route" value="{{ url()->current() }}">

                    <div class="flex items-start">
                        <div class="shrink-0 mt-1 mr-3 text-blue-600 dark:text-blue-400">
                            <i class="mdi mdi-information-outline text-lg shrink-0"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            {{ __('This will clear and regenerate the cache for all application settings.') }}
                        </p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="flex items-center px-4 sm:px-6 py-2.5 bg-green-600 dark:bg-green-700 text-white rounded-lg hover:bg-green-700 dark:hover:bg-green-800 active:bg-green-800 dark:active:bg-green-900 transition-colors text-sm sm:text-base min-h-[44px] touch-manipulation">
                            <i class="mdi mdi-cached mr-2 text-sm shrink-0"></i>
                            <span>{{ __('Rebuild Settings Cache') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('open-sidebar');
            const closeBtn = document.getElementById('close-sidebar');

            openBtn?.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                document.body.classList.add('overflow-hidden');
            });

            closeBtn?.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                document.body.classList.remove('overflow-hidden');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 1024 && sidebar && openBtn) {
                    if (!sidebar.contains(e.target) && !openBtn.contains(e.target)) {
                        sidebar.classList.add('-translate-x-full');
                        document.body.classList.remove('overflow-hidden');
                    }
                }
            });

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
        /* XS Breakpoint optimizations using Tailwind classes via @media */
        @media (max-width: 475px) {
            .xs\:inline {
                display: inline !important;
            }
        }

        /* Mobile sidebar improvements */
        @media (max-width: 1024px) {
            #sidebar {
                max-width: 90vw;
            }
        }

        /* Performance optimizations that need CSS */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
@endpush
