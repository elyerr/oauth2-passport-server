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

use Core\User\Model\User;
use Core\User\Model\UserScope;

class UserRepository
{

    /**
     * User model
     * @var User
     */
    private $model;

    /**
     * User Scope model
     * @var UserScope
     */
    private $userScope;


    /**
     * Construct
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\UserScope $userScope
     */
    public function __construct(User $user, UserScope $userScope)
    {
        $this->model = $user;
        $this->userScope = $userScope;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<User>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->withTrashed();

        $query->with([
            'userScopes',
            'groups'
        ]);

        return $query;
    }

    /**
     * Create resource
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search user by id
     * @param string $id
     * @return User
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find user by email
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email)
    {
        return $this->query()->where('email', $email)->first();
    }

    /**
     * Update by id
     * @param string $id
     * @param array $data
     * @return User
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        if (!empty($model->email_verified_at)) {
            unset($data['email_verified_at']);
        }

        $model->update($data);

        return $model;
    }

    /**
     * Update by email
     * @param string $email
     * @param array $data
     * @return User
     */
    public function updateByEmail(string $email, array $data)
    {
        $model = $this->find($email);

        $model->update($data);

        return $model;
    }

    /**
     * User Scope query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<UserScope>
     */
    public function scopeQuery()
    {
        $query = $this->userScope->query();

        $query->with([
            'scope.service.group',
            'scope',
            'scope.service',
            'scope.role',
            'user'
        ]);

        return $query;
    }
}
