<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $laravelConference = Event::create([
            'name' => 'Laravel Conference',
            'location' => 'Paris, FR',
            'description' => 'Big Laravel Conference',
            'starts_at' => now()->addDays(5)->setTime(15, 0)
        ]);

        $djangoConference = Event::create([
            'name' => 'Django Conference',
            'location' => 'Quebec, CA',
            'description' => 'Big Django Conference',
            'starts_at' => now()->addMonth(1)->setTime(10, 45)
        ]);

        $symfonyMeetup = Event::create([
            'name' => 'Symfony Meetup',
            'location' => 'California, US',
            'description' => 'Big Symfony Meetup',
            'starts_at' => now()->addMonth(2)->setTime(18, 30)
        ]);

        $laravelConference->tickets()->createMany([
            [
                'name' => 'Early Bird',
                'quantity' => 200,
                'price' => 9900
            ],
            [
                'name' => 'VIP',
                'quantity' => 100,
                'price' => 20000
            ],
        ]);

        $djangoConference->tickets()->createMany([
            [
                'name' => 'SIMPLE',
                'quantity' => 150,
                'price' => 1000
            ],
            [
                'name' => 'VIP',
                'quantity' => 50,
                'price' => 2599
            ],
            [
                'name' => 'SUPER VIP',
                'quantity' => 50,
                'price' => 30000
            ],
        ]);

        $symfonyMeetup->tickets()->createMany([
            [
                'name' => 'Free',
                'quantity' => 200,
                'price' => 0
            ],
            [
                'name' => 'VIP',
                'quantity' => 100,
                'price' => 3000
            ],
        ]);
    }
}
