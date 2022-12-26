<?php

use app\modules\bot\components\GroupRouteResolver;

$controllers = require __DIR__ . '/controllers.php';
$actions = require __DIR__ . '/actions.php';

$config = [
    'components' => [
        'commandRouteResolver' => [
            'class' => GroupRouteResolver::class,
            'rules' => [
                '/start(@<botname:[\w_]+[B|b]ot>)?( <message:.+>)?' => 'hello/index',
                '/help(@<botname:[\w_]+[B|b]ot>)?( <message:.+>)?' => 'hello/index',
                '/(fake_face|face)(@<botname:[\w_]+[B|b]ot>)?( <message:.+>)?' => 'my-fake-face/index',
                '/(fake_cat|cat)(@<botname:[\w_]+[B|b]ot>)?( <message:.+>)?' => 'my-fake-cat/index',
                '/(fake_art|art)(@<botname:[\w_]+[B|b]ot>)?( <message:.+>)?' => 'my-fake-art/index',
                '/(fake_horse|horse)(@<botname:[\w_]+[B|b]ot>)?( <message:.+>)?' => 'my-fake-horse/index',
                '/<controller:\w+>__<action:\w+>(@<botname:[\w_]+[B|b]ot>)?(\?<query:(&?\w+=[^&]*)*>)?( <message:.+>)?' => '<controller>/<action>',
                '/<controller:\w+>(@<botname:[\w_]+[B|b]ot>)?(\?<query:(&?\w+=[^&]*)*>)?( <message:.+>)?' => '<controller>/index',
            ],
            'controllers' => $controllers,
            'actions' => $actions,
        ],
    ],
];

return $config;
