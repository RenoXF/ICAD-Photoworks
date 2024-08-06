<?php

namespace Database\Seeders;

use App\Enums\AddonEnum;
use App\Models\Addon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crews = [
            [
                'type' => AddonEnum::Crew,
                'title' => 'Assistant for bride',
                'price' => 500_000,
            ],
            [
                'type' => AddonEnum::Crew,
                'title' => 'Director',
                'price' => 500_000,
            ],
            [
                'type' => AddonEnum::Crew,
                'title' => 'Photographer',
                'price' => 1_000_000,
            ],
            [
                'type' => AddonEnum::Crew,
                'title' => 'Videographer',
                'price' => 1_000_000,
            ],
            [
                'type' => AddonEnum::Crew,
                'title' => 'Drone Pilot',
                'price' => 1_000_000,
            ],
            [
                'type' => AddonEnum::Crew,
                'title' => 'Photographer by owner',
                'price' => 2_000_000,
            ],
        ];

        $shootings = [
            [
                'type' => AddonEnum::Shooting,
                'title' => 'LCD TV 40"',
                'price' => 500_000,
            ],
            [
                'type' => AddonEnum::Shooting,
                'title' => 'LCD TV 50"',
                'price' => 750_000,
            ],
            [
                'type' => AddonEnum::Shooting,
                'title' => 'LCD TV 60"',
                'price' => 1_000_000,
            ],
            [
                'type' => AddonEnum::Shooting,
                'title' => 'LCD TV 70"',
                'price' => 1_500_000,
            ],
            [
                'type' => AddonEnum::Shooting,
                'title' => 'Livestreaming youtube',
                'price' => 1_000_000,
            ],
        ];


        $photobooth = [
            [
                'type' => AddonEnum::Photobooth,
                'title' => 'Print photobooth (unlimited prints, customized design) + online access & instant sharing',
                'price' => 1_250_000,
            ],
            [
                'type' => AddonEnum::Photobooth,
                'title' => 'Glambooth + online access & instant sharing',
                'price' => 1_750_000,
            ],
            [
                'type' => AddonEnum::Photobooth,
                'title' => '180 photobooth (8 cameras) + online access & instant sharing',
                'price' => 1_550_000,
            ],
        ];

        $outputPrints = [
            [
                'type' => AddonEnum::OutputPrint,
                'title' => 'Flashdisk 16GB',
                'price' => 80_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => 'Sameday edit photo',
                'price' => 750_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => 'Sameday edit video',
                'price' => 1_000_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '4 R print',
                'price' => 5_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '10 RL print',
                'price' => 15_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '12 R print with doff laminating & minimalist frame',
                'price' => 100_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '16 R print with doff laminating & minimalist frame',
                'price' => 150_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '22 R print with doff laminating & minimalist frame',
                'price' => 200_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '12 R print with doff laminating & exclusive frame',
                'price' => 200_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '16 R print with doff laminating & exclusive frame',
                'price' => 300_000,
            ],
            [
                'type' => AddonEnum::OutputPrint,
                'title' => '22 R print with doff laminating & exclusive frame',
                'price' => 450_000,
            ],

        ];

        foreach (array_merge($crews, $shootings, $photobooth, $outputPrints) as $addon) {
            Addon::create($addon);
        }
    }
}
