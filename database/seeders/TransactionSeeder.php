<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Transaction::flushEventListeners(); // to not trigger the event listeners whene seeding the data
        \App\Models\Transaction::factory(1000)->create();
    }
}
