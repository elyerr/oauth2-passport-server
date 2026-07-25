@extends('admin.settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-100 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">

        <!-- Sidebar Section -->
        <div class="w-full lg:w-1/4 sticky top-4 space-y-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-5 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-lock-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Session Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Configure session and cookie parameters for your application') }}
                </p>
            </div>

            <div
                class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="mdi mdi-security mr-2 text-blue-600 dark:text-blue-400"></i>
                    {{ __('Security Recommendations') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check-outline text-green-600 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Enable HTTPS only in production') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check-outline text-green-600 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Use HTTP only cookies for security') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check-outline text-green-600 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Set appropriate session lifetime') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields Section -->
        <div class="w-full lg:w-3/4 space-y-6">

            <!-- Session Management -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="mdi mdi-timer-outline mr-2 text-blue-600 dark:text-blue-400"></i>
                    {{ __('Session Management') }}
                </h3>
                <div class="grid grid-cols-1  gap-6">

                    {{-- Session Lifetime --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <i class="mdi mdi-timer-sand-empty text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('Session Lifetime') }}</label>
                        </div>
                        <div class="relative">
                            <input type="number" name="session[lifetime]" min="1"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                                value="{{ config('session.lifetime') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">min</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Specify minutes until session expires due to inactivity') }}
                        </small>
                    </div>

                    {{-- Paswword confirmation --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <i class="mdi mdi-timer-sand-empty text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('Password Confirmation Timeout') }}</label>
                        </div>
                        <div>
                            <input type="number" name="auth[password_timeout]" min="1"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                                value="{{ config('auth.password_timeout') }}">

                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Here you may define the amount of seconds before a password confirmation times out and the user is prompted to re-enter their password via the confirmation screen. By default, the timeout lasts for three hours.') }}
                        </small>
                    </div>

                    {{-- Expire on Close --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <i class="mdi mdi-window-close text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('Expire on Close') }}</label>
                        </div>
                        <select name="session[expire_on_close]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300">
                            <option value="true" {{ config('session.expire_on_close') ? 'selected' : '' }}>
                                {{ __('Yes') }}</option>
                            <option value="false" {{ !config('session.expire_on_close') ? 'selected' : '' }}>
                                {{ __('No') }}</option>
                        </select>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Immediately expire session when browser closes') }}
                        </small>
                    </div>


                    {{-- Session Encryption --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <i class="mdi mdi-encryption text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <label
                                class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('Session Encryption') }}</label>
                        </div>
                        <select name="session[encrypt]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300">
                            <option value="true" {{ config('session.encrypt') ? 'selected' : '' }}>{{ __('Yes') }}
                            </option>
                            <option value="false" {{ !config('session.encrypt') ? 'selected' : '' }}>{{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Encrypt all session data before storage') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Cookie Settings -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="mdi mdi-cookie mr-2 text-blue-600 dark:text-blue-400"></i>
                    {{ __('Cookie Settings') }}
                </h3>
                <div class="grid grid-cols-1 gap-6">

                    {{-- Cookie Names --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <i class="mdi mdi-rename-box text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white">{{ __('Cookie Names') }}</h4>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('Session Cookie Name') }}</label>
                                <input type="text" name="session[cookie]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                                    value="{{ config('session.cookie') }}">
                                <small
                                    class="block mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Cookie name used to identify session instances') }}</small>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('CSRF Token Cookie Name') }}</label>
                                <input type="text" name="session[xcsrf-token]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                                    value="{{ config('session.xcsrf-token') }}">
                                <small
                                    class="block mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Cookie name used for CSRF protection tokens') }}</small>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('Laravel Passport Cookie Name') }}</label>
                                <input type="text" name="system[cookie_name]"
                                    placeholder="{{ __('Enter passport cookie name') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                                    value="{{ config('system.cookie_name') }}">
                                <small
                                    class="block mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Cookie name used by Laravel Passport for authentication') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Cookie Security --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <i class="mdi mdi-security text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white">{{ __('Cookie Security') }}
                            </h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('HTTPS Only') }}</label>
                                <select name="session[secure]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300">
                                    <option value="true" {{ config('session.secure') ? 'selected' : '' }}>
                                        {{ __('Yes') }}</option>
                                    <option value="false" {{ !config('session.secure') ? 'selected' : '' }}>
                                        {{ __('No') }}</option>
                                </select>
                                <small
                                    class="block mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Only send cookies over HTTPS connections') }}</small>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('HTTP Only') }}</label>
                                <select name="session[http_only]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300">
                                    <option value="true" {{ config('session.http_only') ? 'selected' : '' }}>
                                        {{ __('Yes') }}</option>
                                    <option value="false" {{ !config('session.http_only') ? 'selected' : '' }}>
                                        {{ __('No') }}</option>
                                </select>
                                <small
                                    class="block mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Prevent JavaScript access to cookies') }}</small>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('Partitioned Cookies') }}</label>
                                <select name="session[partitioned]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300">
                                    <option value="true" {{ config('session.partitioned') ? 'selected' : '' }}>
                                        {{ __('Yes') }}</option>
                                    <option value="false" {{ !config('session.partitioned') ? 'selected' : '' }}>
                                        {{ __('No') }}</option>
                                </select>
                                <small
                                    class="block mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Tie cookie to top-level site for cross-site context') }}</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
