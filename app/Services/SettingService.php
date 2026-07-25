<?php

namespace App\Services;

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

use App\Support\CacheKeys;
use Core\User\Model\Scope;
use App\Models\OAuth\Token;
use Illuminate\Support\Str;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use App\Models\OAuth\AuthCode;
use Laravel\Passport\Passport;
use App\Models\OAuth\RefreshToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Repositories\SettingRepository;
use Illuminate\Database\QueryException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Throwable;

class SettingService
{
    public function __construct(protected SettingRepository $settingRepository)
    {
    }

    /**
     * Cache key
     * @return string
     */
    public function cacheKey()
    {
        return "settings";
    }

    /**
     * Restore the all config keys
     * @return void
     */
    public function resetConfigKeys()
    {
        file_put_contents($this->pid(), time(), LOCK_EX);
    }

    /**
     * Set a pid to reset cache
     * @return string
     */
    public function pid()
    {
        return base_path('.cache.pid');
    }

    public function ping()
    {
        Cache::store('redis')->get('ping');
    }

    /**
     * Load configs
     */
    public function loadConfigKeys()
    {
        try {
            if (file_exists($this->pid())) {
                Cache::forget(CacheKeys::config());
                @unlink($this->pid());
            }

            $settings = Cache::remember(
                CacheKeys::config(),
                now()->addDays(intval(config('cache.expires', 90))),
                function () {
                    return $this->settingRepository->getConfig();
                }
            );

            foreach ($settings as $key => $value) {

                // Ignore empty values
                if ($value === null || $value === '') {
                    continue;
                }

                if ($value == 'false') {
                    $value = false;
                } elseif ($value == 'true') {
                    $value = true;
                }

                // Convert string numbers to string
                elseif (filter_var($value, FILTER_VALIDATE_INT) !== false) {
                    $value = intval($value);
                }
                // Convert to float from string number
                elseif (is_numeric($value)) {
                    $value = floatval($value);
                }

                // Set the new config
                Config::set($key, $value);
            }
        } catch (\Throwable $th) {
            // Try to clean cache configuration
            $this->resetConfigKeys();
            // Log
            throw new ReportError($th->getMessage(), $th->getTrace());
        }
    }

    /**
     * Set default values
     * @return void
     */
    public function getDefaultSetting()
    {
        URL::forceScheme(env('APP_URL_SCHEME', 'https'));

        $this->loadConfigKeys();

        //Horizon cache settings
        Config::set('database.redis.horizon.url', config('database.redis.cache.url', null));
        Config::set('database.redis.horizon.host', config('database.redis.cache.host', '127.0.0.1'));
        Config::set('database.redis.horizon.username', config('database.redis.cache.username', null));
        Config::set('database.redis.horizon.password', config('database.redis.cache.password', null));
        Config::set('database.redis.horizon.port', intval(config('database.redis.cache.port', 6379)));
        Config::set('database.redis.horizon.database', intval(config('database.redis.cache.database', 0)));

        $this->getPassportSetting();
    }

    /**
     * Add default setting into the system
     * @return void
     */
    public function setDefaultKeys()
    {
        //App name
        $this->settingRepository->load('app.name', 'Oauth2 Server');
        $this->settingRepository->load('app.org_name', 'Server org');

        //expires time for reset password
        $this->settingRepository->load('auth.passwords.users.expire', 10);
        //expires time to try another request
        $this->settingRepository->load('auth.passwords.users.throttle', 10);

        $this->settingRepository->load('auth.password_timeout', 10800);

        //------------------------CACHE CONFIGURATION-------------------//
        $this->settingRepository->load('cache.default', 'file');
        $this->settingRepository->load('cache.expires', 30);
        $this->settingRepository->load('cache.prefix', Str::slug((string) env('APP_NAME', 'laravel')) . '-cache-');

        $this->settingRepository->load('cache.stores.database.table', 'cache');

        $this->settingRepository->load('cache.stores.redis.connection', 'cache');
        $this->settingRepository->load('cache.stores.redis.lock_connection', 'default');

        $this->settingRepository->load('cache.stores.memcached.servers.0.host', '127.0.0.1');
        $this->settingRepository->load('cache.stores.memcached.servers.0.port', 11211);
        $this->settingRepository->load('cache.stores.memcached.servers.0.weight', 100);

        $this->settingRepository->load('cache.stores.dynamodb.region', 'us-east-1');
        $this->settingRepository->load('cache.stores.dynamodb.table', 'cache');
        //------------------------END CACHE CONFIGURATION-------------------//


        //---------------------QUEUES CONFIG--------------------///
        //default queues
        $this->settingRepository->load('queue.default', 'database');

        //Database settings 
        $this->settingRepository->load('queue.connections.database.table', 'jobs');
        $this->settingRepository->load('queue.connections.database.queue', 'default');
        $this->settingRepository->load('queue.connections.database.retry_after', 90);
        $this->settingRepository->load('queue.connections.database.after_commit', false);

        //beanstalkd Settings 
        $this->settingRepository->load('queue.connections.beanstalkd.host', 'localhost');
        $this->settingRepository->load('queue.connections.beanstalkd.queue', 'default');
        $this->settingRepository->load('queue.connections.beanstalkd.retry_after', 90);
        $this->settingRepository->load('queue.connections.beanstalkd.block_for', 0);
        $this->settingRepository->load('queue.connections.beanstalkd.after_commit', false);

        //AWS settings
        //$this->settingRepository->load('queue.connections.sqs.driver', 'sqs');
        $this->settingRepository->load('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id');
        $this->settingRepository->load('queue.connections.sqs.queue', 'default');
        $this->settingRepository->load('queue.connections.sqs.region', 'us-east-1');
        $this->settingRepository->load('queue.connections.sqs.after_commit', false);

        //Redis Settings
        //$this->settingRepository->load('queue.connections.redis.driver', 'redis');
        $this->settingRepository->load('queue.connections.redis.connection', 'default');
        $this->settingRepository->load('queue.connections.redis.queue', 'default');
        $this->settingRepository->load('queue.connections.redis.retry_after', 90);
        $this->settingRepository->load('queue.connections.redis.after_commit', false);
        //---------------------END QUEUES CONFIG--------------------///

        //----------FILESYSTEM SETTINGS------------------------------------------
        $this->settingRepository->load('filesystems.default', 'local');
        $this->settingRepository->load('filesystems.disks.local.driver', 'local');
        $this->settingRepository->load('filesystems.disks.local.root', storage_path('app'));
        $this->settingRepository->load('filesystems.disks.local.throw', false);

        $this->settingRepository->load('filesystems.disks.public.root', storage_path('app/public'));
        $this->settingRepository->load('filesystems.disks.public.url', config('app.url', null) . '/storage');
        $this->settingRepository->load('filesystems.disks.public.visibility', 'public');
        $this->settingRepository->load('filesystems.disks.public.throw', false);

        $this->settingRepository->load('filesystems.disks.s3.use_path_style_endpoint', false);
        $this->settingRepository->load('filesystems.disks.s3.throw', false);

        $this->settingRepository->load('filesystems.links.public', public_path('storage'));
        $this->settingRepository->load('filesystems.links.storage', storage_path('app/public'));


        //-------EMAIL SETTINGS -------------------------
        $this->settingRepository->load('mail.default', 'smtp');

        //$this->settingRepository->load('mail.mailers.smtp.transport', 'smtp');
        $this->settingRepository->load('mail.mailers.smtp.host', 'smtp.mailgun.org');
        $this->settingRepository->load('mail.mailers.smtp.port', 587);
        $this->settingRepository->load('mail.mailers.smtp.encryption', 'tls');
        $this->settingRepository->load('mail.from.address', 'hello@example.com');
        $this->settingRepository->load('mail.from.name', 'Example');

        //---------Setting services ---------------
        $this->settingRepository->load('services.mailgun.endpoint', 'api.mailgun.net');
        $this->settingRepository->load('services.mailgun.scheme', 'https');
        $this->settingRepository->load('services.captcha.driver', "hcaptcha");
        $this->settingRepository->load('services.captcha.enabled', false);
        $this->settingRepository->load('services.captcha.providers.turnstile.api', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
        $this->settingRepository->load('services.captcha.providers.hcaptcha.api', 'https://hcaptcha.com/siteverify');

        //System settings 
        $this->settingRepository->load('system.cookie_name', "oauth2_server");
        $this->settingRepository->load('system.verify_account_time', 5);
        $this->settingRepository->load('system.disable_create_user_by_command', false);
        $this->settingRepository->load('system.destroy_user_after', 30);
        $this->settingRepository->load('system.csp_enabled', false);
        $this->settingRepository->load('system.birthday.active', false);
        $this->settingRepository->load('system.birthday.limit', 18);
        $this->settingRepository->load('system.demo.enabled', false);

        //Session settings 
        $this->settingRepository->load('session.lifetime', 7200);
        $this->settingRepository->load('session.expire_on_close', false);
        $this->settingRepository->load('session.encrypt', false);
        $this->settingRepository->load('session.table', 'sessions');
        $this->settingRepository->load('session.cookie', 'oauth2_session');
        $this->settingRepository->load('session.xcsrf-token', 'oauth2_csrf');
        $this->settingRepository->load('session.path', '/');
        $this->settingRepository->load('session.secure', false);
        $this->settingRepository->load('session.http_only', true);
        $this->settingRepository->load('session.partitioned', false);
    }


    /**
     * Setting for laravel passport
     * @return void
     */
    public function getPassportSetting()
    {
        Passport::authorizationView('vendor.passport.authorize');
        Passport::loadKeysFrom(base_path('secrets/oauth'));

        //Cookies names
        Passport::cookie(config('system.cookie_name'));

        try {

            $scopes = config('openid.passport.tokens_can');

            $dbScopes = Cache::remember(
                'passport:scopes',
                now()->addDays(intval(config('cache.expires', 90))),
                function () {

                    return Scope::with('role')
                        ->where('active', true)
                        ->get()
                        ->mapWithKeys(function ($scope) {
                            return [$scope->gsr_id => $scope->role->description];
                        })
                        ->toArray();
                }
            );

            $scopes += $dbScopes;

            Passport::tokensCan($scopes);
        } catch (Throwable $th) {
            if ($th instanceof \RedisException) {
                $this->resetConfigKeys();
            }

            Log::error($th->getMessage(), $th->getTrace());
        }

        /**
         * Custom models for laravel passport
         */
        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
    }

    /**
     * Update
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $useModuele = (bool) $request->input('use_module', 1);
        $data = $request->except('_method', '_token', 'current_route', 'use_module');
        $route = Route::getRoutes()->getByName($request->current_route)?->action;
        $moduleConfigKey = null;
        dd($useModuele);
        if (isset($route['config_key']) && !empty($route['config_key']) && $useModuele) {
            $moduleConfigKey = $route['config_key'];
        }

        $data = $this->transformConfigRequest($data);
        $settings = [];

        foreach ($data as $key => $value) {

            $configKey = empty($moduleConfigKey) ? $key : "$moduleConfigKey.$key";

            $settings[$configKey] = $value;
        }

        $this->settingRepository->addMany($settings);

        //Set a pid to reset cache
        $this->resetConfigKeys();
    }

    /**
     * Transform request data when the project helper is available.
     * @param array $data
     * @return array
     */
    protected function transformConfigRequest(array $data): array
    {
        if (function_exists('transformConfigRequest')) {
            return transformConfigRequest($data);
        }

        return $data;
    }

    /**
     * Validate cache system from request
     * @param Request $request
     * @return void
     */
    public function validateCacheSystemFromRequest(Request $request): void
    {
        if ($request->has('database.redis')) {
            foreach (['default', 'cache'] as $type) {
                $this->checkRedisConnection(
                    $request->input("database.redis.$type.host"),
                    $request->input("database.redis.$type.port", 6379),
                    $request->input("database.redis.$type.password"),
                    $request->input("database.redis.$type.database", 0),
                    $type
                );
            }
        }
    }

    /**
     * Check redis connection
     * @param mixed $host
     * @param mixed $port
     * @param mixed $password
     * @param mixed $database
     * @param mixed $type
     * @throws ReportError
     * @return void
     */
    protected function checkRedisConnection($host, $port, $password, $database, $type): void
    {
        $client = new \Redis();

        $context = [
            'type' => $type,
            'host' => $host,
            'port' => (int) $port,
            'database' => (int) $database,
        ];

        try {
            // 1. Conexión
            if (!$client->connect($host, (int) $port, 2)) {
                Log::error("Redis connection failed", $context);

                throw new ReportError(
                    "Redis connection failed [$type] → {$host}:{$port}",
                    403
                );
            }

            // 2. Auth
            if (!empty($password)) {
                if (!$client->auth($password)) {
                    Log::error("Redis authentication failed", $context);

                    throw new ReportError(
                        "Redis authentication failed [$type] → {$host}:{$port}",
                        403
                    );
                }
            }

            // 3. Selection DB
            if (!$client->select((int) $database)) {
                Log::error("Redis database selection failed", $context);

                throw new ReportError(
                    "Redis database selection failed [$type] → DB: {$database}",
                    403
                );
            }

            if (!$client->ping()) {
                Log::error("Redis ping failed", $context);

                throw new ReportError(
                    "Redis ping failed [$type] → {$host}:{$port}",
                    403
                );
            }
        } catch (\Throwable $e) {
            Log::error("Redis exception", array_merge($context, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]));

            throw new ReportError(
                "Redis error [$type] → " . $e->getMessage(),
                403
            );
        } finally {
            try {
                $client->close();
            } catch (\Throwable $e) {
                Log::warning("Redis close failed", $context);
            }
        }
    }

    /**
     * Delete a module and all children.
     * @param string $key
     * @return void
     */
    public function deleteKeys(string $key)
    {
        $this->settingRepository->deleteKeys($key);
    }
}
