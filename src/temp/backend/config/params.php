<?php
$sidebar = array_merge(
    require(__DIR__ . '/../../vendor/sergmoro1/yii2-blog-tools/src/config/sidebar.php'),
    require(__DIR__ . '/../../vendor/sergmoro1/yii2-user/src/config/sidebar.php'),
    require(__DIR__ . '/../../vendor/sergmoro1/yii2-lookup/src/config/sidebar.php')
);

$icons = array_merge(
    require(__DIR__ . '/../../vendor/sergmoro1/yii2-blog-tools/src/config/icons.php')
);

$dropdown = array_merge(
    require(__DIR__ . '/../../vendor/sergmoro1/yii2-blog-tools/src/config/dropdown.php')
);

return [
  'before_web' => 'backend',
  'adminEmail' => 'admin@vorst.ru',
  'postsPerPage' => 20,
  'recordsPerPage' => 20,
  'fileSize' => ['max' => 5],
  'slogan' => 'Websites development',
  'sidebar' => $sidebar,
  'icons' => $icons,
  'dropdown' => $dropdown,
];
