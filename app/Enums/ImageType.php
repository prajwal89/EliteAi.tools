<?php

namespace App\Enums;

enum ImageType: string
{
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case BMP = 'image/bmp';
    case WEBP = 'image/webp';
}
