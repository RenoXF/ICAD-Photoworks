<?php

namespace App\Enums;

enum ProductType: string
{
    use EnumToArray;

    case Engagement = 'ENGAGEMENT';
    case Ceremony = 'CEREMONY';
    case WeddingPhoto = 'WEDDING_PHOTO';
    case WeddingCinematic = 'WEDDING_CINEMATIC';
    case WeddingBundling = 'WEDDING_BUNDLING';
    case AllInWedding = 'ALL_IN_WEDDING';
    case WeddingShooting = 'WEDDING_SHOOTING';
}
