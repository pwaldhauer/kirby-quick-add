<?php

Kirby::plugin('knuspermagier/quick-add', [
    'sections' => [
        'quickadd' => require_once __DIR__ . '/sections/quick-add.php',
    ],
]);