<?php

Kirby::plugin('pwaldhauer/quick-add', [
    'sections' => [
        'quickadd' => require_once __DIR__ . '/sections/quick-add.php',
        'quickaddimage' => require_once __DIR__ . '/sections/quick-add-image.php',
    ],
]);
