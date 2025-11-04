<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\status;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Completed',
            'Pending',
            'Shipped',
            'Ready',
            'Delivered',
            'Cancelled'
        ];

        foreach ($statuses as $status) {
            status::create([
                'status_name' => $status,
            ]);
        }
    }
}
