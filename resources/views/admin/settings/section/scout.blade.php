@extends('admin.settings.setting')


@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm">
        {{-- Sidebar --}}
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 text-white p-6 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-magnify text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Scout Search') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure Laravel Scout search engine integration') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-indigo-600"></i>
                    {{ __('Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li>{{ __('Enable queue for better performance in production.') }}</li>
                    <li>{{ __('Use index prefix for multi-tenant environments.') }}</li>
                    <li>{{ __('Set chunk size according to server capacity.') }}</li>
                </ul>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="w-full lg:w-3/4 space-y-6">

            {{-- General Settings --}}
            <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-300 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                    {{ __('General Settings') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Driver --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Default Driver') }}
                        </label>
                        <select name="scout[driver]" id="scout_driver"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-700 text-gray-800 dark:text-gray-200">
                            @foreach (['collection', 'database', 'meilisearch', 'typesense', 'algolia', 'null'] as $driver)
                                <option value="{{ $driver }}"
                                    {{ config('scout.driver') === $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Prefix --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Index Prefix') }}
                        </label>
                        <input type="text" name="scout[prefix]" value="{{ config('scout.prefix') }}"
                            class="w-full px-4 py-3 text-gray-800 dark:text-gray-200 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-700">
                    </div>

                    {{-- Queue --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Enable Queue for Indexing') }}
                        </label>
                        <select name="scout[queue]"
                            class="w-full px-4 py-3 text-gray-800 dark:text-gray-200 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-700">
                            <option value="true" {{ config('scout.queue') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="false" {{ !config('scout.queue') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                    </div>

                    {{-- After Commit --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Sync After Database Commit') }}
                        </label>
                        <select name="scout[after_commit]"
                            class="w-full px-4 py-3 text-gray-800 dark:text-gray-200 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-700">
                            <option value="true" {{ config('scout.after_commit') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="false" {{ !config('scout.after_commit') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- Meilisearch Settings --}}
            <div id="meilisearch_config"
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-300 dark:border-gray-700 hidden">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                    {{ __('Meilisearch Configuration') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            Host
                        </label>
                        <input type="text" name="scout[meilisearch][host]"
                            value="{{ config('scout.meilisearch.host') }}"
                            class="w-full px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2  text-gray-800 dark:text-gray-200">
                            {{ __('API Key') }}
                        </label>
                        <input type="password" name="scout[meilisearch][key]" value="{{ config('scout.meilisearch.key') }}"
                            class="w-full text-gray-800 dark:text-gray-200 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-700">
                    </div>
                </div>
            </div>

            {{-- Performance --}}
            <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-300 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                    {{ __('Performance') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                            {{ __('Searchable Chunk Size') }}
                        </label>
                        <input type="number" name="scout[chunk][searchable]"
                            value="{{ config('scout.chunk.searchable') }}"
                            class="w-full px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Unsearchable Chunk Size') }}
                        </label>
                        <input type="number" name="scout[chunk][unsearchable]"
                            value="{{ config('scout.chunk.unsearchable') }}"
                            class="w-full px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 dark:bg-gray-700">
                    </div>

                    {{-- Soft Delete --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Keep Soft Deleted Records in Index') }}
                        </label>
                        <select name="scout[soft_delete]"
                            class="w-full px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 border border-gray-300 dark:bg-gray-700 dark:border-gray-700">
                            <option value="true" {{ config('scout.soft_delete') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="false" {{ !config('scout.soft_delete') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {

            const driverSelect = document.getElementById('scout_driver');
            const meiliSection = document.getElementById('meilisearch_config');

            function toggleMeili() {
                if (driverSelect.value === 'meilisearch') {
                    meiliSection.classList.remove('hidden');
                } else {
                    meiliSection.classList.add('hidden');
                }
            }

            toggleMeili();
            driverSelect.addEventListener('change', toggleMeili);
        });
    </script>
@endpush
