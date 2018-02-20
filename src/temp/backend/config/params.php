<?php
return [
    'before_web' => 'backend',
    'adminEmail' => 'admin@vorst.ru',
    'postsPerPage' => 20,
    'recordsPerPage' => 20,
    'fileSize' => ['max' => 5],
    'slogan' => 'Websites development',
    'sidebar' => [
        'post' => ['url' => 'blog/post/index', 'caption' => 'Posts', 'icon' => 'newspaper-o'],
        'rubric' => ['url' => 'blog/rubric/index', 'caption' => 'Rubrics', 'icon' => 'list-ul'],
        'comment' => ['url' => 'blog/comment/index', 'caption' => 'Comments', 'icon' => 'comments'],
        'tag' => ['url' => 'blog/tag/index', 'caption' => 'Tags', 'icon' => 'tags'],
        'author' => ['url' => 'blog/author/index', 'caption' => 'Authors', 'icon' => 'user'],
        'meta' => ['url' => 'blog/meta/index', 'module' => 1, 'caption' => 'Meta', 'icon' => 'search'],
        'user' => ['url' => 'user/user/index', 'caption' => 'Users', 'icon' => 'user-secret', 'admin' => true],
    ],
];
