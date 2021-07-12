<?php

return [

    'driver' => env('IMAGE_DRIVER', 'gd'),

    'thumb' => env('IMAGE_THUMB'),
    'thumb_width' => env('IMAGE_THUMB_WIDTH'),
    'thumb_height' => env('IMAGE_THUMB_HEIGHT'),
    'water' => env('IMAGE_WATER'),
    'water_alpha' => env('IMAGE_WATER_ALPHA', 80),
    'water_image' => env('IMAGE_WATER_IMAGE'),

];
