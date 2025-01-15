<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationType::create(['name' => 'comments']);
        NotificationType::create(['name' => 'likes']);
        NotificationType::create(['name' => 'follow']);
    }
}
