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

use Core\User\Model\Role;
use Illuminate\Http\Request;
use Core\User\Repositories\RoleRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

class RoleService
{
    /**
     * Role repository
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * Construct
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Role>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->roleRepository->query();

        $query->when(
            $request->filled('name'),
            fn($q) => $q->whereRaw(
                "LOWER(name) like ?",
                ["%" . strtolower($request->name) . "%"]
            )
        );

        $query->when($request->filled('system'), fn($q) => $q->where('system', $request->system));

        $query->when(
            $request->filled('order_type'),
            function ($q) use ($request) {

                $q->orderBy($request->filled('order_by') ? $request->order_by : 'created_at', $request->order_type);
            }
        );


        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return Role
     */
    public function create(array $data)
    {
        return $this->roleRepository->create([
            'name' => $data['name'],
            'slug' => $data['name'],
            'description' => $data['description'],
            'system' => $data['system'] ?? false
        ]);
    }


    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return Role
     */
    public function update(string $id, array $data)
    {
        $model = $this->roleRepository->find($id);

        if (empty($model)) {
            new ReportError(
                __("This resource can not be found"),
                404
            );
        }

        if ((bool) $model->system) {
            new ReportError(
                __("This is a system role and cannot be modified. If you believe this is an error, please contact the administrator."),
                403
            );
        }

        return $this->roleRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }


    /**
     * Delete resource
     * @param string $id
     * @return Role
     */
    public function delete(string $id)
    {
        $model = $this->roleRepository->find($id);

        if ((bool) $model->system) {
            new ReportError(
                __("This is a system role and cannot be deleted. If you believe this is an error, please contact the administrator."),
                403
            );
        }

        if ($model->scopes()->count() > 0) {
            new ReportError(__("This action cannot be completed because this role is currently assigned to one or more scopes and cannot be deleted."), 403);
        }

        $model->delete();

        //send event
        return $model;
    }
}
