{{--
    Copyright 2015 ppy Pty. Ltd.

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
@extends("master")

@section("content")
    <div class="osu-layout__row osu-layout__row--page-compact">
        <div class="page-header-nav">
            <ol class=" forum-header-breadcrumb forum-header-breadcrumb--large forum-colour__bg-link--category-integrated-bottom-line-application">
                <li class="forum-header-breadcrumb__item">
                    <a href="http://localhost:8080/forum" class="forum-header-breadcrumb__link">osu!community <span class="forum-header-breadcrumb__link-stripe u-current-forum-bg"></span></a>
                </li>
                <li class="forum-header-breadcrumb__item">
                    <a href="http://localhost:8080/forum#forum-3" class="forum-header-breadcrumb__link">Integrated bottom-line application <span class="forum-header-breadcrumb__link-stripe u-current-forum-bg"></span></a>
                </li>
                <li class="forum-header-breadcrumb__item">
                    <a href="http://localhost:8080/forum/6" class="forum-header-breadcrumb__link forum-header-breadcrumb__link--is-active">Sharable foreground implementation<span class="forum-header-breadcrumb__link-stripe u-current-forum-bg"></span></a>
                </li>
            </ol>
        </div>
        <div
            class="forum-category-header forum-colour__bg--category-integrated-bottom-line-application forum-category-header--forum js-forum-cover--header"
            style="{{ isset($cover['data']['fileUrl']) === true ? "background-image: url('{$cover['data']['fileUrl']}');" : '' }}">
            <div class="forum-category-header__loading js-forum-cover--loading">
                @include('objects._spinner')
            </div>

            <div class="forum-category-header__titles">
                <h1 class="forum-category-header__title forum-category-header__title--forum">
                    <a class="link--white link--no-underline" href="#">
                        Testing123
                    </a>
                </h1>
            </div>

            @if (Auth::check() === true && Auth::user()->isAdmin() === true)
                @include('forum._cover')
            @endif
        </div>
    </div>

    <div class="osu-layout__row" id="download-button">
        <a href="http://m1.ppy.sh/r/osu!install.exe">
            <img class="round" src="/images/download-button.png" alt="osu! online installer" />
        </a>
    </div>

    <div class="osu-layout__row osu-layout__row--page" id="download-steps">
        <div>
            <h1>Step 1</h1>
            <p>Download the osu! game client</p>
        </div>
        <div>
            <h1>Step 2</h1>
            <p>Create an osu! player account</p>
        </div>
        <div>
            <h1>Step 3</h1>
            <p>???</p>
        </div>
    </div>

    <div class="osu-layout__row osu-layout__row--page" id="download-guides">
        <div class="explanation">
            <h4>Learn more?</h4>

            <p>
                Check out the <a href="https://www.youtube.com/user/osuacademy/">osu!academy YouTube Channel</a> for up-to-date tutorials and tips on how to get the most out of osu!
            </p>
        </div>

        <div class="embed-responsive embed-responsive-16by9">
            <iframe src="https://www.youtube.com/embed/videoseries?list=PLmWVQsxi34bMYwAawZtzuptfMmszUa_tl" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
@stop
