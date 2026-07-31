<?php

namespace Core\User\Services;

use Illuminate\Http\Request;
use Core\User\Repositories\ScopeRepository;

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

class ScopeService
{

    /**
     * Scope repository
     * @var ScopeRepository
     */
    private $scopeRepository;

    /**
     * Construct
     * @param  ScopeRepository $scopeRepository
     */
    public function __construct(ScopeRepository $scopeRepository)
    {
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * Search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\User\Model\Scope>
     */
    public function search(Request $request)
    {
        // Create query
        $query = $this->scopeRepository->query();

        $query->when($request->filled('active'), fn($q) => $q->where('active', $request->active));
        $query->when($request->filled('public'), fn($q) => $q->where('public', $request->public));
        $query->when($request->filled('service_id'), fn($q) => $q->where('service_id', $request->service_id));
        $query->when($request->filled('role_id'), fn($q) => $q->where('role_id', $request->role_id));

        if ($request->filled('group_id')) {
            $query->whereHas(
                'service',
                function ($query) use ($request) {
                    $query->where('group_id', $request->input('group_id'));
                }
            );
        }

        // search by role name or slug
        if ($request->filled('role_name')) {
            $query->whereHas(
                'role',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) like ?",
                        ["%" . strtolower($request->role_name) . "%"]
                    );
                }
            );
        }

        // Search by service name or slug
        if ($request->filled('service_name')) {
            $query->whereHas(
                'service',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) like  ?",
                        ["%" . strtolower($request->service_name) . "%"]
                    );
                }
            );
        }

        return $query;
    }

    /**
     * Search for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\User\Model\Scope>
     */
    public function searchForUser(Request $request)
    {
        // Create query
        $query = $this->scopeRepository->query();

        $query->where('active', true);

        $query->where('public', false);

        // search by role name or slug
        if ($request->has('role_name')) {
            $query->whereHas(
                'role',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) like ?",
                        ["%" . strtolower($request->role) . "%"]
                    );
                }
            );
        }

        // Search by service name or slug
        if ($request->has('service_name')) {
            $query->whereHas(
                'service',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) like  ?",
                        ["%" . strtolower($request->service) . "%"]
                    );
                }
            );
        }

        return $query;
    }


    /**
     * Search scope for service
     * @param string $scope_id
     * @param string $service_id
     * @return \Core\User\Model\Scope|\stdClass|null
     */
    public function searchScopeByService(string $scope_id, string $service_id)
    {
        $query = $this->scopeRepository->query();

        // Search by scope id
        $query->where('id', $scope_id);

        // Search by service id
        $query->whereHas(
            'service',
            function ($query) use ($service_id) {
                $query->where('id', $service_id);
            }
        );

        return $query->first();
    }
}
