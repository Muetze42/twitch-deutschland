<?php

return [
    'images' => [
        'imagify-api-key'      => env('IMAGIFY_API_KEY'),
        'tinypng-api-key'      => env('TINYPNG_API_KEY'),
        'spatie-media-library' => true,
        'webp-convert'         => true,
        'paths' => [
            storage_path('app/assets'),
        ],
    ],
];
