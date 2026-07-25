@extends('admin.settings.setting')

@section('form')
    <!-- Horizon Configuration -->
    <div class="space-y-6">
        <!-- Basic Settings -->
        <div
            class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                    <i class="mdi mdi-chart-line text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ __('Laravel Horizon Settings') }}
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Redis Prefix -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Redis Prefix') }}
                    </label>
                    <input type="text" name="horizon[prefix]"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        placeholder="app_horizon:" value="{{ config('horizon.prefix') }}">
                </div>

                <!-- Memory Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Memory Limit (MB)') }}
                    </label>
                    <input type="number" name="horizon[memory_limit]" min="32"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.memory_limit', 64) }}">
                </div>

                <!-- Fast Termination -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Fast Termination') }}
                    </label>
                    <select name="horizon[fast_termination]"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">
                        <option value="false" {{ config('horizon.fast_termination') ? '' : 'selected' }}>
                            {{ __('Disabled') }}
                        </option>
                        <option value="true" {{ config('horizon.fast_termination') ? 'selected' : '' }}>
                            {{ __('Enabled') }}
                        </option>
                    </select>
                </div>

                <!-- Queue Wait Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Queue Wait Threshold (seconds)') }}
                    </label>
                    <input type="number" name="horizon[waits][redis:default]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.waits.redis:default', 60) }}">
                </div>
            </div>
        </div>

        <!-- Job Trimming Times -->
        <div
            class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                    <i class="mdi mdi-content-cut text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ __('Job Trimming Times') }}
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Recent Jobs -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Recent Jobs (minutes)') }}
                    </label>
                    <input type="number" name="horizon[trim][recent]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.trim.recent', 60) }}">
                </div>

                <!-- Pending Jobs -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Pending Jobs (minutes)') }}
                    </label>
                    <input type="number" name="horizon[trim][pending]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.trim.pending', 60) }}">
                </div>

                <!-- Completed Jobs -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Completed Jobs (minutes)') }}
                    </label>
                    <input type="number" name="horizon[trim][completed]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.trim.completed', 60) }}">
                </div>

                <!-- Recent Failed Jobs -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Recent Failed Jobs (minutes)') }}
                    </label>
                    <input type="number" name="horizon[trim][recent_failed]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.trim.recent_failed', 10080) }}">
                </div>

                <!-- Failed Jobs -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Failed Jobs (minutes)') }}
                    </label>
                    <input type="number" name="horizon[trim][failed]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.trim.failed', 10080) }}">
                </div>

                <!-- Monitored Jobs -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Monitored Jobs (minutes)') }}
                    </label>
                    <input type="number" name="horizon[trim][monitored]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.trim.monitored', 10080) }}">
                </div>
            </div>
        </div>

        <!-- Metrics Configuration -->
        <div
            class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg mr-3">
                    <i class="mdi mdi-chart-bar text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ __('Metrics Configuration') }}
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Job Snapshots -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Job Snapshots (hours)') }}
                    </label>
                    <input type="number" name="horizon[metrics][trim_snapshots][job]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.metrics.trim_snapshots.job', 24) }}">
                </div>

                <!-- Queue Snapshots -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Queue Snapshots (hours)') }}
                    </label>
                    <input type="number" name="horizon[metrics][trim_snapshots][queue]" min="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                        value="{{ config('horizon.metrics.trim_snapshots.queue', 24) }}">
                </div>
            </div>
        </div>

        <!-- Supervisor Default Configuration -->
        <div
            class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg mr-3">
                    <i class="mdi mdi-server text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ __('Supervisor Default Configuration') }}
                </h3>
            </div>

            <div class="space-y-4">
                <!-- Queues -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Queues (comma separated)') }}
                    </label>
                    <textarea name="horizon[defaults][supervisor-1][queue]"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">{{ config('horizon.defaults.supervisor-1.queue') }}</textarea>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Connection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Connection') }}
                        </label>
                        <select name="horizon[defaults][supervisor-1][connection]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">
                            <option value="redis"
                                {{ config('horizon.defaults.supervisor-1.connection', 'redis') == 'redis' ? 'selected' : '' }}>
                                {{ __('Redis') }}
                            </option>
                        </select>
                    </div>

                    <!-- Balance Strategy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Balance Strategy') }}
                        </label>
                        <select name="horizon[defaults][supervisor-1][balance]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">
                            <option value="auto"
                                {{ config('horizon.defaults.supervisor-1.balance') == 'auto' ? 'selected' : '' }}>
                                {{ __('Auto') }}
                            </option>
                            <option value="simple"
                                {{ config('horizon.defaults.supervisor-1.balance') == 'simple' ? 'selected' : '' }}>
                                {{ __('Simple') }}
                            </option>
                            <option value="false"
                                {{ config('horizon.defaults.supervisor-1.balance') == 'false' ? 'selected' : '' }}>
                                {{ __('False') }}
                            </option>
                        </select>
                    </div>

                    <!-- Auto Scaling Strategy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Auto Scaling Strategy') }}
                        </label>
                        <select name="horizon[defaults][supervisor-1][autoScalingStrategy]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">
                            <option value="time"
                                {{ config('horizon.defaults.supervisor-1.autoScalingStrategy') == 'time' ? 'selected' : '' }}>
                                {{ __('Time') }}
                            </option>
                            <option value="size"
                                {{ config('horizon.defaults.supervisor-1.autoScalingStrategy') == 'size' ? 'selected' : '' }}>
                                {{ __('Size') }}
                            </option>
                        </select>
                    </div>

                    <!-- Max Processes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Max Processes') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][maxProcesses]" min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.maxProcesses', 4) }}">
                    </div>

                    <!-- Max Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Max Time (seconds, 0 = unlimited)') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][maxTime]" min="0"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.maxTime', 0) }}">
                    </div>

                    <!-- Max Jobs -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Max Jobs per Worker') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][maxJobs]" min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.maxJobs', 4) }}">
                    </div>

                    <!-- Memory per Worker -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Memory per Worker (MB)') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][memory]" min="32"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.memory', 128) }}">
                    </div>

                    <!-- Tries -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Max Tries') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][tries]" min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.tries', 3) }}">
                    </div>

                    <!-- Timeout -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Timeout (seconds)') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][timeout]" min="30"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.timeout', 120) }}">
                    </div>

                    <!-- Nice -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Nice (priority, -20 to 19)') }}
                        </label>
                        <input type="number" name="horizon[defaults][supervisor-1][nice]" min="-20" max="19"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.defaults.supervisor-1.nice', 0) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Environment Configurations -->
        <div
            class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                    <i class="mdi mdi-cloud text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ __('Environment Configurations') }}
                </h3>
            </div>

            <!-- Production Environment -->
            <div class="mb-6">
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                    <i class="mdi mdi-server-security text-green-500 mr-2"></i>
                    {{ __('Production Environment') }}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-4 border-l-2 border-green-200 dark:border-green-800">
                    <!-- Min Processes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Min Processes') }}
                        </label>
                        <input type="number" name="horizon[environments][production][supervisor-1][minProcesses]"
                            min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.environments.production.supervisor-1.minProcesses', 1) }}">
                    </div>

                    <!-- Max Processes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Max Processes') }}
                        </label>
                        <input type="number" name="horizon[environments][production][supervisor-1][maxProcesses]"
                            min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.environments.production.supervisor-1.maxProcesses', 5) }}">
                    </div>

                    <!-- Balance Max Shift -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Balance Max Shift') }}
                        </label>
                        <input type="number" name="horizon[environments][production][supervisor-1][balanceMaxShift]"
                            min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.environments.production.supervisor-1.balanceMaxShift', 1) }}">
                    </div>

                    <!-- Balance Cooldown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Balance Cooldown (seconds)') }}
                        </label>
                        <input type="number" name="horizon[environments][production][supervisor-1][balanceCooldown]"
                            min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.environments.production.supervisor-1.balanceCooldown', 3) }}">
                    </div>
                </div>
            </div>

            <!-- Development Environment -->
            <div>
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                    <i class="mdi mdi-code-tags text-yellow-500 mr-2"></i>
                    {{ __('Development Environment') }}
                </h4>
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 pl-4 border-l-2 border-yellow-200 dark:border-yellow-800">
                    <!-- Balance Strategy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Balance Strategy') }}
                        </label>
                        <select name="horizon[environments][dev][supervisor-1][balance]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">
                            <option value="auto"
                                {{ config('horizon.environments.dev.supervisor-1.balance', 'auto') == 'auto' ? 'selected' : '' }}>
                                {{ __('Auto') }}
                            </option>
                            <option value="simple"
                                {{ config('horizon.environments.dev.supervisor-1.balance') == 'simple' ? 'selected' : '' }}>
                                {{ __('Simple') }}
                            </option>
                            <option value="false"
                                {{ config('horizon.environments.dev.supervisor-1.balance') == 'false' ? 'selected' : '' }}>
                                {{ __('False') }}
                            </option>
                        </select>
                    </div>

                    <!-- Auto Scaling Strategy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Auto Scaling Strategy') }}
                        </label>
                        <select name="horizon[environments][dev][supervisor-1][autoScalingStrategy]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">
                            <option value="time"
                                {{ config('horizon.environments.dev.supervisor-1.autoScalingStrategy', 'time') == 'time' ? 'selected' : '' }}>
                                {{ __('Time') }}
                            </option>
                            <option value="size"
                                {{ config('horizon.environments.dev.supervisor-1.autoScalingStrategy') == 'size' ? 'selected' : '' }}>
                                {{ __('Size') }}
                            </option>
                        </select>
                    </div>

                    <!-- Max Processes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                            {{ __('Max Processes') }}
                        </label>
                        <input type="number" name="horizon[environments][dev][supervisor-1][maxProcesses]"
                            min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
                            value="{{ config('horizon.environments.dev.supervisor-1.maxProcesses', 5) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Silenced Jobs -->
        <div
            class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg mr-3">
                    <i class="mdi mdi-bell-off text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ __('Silenced Jobs') }}
                </h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Job Classes (one per line)') }}
                    </label>
                    <textarea name="horizon[silenced]" rows="3"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm font-mono text-sm"
                        placeholder="App\Jobs\ExampleJob&#10;App\Jobs\AnotherJob">{{ is_array(config('horizon.silenced')) ? implode("\n", config('horizon.silenced')) : '' }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white mb-2">
                        {{ __('Silenced Tags (one per line)') }}
                    </label>
                    <textarea name="horizon[silenced_tags]" rows="2"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm font-mono text-sm"
                        placeholder="notifications&#10;emails">{{ is_array(config('horizon.silenced_tags')) ? implode("\n", config('horizon.silenced_tags')) : '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
            <div class="flex items-start">
                <i class="mdi mdi-information-outline text-indigo-500 mt-0.5 mr-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Laravel Horizon manages Redis queues and background workers. Adjust these settings to optimize job processing performance. Changes will take effect after running php artisan horizon:terminate') }}
                </span>
            </div>
        </div>
    </div>
@endsection
