<?php

return [
    'images' => [
        'imagify' => [
            'api-key'   => env('IMAGIFY_API_KEY'),
            'level'     => 'aggressive',  // Options: normal, aggressive, ultra
            'keep_exif' => false,
        ],
        'tinypng' => [
            'api-key'   => env('TINYPNG_API_KEY'),
        ],
        'spatie-media-library' => true,
        'webp-convert-quality' => 90,   // null to disable
        'paths' => [
            storage_path('app/assets'),
        ],
    ],
];
