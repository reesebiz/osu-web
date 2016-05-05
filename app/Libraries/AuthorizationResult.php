<?php

/**
 *    Copyright 2015 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace App\Libraries;

use App\Exceptions\AuthorizationException;

class AuthorizationResult
{
    private $prefix;
    private $userId;
    private $keys;
    private $rawMessage;

    public function __construct($prefix = null, $user = null, $keys = null)
    {
        $this->prefix = $prefix;
        $this->keys = $keys;

        if ($user !== null) {
            $this->userId = $user->user_id;
        }
    }

    public function key()
    {
        if ($this->keys === null) {
            return;
        }

        return serialize([$this->userId, $this->keys]);
    }

    public function set($rawMessage)
    {
        $this->rawMessage = $rawMessage;

        return $this;
    }

    public function can()
    {
        return $this->rawMessage === null;
    }

    public function rawMessage()
    {
        if ($this->can()) {
            return;
        }

        $keys = ['authorization'];

        if (present($this->prefix)) {
            $keys[] = $this->prefix;
        }

        if ($this->rawMessage === '') {
            $keys[] = 'error';
        } else {
            $keys[] = $this->rawMessage;
        }

        return implode('.', $keys);
    }

    public function message()
    {
        if ($this->can()) {
            return;
        }

        return trans($this->rawMessage());
    }

    public function ensureCan()
    {
        if ($this->can()) {
            return;
        }

        throw new AuthorizationException($this->message());
    }
}
