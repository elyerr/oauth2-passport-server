<?php
namespace Core\User\Repositories;

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

use Core\User\Model\Service;

class ServiceRepository
{
    /**
     * Model
     * @var Service
     */
    public $model;

    /**
     * Construct
     * @param  Service $service
     */
    public function __construct(Service $service)
    {
        $this->model = $service;
    }

    /**
     * Query
     * @return  \Illuminate\Database\Eloquent\Builder<Service>
     */
    public function query()
    {
        $query = $this->model->query();
        $query->with([
            'group',
            'scopes',
            'scopes.role'
        ]);
        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return Service 
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find resource
     * @param string $id
     * @return Service
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Update
     * @param string $id
     * @param array $data
     * @return Service
     */
    public function update(string $id, array $data)
    {
        $service = $this->find($id);

        $service->update($data);

        return $service;
    }
}
