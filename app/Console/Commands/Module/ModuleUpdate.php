<?php

namespace App\Console\Commands\Module;

use App\Repositories\ModuleRepository;
use App\Services\ModuleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class ModuleUpdate extends Command
{
    use HandlesModulePublicAssets;

    protected $signature = 'module:update
    {--name= : Module name}
    {--target-version= : Target version (branch or tag)}
    {--env= : Installation environment (dev or production)}';

    protected $description = 'Update an existing third-party module';

    protected $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        parent::__construct();
        $this->moduleRepository = $moduleRepository;
    }

    public function handle(): int
    {
        $moduleNames = app(ModuleService::class)->all()->pluck('name', 'name')->toArray();

        if (empty($moduleNames)) {
            $this->error('No modules found in the database.');
            return self::FAILURE;
        }


        $name = $this->choice(
            'Select the module to update',
            $moduleNames
        );

        // List modules from database if no name provided
        if (!$name) {
            $modules = app(ModuleService::class)->all();

            if ($modules->isEmpty()) {
                $this->error('No modules registered in the database.');
                return self::FAILURE;
            }

            $this->info('Registered modules:');
            $this->newLine();

            $headers = ['#', 'Name', 'Current Version', 'Last Version', 'New Version', 'Provider'];
            $rows = [];
            foreach ($modules as $index => $module) {
                $rows[] = [
                    $index + 1,
                    $module->name,
                    $module->current_version ?: 'N/A',
                    $module->last_version ?: 'N/A',
                    $module->new_version ?: 'N/A',
                    $module->provider ?: 'N/A'
                ];
            }

            $this->table($headers, $rows);

            $name = $this->ask('Enter the module name to update');

            if (!$name) {
                $this->error('Module name is required.');
                return self::FAILURE;
            }
        }

        // Normalize module name
        $name = normalizeModuleName($name);

        // Find the module in database
        $module = app(ModuleService::class)->findByName($name);

        if (!$module) {
            $this->error("Module '{$name}' not found in database.");
            return self::FAILURE;
        }

        // Verify module exists in filesystem
        $modulePath = $module->path;
        if (!File::isDirectory($modulePath)) {
            $this->error("Module path '{$modulePath}' does not exist.");
            return self::FAILURE;
        }

        // Show current module info
        $this->info('Current module details:');
        $this->newLine();
        $this->line("Name: {$module->name}");
        $this->line("Current Version: " . ($module->current_version ?: 'N/A'));
        $this->line("Last Version: " . ($module->last_version ?: 'N/A'));
        $this->line("New Version: " . ($module->new_version ?: 'N/A'));
        $this->line("Provider: " . ($module->provider ?: 'N/A'));
        $this->line("Path: {$modulePath}");
        $this->newLine();

        // Get available versions (branches/tags)
        $version = $this->option('target-version');

        if (!$version) {

            $availableVersions = $this->getAvailableVersions(
                $module
            );

            $availableVersions = array_slice(
                $availableVersions,
                0,
                20
            );

            $availableVersions[] = 'Custom version…';

            $versionChoice = $this->choice(
                'Select the target version to update to',
                $availableVersions,
                0
            );

            if ($versionChoice === 'Custom version…') {

                $version = $this->ask(
                    'Enter the target version manually'
                );

            } else {

                $version = trim(
                    preg_replace('/^[^a-zA-Z0-9]*/u', '', $versionChoice)
                );
            }
        }

        if (!$version) {

            $this->error(
                'Version is required to update.'
            );

            return self::FAILURE;
        }

        // Don't update if same version
        if ($version === $module->current_version) {
            $this->warn("Module is already at version {$version}.");
            if (!$this->confirm('Do you want to reinstall this version?')) {
                $this->info('Update cancelled.');
                return self::SUCCESS;
            }
        }

        // Environment selection
        $environment = $this->option('env');
        if (!$environment) {
            $environment = $this->choice(
                'Select installation environment',
                ['dev', 'production'],
                app()->environment('production') ? 1 : 0
            );
        }

        if (!in_array($environment, ['dev', 'production'])) {
            $this->error('Invalid environment. Allowed: dev or production.');
            return self::FAILURE;
        }

        // Confirm update
        $this->newLine();
        $this->warn('You are about to update the module:');
        $this->line("Module: {$name}");
        $this->line("From version: " . ($module->current_version ?: 'N/A'));
        $this->line("To version: {$version}");
        $this->line("Environment: {$environment}");
        $this->newLine();

        if (!$this->confirm('Do you want to proceed with the update?')) {
            $this->info('Update cancelled.');
            return self::SUCCESS;
        }

        // Execute update
        $previousVersion = $module->current_version;

        // Step 1: Update new_version in database before git operations
        app(ModuleService::class)->update($module->getId(), [
            'new_version' => $version
        ]);


        // Step 2: Pull/Checkout target version
        if (!$this->checkoutVersion($modulePath, $version)) {
            app(ModuleService::class)->update($module->getId(), [
                'new_version' => null
            ]);

            $this->error('Update failed: Git checkout failed');
            return self::FAILURE;
        }

        // Step 3: Update current_version and last_version in database
        app(ModuleService::class)->update($module->getId(), [
            'last_version' => $previousVersion,
            'current_version' => $version,
            'new_version' => null
        ]);

        // Step 4: Install/Update dependencies
        if (!$this->runComposerUpdate($modulePath, $environment)) {
            $this->error('Update failed: Composer update failed');
            return self::FAILURE;
        }

        if (!$this->ensureModulePublicSymlink($modulePath)) {
            $this->error('Update failed: Public assets link failed');
            return self::FAILURE;
        }

        // Step 5: Run migrations
        if (!$this->runMigrations()) {
            $this->error('Update failed: Migrations failed');
            return self::FAILURE;
        }

        // Step 6: Reload module services
        $this->info('Reloading module services...');
        if (!$this->loadServices()) {
            $this->error('Update failed: Service loading failed');
            return self::FAILURE;
        }

        $this->newLine();
        $this->info("✓ Module '{$name}' updated successfully to version {$version}.");
        $this->line("Previous version: " . ($previousVersion ?: 'N/A'));
        $this->line("Current version: {$version}");

        return self::SUCCESS;
    }

    /**
     * Get available versions (branches and tags) from the module repository
     */
    protected function getAvailableVersions(object $module): array
    {
        $this->info('Fetching available versions...');

        try {

            /**
             * Packagist
             */
            if ($module->provider === 'packagist') {

                $url = "https://repo.packagist.org/p2/{$module->source}.json";

                $response = \Illuminate\Support\Facades\Http::timeout(15)
                    ->get($url);

                if (!$response->successful()) {
                    return [];
                }

                $packages = $response->json(
                    "packages.{$module->source}",
                    []
                );

                return collect($packages)
                    ->pluck('version')
                    ->filter()
                    ->unique()
                    ->take(20)
                    ->values()
                    ->toArray();
            }

            /**
             * Git
             */
            if ($module->provider === 'git') {

                $modulePath = $module->path;

                $fetchProcess = new Process([
                    'git',
                    'fetch',
                    '--all',
                    '--tags',
                    '--prune'
                ], $modulePath);

                $fetchProcess->setTimeout(300);
                $fetchProcess->run();

                $versions = [];

                /**
                 * Branches
                 */
                $branchProcess = new Process([
                    'git',
                    'for-each-ref',
                    '--format=%(refname:short)',
                    'refs/remotes/origin'
                ], $modulePath);

                $branchProcess->run();

                if ($branchProcess->isSuccessful()) {

                    foreach (
                        explode(
                            "\n",
                            trim($branchProcess->getOutput())
                        ) as $branch
                    ) {

                        $branch = trim($branch);

                        if (
                            empty($branch)
                            || str_contains($branch, 'HEAD')
                        ) {
                            continue;
                        }

                        $branch = preg_replace(
                            '/^origin\//',
                            '',
                            $branch
                        );

                        $versions[] = "{$branch}";
                    }
                }

                /**
                 * Tags
                 */
                $tagProcess = new Process([
                    'git',
                    'tag',
                    '--list',
                    '--sort=-version:refname'
                ], $modulePath);

                $tagProcess->run();

                if ($tagProcess->isSuccessful()) {

                    foreach (
                        explode(
                            "\n",
                            trim($tagProcess->getOutput())
                        ) as $tag
                    ) {

                        $tag = trim($tag);

                        if (!empty($tag)) {
                            $versions[] = "{$tag}";
                        }
                    }
                }

                return array_values(
                    array_unique($versions)
                );
            }

            return [];

        } catch (\Throwable $e) {

            $this->warn(
                'Could not fetch available versions: '
                . $e->getMessage()
            );

            return [];
        }
    }

    /**
     * Checkout the target version from git
     */
    protected function checkoutVersion(string $modulePath, string $version): bool
    {
        $this->info("Checking out version: {$version}...");

        // Stash any local changes first
        $stashProcess = new Process(['git', 'stash', '--include-untracked'], $modulePath);
        $stashProcess->run();

        // Fetch latest changes
        $fetchProcess = new Process(['git', 'fetch', '--all', '--tags'], $modulePath);
        $fetchProcess->setTimeout(300);
        $fetchProcess->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                $this->warn(trim($buffer));
            } else {
                $this->line(trim($buffer));
            }
        });

        // Try to checkout the version
        $checkoutProcess = new Process(['git', 'checkout', $version], $modulePath);
        $checkoutProcess->setTimeout(300);
        $checkoutProcess->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                $this->warn(trim($buffer));
            } else {
                $this->line(trim($buffer));
            }
        });

        if (!$checkoutProcess->isSuccessful()) {
            $this->error("Failed to checkout version: {$version}");
            return false;
        }

        // Pull latest changes if it's a branch
        $isTag = $this->isTag($modulePath, $version);

        if (!$isTag) {
            $this->info("Pulling latest changes for branch: {$version}");
            $pullProcess = new Process(['git', 'pull', 'origin', $version], $modulePath);
            $pullProcess->setTimeout(300);
            $pullProcess->run(function ($type, $buffer) {
                if ($type === Process::ERR) {
                    $this->warn(trim($buffer));
                } else {
                    $this->line(trim($buffer));
                }
            });

            if (!$pullProcess->isSuccessful()) {
                $this->warn('Could not pull latest changes, but checkout was successful.');
            }
        }

        return true;
    }

    /**
     * Check if a version is a git tag
     */
    protected function isTag(string $modulePath, string $version): bool
    {
        $process = new Process(['git', 'tag', '-l', $version], $modulePath);
        $process->run();

        return $process->isSuccessful() && trim($process->getOutput()) === $version;
    }

    /**
     * Run composer update in the module path
     */
    protected function runComposerUpdate(string $path, string $environment): bool
    {
        $this->info('Running Elyscope update...');

        $command = [
            'elyscope',
            'update',
            '--lock',
            '--no-interaction',
            '--prefer-dist',
            '--no-progress'
        ];

        if ($environment === 'production') {
            $command[] = '--no-dev';
            $command[] = '--optimize-autoloader';
        }

        $process = new Process($command, $path);
        $process->setTimeout(600); // 10 minutes timeout for composer
        $process->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                $this->warn(trim($buffer));
            } else {
                $this->line(trim($buffer));
            }
        });

        if (!$process->isSuccessful()) {
            $this->error('Elyscope update failed.');
            return false;
        }

        return true;
    }

    /**
     * Run database migrations
     */
    protected function runMigrations(): bool
    {
        $this->info('Running migrations...');

        try {
            Artisan::call('migrate', [
                '--force' => true
            ]);

            $output = Artisan::output();
            $this->line($output);

            return true;
        } catch (\Throwable $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Load module services
     */
    protected function loadServices(): bool
    {
        try {
            Artisan::call('module:services-uploads');
            $output = Artisan::output();
            $this->line($output);
            return true;
        } catch (\Throwable $th) {
            $this->error('Service loading failed: ' . $th->getMessage());
            return false;
        }
    }


}
