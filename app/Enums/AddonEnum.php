<?php

namespace App\Enums;

enum AddonEnum: string
{
    use EnumToArray;

    case Crew = 'CREWS';
    case OutputPrint = 'OUTPUT_AND_PRINT';
    case Shooting = 'SHOOTING';
    case Photobooth = 'PHOTOBOOTH';
}
