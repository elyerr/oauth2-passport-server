<?php

namespace Core\User\Services;

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

use App\Support\CacheVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Core\User\Repositories\ServiceRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Database\UniqueConstraintViolationException;

class ServiceService
{
    /**
     * Construct
     * @param ServiceRepository $serviceRepository
     * @param ScopeService $scopeService
     */
    public function __construct(
        protected ServiceRepository $serviceRepository,
        protected ScopeService $scopeService
    ) {
    }

    /**
     * Summary of search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\User\Model\Service>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->serviceRepository->query();

        $query->when($request->filled('group_id'), fn($q) => $q->where('group_id', $request->input('group_id')));

        if ($request->filled('group')) {
            $query->whereHas(
                'group',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ['%' . strtolower($request->group) . '%']
                    );
                }
            );
        }

        if ($request->filled('name')) {
            $query->whereRaw(
                "LOWER(name) like ?",
                ["%" . strtolower($request->name) . "%"]
            );
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->whereRaw(
                'LOWER(visibility) like ?',
                ["%" . $request->visibility . "%"]
            );
        }


        $query->orderByDesc('updated_at');

        return $query;
    }

    /**
     * Search for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\User\Model\Service>
     */
    public function searchForGuest(Request $request)
    {
        // Prepare query
        $query = $this->serviceRepository->query();

        $query->where('visibility', 'public');

        if ($request->filled('group')) {
            $query->whereHas(
                'group',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ['%' . strtolower($request->group) . '%']
                    );
                }
            );
        }

        return $query;
    }

    /**
     * Find service
     * @param string $id
     * @return \Core\User\Model\Service
     */
    public function find(string $id)
    {
        return $this->serviceRepository->find($id);
    }

    /**
     * Find by slug
     * @param string $slug
     * @return \Core\User\Model\Service|\stdClass|null
     */
    public function findBySlug(string $slug)
    {
        return $this->serviceRepository->query()->where('slug', $slug)->first();
    }

    /**
     * Create new resource
     * @param array $data
     * @throws ReportError
     * @return \Core\User\Model\Service
     */
    public function create(array $data)
    {
        try {
            return $this->serviceRepository->create([
                'name' => $data['name'],
                'slug' => $data['name'],
                'description' => $data['description'],
                'group_id' => $data['group_id'],
                'system' => $data['system'] ?? false,
                'visibility' => $data['visibility']
            ]);
        } catch (UniqueConstraintViolationException $th) {
            throw new ReportError(__("This service cannot be registered, as it is already associated with this group."), 400);
        }
    }

    /**
     * Update service
     * @param string $id
     * @param array $data
     * @return \Core\User\Model\Service
     */
    public function update(string $id, array $data)
    {
        $model = $this->serviceRepository->find($id);

        if (empty($model)) {
            new ReportError(__("This resource can not be found"), 404);
        }

        if ((bool) $model->system) {
            new ReportError(
                __("This is a system service and cannot be modified. If you believe this is an error, please contact the administrator."),
                403
            );
        }

        return $this->serviceRepository->update($id, [
            'description' => $data['description'],
            'name' => $data['name'],
            'visibility' => $data['visibility']
        ]);
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return \Core\User\Model\Service
     */
    public function delete(string $id)
    {
        $model = $this->serviceRepository->find($id);

        if ((bool) $model->system) {
            new ReportError(__("This action cannot be completed because this service is a system service and cannot be deleted."), 403);
        }

        if (count($model->scopes) > 0) {
            new ReportError(__("This action can't be done"), 403);
        }

        $model->delete();

        return $model;
    }

    /**
     * Search scope for service
     * @param string $service_id
     * @return array
     */
    public function searchScopes(string $service_id)
    {
        $model = $this->serviceRepository->find($service_id);

        return $model->scopes;
    }

    /**
     * Assign or update scopes for service
     * @param string $service_id
     * @param array $data
     */
    public function assignOrUpdateScopes(string $service_id, array $data)
    {
        $model = $this->serviceRepository->find($service_id)
            ->scopes()->updateOrCreate(
                [
                    'role_id' => $data['role_id'],
                ],
                [
                    'role_id' => $data['role_id'],
                    'public' => $data['public'] ?? false,
                    'active' => $data['active'] ?? false,
                    'api_key' => $data['api_key'] ?? false,
                    'web' => $data['web'] ?? false,
                ]
            );

        Cache::increment(CacheVersions::SCOPES);
        return $model;
    }

    /**
     * Revoke scope for service
     * @param string $service_id
     * @param string $scope_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return TModel|TValue|null
     */
    public function revokeScope(string $service_id, string $scope_id)
    {
        $scope = $this->scopeService->searchScopeByService($scope_id, $service_id);

        if (empty($scope)) {
            throw new ReportError(__("This action is invalid"), 403);
        }

        try {
            $scope->delete();
        } catch (\Throwable $th) {
            throw new ReportError(__("This resource cannot be deleted because it is being used by another resource."), 403);
        }

        Cache::increment(CacheVersions::SCOPES);

        return $scope;
    }
}
