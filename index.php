<?php

use Foxkit\Blog\Content\ReadmorePlugin;
use Foxkit\Blog\Event\PostListener;
use Foxkit\Blog\Event\RouteListener;

return [
    'name' => 'blog',
    'autoload' => [
        'Foxkit\\Blog\\' => 'src'
    ],
    'nodes' => [
        'blog' => [
            'name' => '@blog',
            'label' => 'Blog',
            'controller' => 'Foxkit\\Blog\\Controller\\SiteController',
            'protected' => true,
            'frontpage' => true
        ]
    ],
    'routes' => [
        '/blog' => [
            'name' => '@blog',
            'controller' => 'Foxkit\\Blog\\Controller\\BlogController'
        ],
        '/api/blog' => [
            'name' => '@blog/api',
            'controller' => [
                'Foxkit\\Blog\\Controller\\PostApiController',
                'Foxkit\\Blog\\Controller\\CommentApiController'
            ]
        ]
    ],
    'permissions' => [
        'blog: manage own posts' => [
            'title' => 'Manage own posts',
            'description' => 'Create, edit, delete and publish posts of their own'
        ],
        'blog: manage all posts' => [
            'title' => 'Manage all posts',
            'description' => 'Create, edit, delete and publish posts by all users'
        ],
        'blog: manage comments' => [
            'title' => 'Manage comments',
            'description' => 'Approve, edit and delete comments'
        ],
        'blog: post comments' => [
            'title' => 'Post comments',
            'description' => 'Allowed to write comments on the site'
        ],
        'blog: skip comment approval' => [
            'title' => 'Skip comment approval',
            'description' => 'User can write comments without admin approval'
        ],
        'blog: comment approval required once' => [
            'title' => 'Comment approval required only once',
            'description' => 'First comment needs to be approved, later comments are approved automatically'
        ],
        'blog: skip comment min idle' => [
            'title' => 'Skip comment minimum idle time',
            'description' => 'User can write multiple comments without having to wait in between'
        ]
    ],
    'menu' => [
        'blog' => [
            'label' => 'Blog',
            'icon' => 'blog:icon.svg',
            'url' => '@blog/post',
            'active' => '@blog/post*',
            'access' => 'blog: manage own posts || blog: manage all posts || blog: manage comments || system: access settings',
            'priority' => 110
        ],
        'blog: posts' => [
            'label' => 'Posts',
            'parent' => 'blog',
            'url' => '@blog/post',
            'active' => '@blog/post*',
            'access' => 'blog: manage own posts || blog: manage all posts'
        ],
        'blog: comments' => [
            'label' => 'Comments',
            'parent' => 'blog',
            'url' => '@blog/comment',
            'active' => '@blog/comment*',
            'access' => 'blog: manage comments'
        ],
        'blog: settings' => [
            'label' => 'Settings',
            'parent' => 'blog',
            'url' => '@blog/settings',
            'active' => '@blog/settings*',
            'access' => 'system: access settings'
        ]
    ],
    'settings' => '@blog/settings',
    'config' => [
        'comments' => [
            'autoclose' => false,
            'autoclose_days' => 14,
            'blacklist' => '',
            'comments_per_page' => 20,
            'gravatar' => true,
            'max_depth' => 5,
            'maxlinks' => 2,
            'minidle' => 120,
            'nested' => true,
            'notifications' => 'always',
            'order' => 'ASC',
            'replymail' => true,
            'require_email' => true
        ],
        'posts' => [
            'posts_per_page' => 20,
            'comments_enabled' => true,
            'markdown_enabled' => true
        ],
        'permalink' => [
            'type' => '',
            'custom' => '{slug}'
        ],
        'feed' => [
            'type' => 'rss2',
            'limit' => 20
        ]
    ],
    'events' => [
        'boot' => function ($event, $app) {
            $app->subscribe(
                new RouteListener,
                new PostListener(),
                new ReadmorePlugin
            );
        },
        'view.scripts' => function ($event, $scripts) {
            $scripts->register('link-blog', 'blog:app/bundle/link-blog.js', '~panel-link');
            $scripts->register('post-meta', 'blog:app/bundle/post-meta.js', '~post-edit');
        }
    ]
];
