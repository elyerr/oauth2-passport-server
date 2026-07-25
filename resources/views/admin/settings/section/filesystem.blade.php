@extends('admin.settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-folder-cog text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Filesystem Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure storage disks and file handling') }}
                </p>
            </div>

            <div
                class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Storage Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-server text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Use local storage for development and testing') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-cloud text-blue-500 mr-2 mt-0.5"></i>
                        {{ __('S3 is recommended for production environments') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-security text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('Secure your S3 credentials properly') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Default Filesystem Configuration -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3">
                        <i class="mdi mdi-star-cog text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Default Filesystem Disk') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                            {{ __('Default Disk') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="filesystems[default]" id="filesystem-select"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300">
                            @foreach (['local', 'public', 's3'] as $driver)
                                <option value="{{ $driver }}"
                                    {{ config('filesystems.default') == $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select the default storage disk for your application') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Disk Configurations -->
            <div class="space-y-6">
                <!-- Local Disk -->
                <div id="disk-local"
                    class="disk-settings p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg mr-3">
                            <i class="mdi mdi-server text-green-500 dark:text-green-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Local Disk Configuration') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (['driver', 'root', 'throw'] as $key)
                            <div class="{{ $key === 'root' ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                                    {{ ucfirst($key) }}
                                    @if ($key === 'root')
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if ($key == 'throw')
                                    <select name="filesystems[disks][local][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300">
                                        <option value="false"
                                            {{ !config('filesystems.disks.local.throw', false) ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="true"
                                            {{ config('filesystems.disks.local.throw', false) ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                @else
                                    <div class="relative">
                                        <input type="text" name="filesystems[disks][local][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300"
                                            value="{{ config('filesystems.disks.local.' . $key, $key == 'root' ? storage_path('app') : 'local') }}"
                                            {{ $key == 'driver' ? 'readonly' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'driver' ? 'cog' : 'folder' }} text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                @endif
                                @if ($key === 'root')
                                    <small class="block mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Absolute path to the local storage directory') }}
                                    </small>
                                @elseif($key === 'throw')
                                    <small class="block mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Throw exceptions on write errors') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Public Disk -->
                <div id="disk-public"
                    class="disk-settings p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                            <i class="mdi mdi-folder-public text-blue-500 dark:text-blue-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Public Disk Configuration') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (['driver', 'root', 'url', 'visibility', 'throw'] as $key)
                            <div class="{{ in_array($key, ['root', 'url']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                                    {{ ucfirst($key) }}
                                    @if (in_array($key, ['root', 'url']))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if ($key == 'throw')
                                    <select name="filesystems[disks][public][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300">
                                        <option value="false"
                                            {{ !config('filesystems.disks.public.throw', false) ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="true"
                                            {{ config('filesystems.disks.public.throw', false) ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                @elseif ($key == 'visibility')
                                    <select name="filesystems[disks][public][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300">
                                        <option value="public"
                                            {{ config('filesystems.disks.public.visibility', 'public') == 'public' ? 'selected' : '' }}>
                                            {{ __('Public') }}
                                        </option>
                                        <option value="private"
                                            {{ config('filesystems.disks.public.visibility', 'public') == 'private' ? 'selected' : '' }}>
                                            {{ __('Private') }}
                                        </option>
                                    </select>
                                @else
                                    <div class="relative">
                                        <input type="text" name="filesystems[disks][public][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300"
                                            value="{{ config('filesystems.disks.public.' . $key, '') }}"
                                            {{ $key == 'driver' ? 'readonly' : '' }}
                                            placeholder="{{ $key === 'url' ? 'http://localhost/storage' : '' }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'driver' ? 'cog' : ($key === 'root' ? 'folder' : ($key === 'url' ? 'link' : 'eye')) }} text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                @endif
                                @if ($key === 'url')
                                    <small class="block mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Base URL for public asset links') }}
                                    </small>
                                @elseif($key === 'visibility')
                                    <small class="block mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Default file visibility setting') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- S3 Disk -->
                <div id="disk-s3"
                    class="disk-settings p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg mr-3">
                            <i class="mdi mdi-amazon text-yellow-500 dark:text-yellow-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Amazon S3 Configuration') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (['driver', 'key', 'secret', 'region', 'bucket', 'url', 'endpoint', 'use_path_style_endpoint', 'throw'] as $key)
                            <div class="{{ in_array($key, ['key', 'secret', 'url', 'endpoint']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                    @if (in_array($key, ['key', 'secret', 'bucket', 'region']))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if (in_array($key, ['throw', 'use_path_style_endpoint']))
                                    <select name="filesystems[disks][s3][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300">
                                        <option value="false"
                                            {{ !config('filesystems.disks.s3.' . $key, false) ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="true"
                                            {{ config('filesystems.disks.s3.' . $key, false) ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                @else
                                    <div class="relative">
                                        <input type="{{ in_array($key, ['key', 'secret']) ? 'password' : 'text' }}"
                                            name="filesystems[disks][s3][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-300"
                                            value="{{ config('filesystems.disks.s3.' . $key, '') }}"
                                            {{ $key == 'driver' ? 'readonly' : '' }}
                                            placeholder="{{ $key === 'region' ? 'us-east-1' : ($key === 'bucket' ? 'your-bucket-name' : ($key === 'endpoint' ? 'https://s3.region.amazonaws.com' : '')) }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'driver' ? 'cog' : ($key === 'key' ? 'key' : ($key === 'secret' ? 'key-variant' : ($key === 'region' ? 'earth' : ($key === 'bucket' ? 'bucket' : ($key === 'url' ? 'link' : ($key === 'endpoint' ? 'web' : 'alert-circle')))))) }} text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                @endif
                                @if ($key === 'use_path_style_endpoint')
                                    <small class="block mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Use path-style endpoint for legacy S3 compatibility') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div
                        class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 transition-colors duration-300">
                        <div class="flex items-center">
                            <i class="mdi mdi-information-outline text-blue-500 mr-2"></i>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('AWS credentials require appropriate IAM permissions for S3 access') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            const selectFilesystem = document.getElementById("filesystem-select");
            const disks = ["local", "public", "s3"];

            function toggleDisks() {
                let selected = selectFilesystem.value;
                disks.forEach(disk => {
                    const diskElement = document.getElementById(`disk-${disk}`);
                    if (diskElement) {
                        diskElement.style.display = (disk === selected) ? "block" : "none";
                    }
                });
            }

            selectFilesystem.addEventListener("change", toggleDisks);
            toggleDisks();

            // Storage management actions
            const storageButtons = ['test-connection', 'create-symlink', 'clear-storage', 'storage-stats'];
            storageButtons.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener('click', function() {
                        const action = this.id.replace('-', '_');
                        handleStorageAction(action);
                    });
                }
            });

            function handleStorageAction(action) {
                const button = document.getElementById(action.replace('_', '-'));
                const originalText = button.innerHTML;
                const actionNames = {
                    'test_connection': 'Storage Connection',
                    'create_symlink': 'Storage Symlink',
                    'clear_storage': 'Temporary Files',
                    'storage_stats': 'Storage Statistics'
                };

                button.innerHTML = '<i class="mdi mdi-loading animate-spin mr-2"></i>Processing...';
                button.disabled = true;

                // Simulate API call (replace with actual implementation)
                setTimeout(() => {
                    const success = Math.random() > 0.2; // 80% success rate for demo

                    if (success) {
                        showToast(`{{ __(':action completed successfully') }}`.replace(':action',
                            actionNames[action]), 'success');
                    } else {
                        showToast(`{{ __('Failed to complete :action') }}`.replace(':action', actionNames[
                            action]), 'error');
                    }

                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);
            }

            function showToast(message, type = 'info') {
                // Toast notification implementation
                console.log(`${type}: ${message}`);
            }

            // Toggle password visibility for S3 credentials
            const s3SecretInput = document.querySelector('input[name="filesystems[disks][s3][secret]"]');
            if (s3SecretInput) {
                const toggleVisibility = document.createElement('button');
                toggleVisibility.type = 'button';
                toggleVisibility.className = 'absolute inset-y-0 right-0 pr-3 flex items-center';
                toggleVisibility.innerHTML =
                    '<i class="mdi mdi-eye-outline text-[var(--color-text-secondary)]"></i>';
                toggleVisibility.addEventListener('click', function() {
                    const type = s3SecretInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    s3SecretInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ?
                        '<i class="mdi mdi-eye-outline text-[var(--color-text-secondary)]"></i>' :
                        '<i class="mdi mdi-eye-off-outline text-[var(--color-text-secondary)]"></i>';
                });

                s3SecretInput.parentNode.appendChild(toggleVisibility);
            }
        });
    </script>
@endpush
