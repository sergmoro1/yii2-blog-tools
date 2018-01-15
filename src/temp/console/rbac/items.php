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
        'children' => [
            'create',
        ],
    ],
    'changePostStatus' => [
        'type' => 2,
    ],
    'viewFund' => [
        'type' => 2,
    ],
    'updateOwnPost' => [
        'type' => 2,
        'ruleName' => 'postAuthor',
        'children' => [
            'update',
        ],
    ],
    'replyComment' => [
        'type' => 2,
    ],
    'replyOwnComment' => [
        'type' => 2,
        'ruleName' => 'ownComment',
        'children' => [
            'replyComment',
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
    'author' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'index',
            'createPost',
            'viewPost',
            'updateOwnPost',
            'replyOwnComment',
            'updateOwnAnswer',
            'updateOwnProfile',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'replyComment',
            'changePostStatus',
            'viewFund',
            'create',
            'update',
            'delete',
            'gear',
            'author',
        ],
    ],
];
