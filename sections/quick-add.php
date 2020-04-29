<?php

return [
    'props' => [

        'headline' => function ($headline = '') {
            return $headline;
        },

        'target' => function ($target = '') {
            return $target;
        },

        'defaultTemplate' => function ($defaultTemplate = '') {
            return $defaultTemplate;
        },

        'templates' => function ($links = []) {
            return $links;
        }
    ]
];