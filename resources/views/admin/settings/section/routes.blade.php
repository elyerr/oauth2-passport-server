@extends('admin.settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-routes text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Route Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Control application routing and feature accessibility') }}</p>
            </div>

            <div
                class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="mdi mdi-security mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Security Notes') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-account text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Disable registration to restrict account creation') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-api text-yellow-500 dark:text-yellow-400 mr-2 mt-0.5"></i>
                        {{ __('API access should be limited to trusted users') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-developer-board text-blue-500 dark:text-blue-400 mr-2 mt-0.5"></i>
                        {{ __('Developer tools are for advanced users only') }}
                    </li>
                </ul>
            </div>
        </div>

        @php($routes = config('routes', []))
        <div class="grid grid-cols-1 gap-3">

            @foreach ($routes as $root => $groups)
                <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">

                    <div class="flex items-center mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                            <i class="mdi mdi-routes text-indigo-600 dark:text-indigo-400 text-xl"></i>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ ucfirst(str_replace('_', ' ', $root)) }}
                        </h3>

                    </div>

                    @foreach ($groups as $group => $features)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                {{ ucfirst(str_replace('_', ' ', $group)) }}
                            </h4>

                            <div class="space-y-3">
                                @foreach ($features as $feature => $data)
                                    <div
                                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">

                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-800 dark:text-white">
                                                {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                            </label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ __(config("routes.{$root}.{$group}.{$feature}.description", $data['description'])) }}
                                            </p>

                                            <input type="hidden"
                                                name="routes[{{ $root }}][{{ $group }}][{{ $feature }}][description]"
                                                value="{{ config("routes.{$root}.{$group}.{$feature}.description", $data['description']) }}">
                                        </div>

                                        <div class="ml-4">

                                            <select
                                                name="routes[{{ $root }}][{{ $group }}][{{ $feature }}][status]"
                                                class="w-28 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                           bg-white dark:bg-gray-600 text-gray-800 dark:text-white
                                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                                <option value="true"
                                                    {{ config("routes.{$root}.{$group}.{$feature}.status", true) == true ? 'selected' : '' }}>
                                                    {{ __('Enabled') }}
                                                </option>

                                                <option value="false"
                                                    {{ config("routes.{$root}.{$group}.{$feature}.status", true) == false ? 'selected' : '' }}>
                                                    {{ __('Disabled') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
