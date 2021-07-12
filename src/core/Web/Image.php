<?php

namespace Duxravel\Core\Web;

use Duxravel\Core\Controllers\Controller;

class Image extends Controller
{

    public function placeholder($width, $height, $text) {
        $html = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '" preserveAspectRatio="none"><rect width="' . $width . '" height="' . $height . '" fill="#f4f6fa"/><text text-anchor="middle" x="' . round($width / 2, 0) . '" y="' . round($height / 2, 0) . '" style="fill:#aaa;font-size:1.6rem;font-family:Arial,Helvetica,sans-serif;dominant-baseline:central">' . $text . '</text></svg>';
        return response($html, 200)->header('Content-Type', 'image/svg+xml');
    }
}
