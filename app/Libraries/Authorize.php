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

use App\Libraries\AuthorizationResult as Result;
use App\Models\Forum\Authorize as ForumAuthorize;

class Authorize
{
    private $cache = [];

    public function doCheckUser($user, $ability, $args)
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        $function = "check{$ability}";

        $result = call_user_func_array(
            [$this, $function], array_merge([$user], $args)
        );

        $this->setCache($result);

        return $result;
    }

    public function hasCache($result)
    {
        return $result->key() !== null && isset($this->cache[$result->key()]);
    }

    public function getCache($result)
    {
        return $this->cache[$result->key()];
    }

    public function setCache($result)
    {
        if ($result->key() === null) {
            return;
        }

        $this->cache[$result->key()] = $result;
    }

    public function checkForumView($user, $forum)
    {
        $result = new Result('forum.view', $user, $forum->forum_id);

        if ($this->hasCache($result)) {
            return $this->getCache($result);
        }

        if ($user !== null && $user->isAdmin()) {
            return $result;
        }

        if ($forum->categoryId() === config('osu.forum.admin_forum_id')) {
            return $result->set('admin_only');
        }

        return $result;
    }

    public function checkForumPostDelete($user, $post, $positionCheck = true, $position = null, $topicPostsCount = null)
    {
        $result = new Result('forum.post.delete', $user, [$post->post_id, $positionCheck]);

        if ($this->hasCache($result)) {
            return $this->getCache($result);
        }

        if ($user === null) {
            return $result->set('require_login');
        }

        if ($user->isAdmin()) {
            return $result;
        }

        if ($post->poster_id !== $user->user_id) {
            return $result->set('not_post_owner');
        }

        if ($positionCheck === false) {
            return $result;
        }

        if ($position === null) {
            $position = $post->postPosition;
        }

        if ($topicPostsCount === null) {
            $topicPostsCount = $post->topic->postsCount();
        }

        if ($position !== $topicPostsCount) {
            return $result->set('can_only_delete_last_post');
        }

        return $result;
    }

    public function checkForumPostEdit($user, $post)
    {
        $result = new Result('forum.post.edit', $user, $post->post_id);

        if ($this->hasCache($result)) {
            return $this->getCache($result);
        }

        if ($user === null) {
            return $result->set('require_login');
        }

        if ($user->isAdmin()) {
            return $result;
        }

        if ($post->poster_id !== $user->user_id) {
            return $result->set('not_post_owner');
        }

        if ($post->post_edit_locked) {
            return $result->set('locked');
        }

        return $result;
    }

    public function checkForumTopicEdit($user, $topic)
    {
        return $this->checkForumPostEdit($user, $topic->posts()->first());
    }

    public function checkForumTopicReply($user, $topic)
    {
        $result = new Result('forum.topic.reply', $user, $topic->topic_id);

        if ($this->hasCache($result)) {
            return $this->getCache($result);
        }

        if (!ForumAuthorize::canPost($user, $topic->forum, $topic)) {
            return $result->set('can_not_post');
        }

        return $result;
    }

    public function checkForumTopicStore($user, $forum)
    {
        $result = new Result('forum.topic.store', $user, $forum->forum_id);

        if ($this->hasCache($result)) {
            return $this->getCache($result);
        }

        if ($forum->forum_type === 1) {
            return $result;
        }

        return $result->set('closed');
    }

    public function checkForumTopicCoverEdit($user, $cover)
    {
        $result = new Result('forum.topic_cover.edit', $user, $cover->id);

        if ($this->hasCache($result)) {
            return $this->getCache($result);
        }

        if ($cover->topic !== null) {
            return $this->checkForumTopicEdit($user, $cover->topic);
        }

        if ($user === null) {
            return $result->set('require_login');
        }

        if ($user->isAdmin()) {
            return $result;
        }

        if ($cover->owner() === null) {
            return $result->set('uneditable');
        }

        if ($cover->owner()->user_id !== $user->user_id) {
            return $result->set('owner_only');
        }

        return $result;
    }
}
