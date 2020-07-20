<?php

/**
 * eg: 运行此命令增加菜单 php bin/hyperf.php permission:update
 * TODO: 三级菜单例子
 *
 */

return [
    ['name' => '/users', 'display_name' => '用户管理', 'url' => '', 'parent_id' => '', 'child' =>
        [
            ['name' => '/users/get', 'display_name' => '添加用户', 'url' => '/user', 'parent_id' => '/users'],
            ['name' => '/users/post', 'display_name' => '用户更新', 'url' => '/user', 'parent_id' => '/users'],
        ]
    ],
];
