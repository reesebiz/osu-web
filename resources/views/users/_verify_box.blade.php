{{--
    Copyright 2015-2016 ppy Pty. Ltd.

    This file is part of osu!web. osu!web is distributed with the hope of
    attracting more community contributions to the core ecosystem of osu!.

    osu!web is free software: you can redistribute it and/or modify
    it under the terms of the Affero GNU General Public License version 3
    as published by the Free Software Foundation.

    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
--}}
<div class="user-verification">
    <h1 class="user-verification__row user-verification__row--title">
        {{ trans('user_verification.box.title') }}
    </h1>

    <p class="user-verification__row user-verification__row--info">
        {!! trans('user_verification.box.sent', ['mail' => '<strong>'.obscure_email($email).'</strong>']) !!}
    </p>

    <div class="user-verification__row user-verification__row--key">
        <input
            data-verification-key-length="{{ config('osu.user.verification_key_length_hex') }}"
            class="user-verification__key js-user-verification--input modal-af"
        />
        <div class="user-verification__errors js-user-verification--error" data-visibility="hidden"></div>

        <div class="user-verification__errors js-user-verification--verifying" data-visibility="hidden">
            <i class="fa fa-spinner fa-pulse fa-fw"></i>
            {{ trans('user_verification.box.verifying') }}
        </div>
    </div>

    <p class="user-verification__row user-verification__row--info">
        {{ trans('user_verification.box.info.check_spam') }}
    </p>

    <p class="user-verification__row user-verification__row--info">
        {!! trans('user_verification.box.info.recover', [
            'link' => link_to(
                config('osu.urls.user.recover'),
                trans('user_verification.box.info.recover_link'),
                ['class' => 'user-verification__link']
            ),
        ]) !!}
    </p>
</div>
