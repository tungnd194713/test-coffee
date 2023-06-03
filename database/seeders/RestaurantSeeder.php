<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $streets = [ "Hang Gai", "Hang Dao", "Hang Bong", "Hang Ma", "Hang Bac", "Hang Quat", "Hang Tre", "Nguyen Hue", "Trang Tien", "Hang Khay", "Hang Bai", "Hang Thiec", "Tran Phu", "Le Duẩn", "Hai Ba Trung", "Ba Trieu", "Ly Thuong Kiet", "Hang Dau", "Hang Be", "Hoang Dieu" ];
        $coffeeShops = [ "The Daily Grind", "Brewed Awakening", "Caffeine Fix", "Perk Me Up", "Bean There, Done That", "Mug Life", "Grounds for Joy", "Sip and Savor", "Espresso Lane", "Cup of Joe", "Steamy Beans", "Roast and Toast", "Wakey Wakey", "The Coffee House", "Jolt Java", "Aroma Junction", "Sip 'n' Smile", "The Bean Bag", "Cafe Mocha", "Java Joy", "Cuppa Co", "The Brew Crew", "Morning Buzz", "Perky Perks", "Bean Town", "Cafe de Licious", "The Roastery", "The Grind Spot", "Grounds Up", "Brew Hoo", "The Steamy Cup", "The Espresso Stop", "Bean Bliss", "Coffee Culture", "Cup O'Clock", "Mug Shot", "Sip Street", "Brewtiful", "Buzz Bean", "The Caffeine Lounge", "Coffee Corner", "Java Jive", "Savor the Flavor", "The Coffee Cartel", "Cupping Cabin", "Percolate Place", "The Grindhouse", "Steamy Grounds", "Mocha Magic", "Bean Barn", "Cafe Noir", "Brewed Perfection" ];
        $logos = [
            'https://images.unsplash.com/photo-1511667282954-8957078364a6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjQ2&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1513244608388-32427255be63?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjQ4&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1521834100799-d805ca040e94?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjUx&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1533630757306-cbadb934bcb1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjUz&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1514066558159-fc8c737ef259?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjU3&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1514066558159-fc8c737ef259?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjU3&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1521401292936-0a2129a30b1c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjYw&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1521401292936-0a2129a30b1c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MjYw&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1468730533502-216da872eab2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MzM1&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
            'https://images.unsplash.com/photo-1447933601403-0c6688de566e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8Y29mZmVlLHNob3B8fHx8fHwxNjg1Nzk2MzU5&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080',
        ];
        $data = [];
        for ($i = 0; $i < 50; $i++) {
            array_push($data, [
                'address' => rand(1, 999) . ' ' . $streets[rand(0, count($streets) - 1)],
                'district' => rand(1, 30),
                'name' => $coffeeShops[$i],
                'logo' => $logos[rand(0, count($logos) - 1)],
                'view' => rand(1, 100),
                'is_confirm' => 1,
                'total_star' => 3,
                'crowded_time' => null,
                'end_crowded_time' => null,
                'longitude' => rand(1050000, 1059999) / 10000,
                'latitude' => rand(210000, 219999) / 10000,
            ]);
        }
        DB::table('restaurants')->insert($data);
    }
}
