<?php

namespace Foxkit\Blog\Event;

use Foxkit\Blog\Model\Post;
use Foxkit\Comment\Model\Comment;
use Foxkit\Event\EventSubscriberInterface;

class PostListener implements EventSubscriberInterface
{
    public function onCommentChange($event, Comment $comment)
    {
        Post::updateCommentInfo($comment->post_id);
    }

    public function onRoleDelete($event, $role)
    {
        Post::removeRole($role);
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'model.comment.saved' => 'onCommentChange',
            'model.comment.deleted' => 'onCommentChange',
            'model.role.deleted' => 'onRoleDelete'
        ];
    }
}
