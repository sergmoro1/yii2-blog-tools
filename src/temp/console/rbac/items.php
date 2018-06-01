<?php
return [
    'index' => [
        'type' => 2,
    ],
    'create' => [
        'type' => 2,
    ],
    'update' => [
        'type' => 2,
    ],
    'delete' => [
        'type' => 2,
    ],
    'viewPost' => [
        'type' => 2,
    ],
    'createPost' => [
        'type' => 2,
    ],
    'changePostStatus' => [
        'type' => 2,
    ],
    'updateOwnPost' => [
        'type' => 2,
        'ruleName' => 'postModerator',
        'children' => [
            'update',
        ],
    ],
    'replyStranger' => [
        'type' => 2,
        'ruleName' => 'strangerComment',
    ],
    'replyOwnComment' => [
        'type' => 2,
        'ruleName' => 'ownComment',
        'children' => [
            'replyStranger',
        ],
    ],
    'updateOwnAnswer' => [
        'type' => 2,
        'ruleName' => 'ownAnswer',
        'children' => [
            'update',
        ],
    ],
    'updateOwnProfile' => [
        'type' => 2,
        'ruleName' => 'ownProfile',
        'children' => [
            'update',
        ],
    ],
    'gear' => [
        'type' => 2,
        'description' => 'Change Settings',
    ],
    'commentator' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'updateOwnProfile',
        ],
    ],
    'author' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'index',
            'createPost',
            'viewPost',
            'updateOwnPost',
            'replyStranger',
            'replyOwnComment',
            'updateOwnAnswer',
            'commentator',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'changePostStatus',
            'create',
            'update',
            'delete',
            'gear',
            'author',
        ],
    ],
];
