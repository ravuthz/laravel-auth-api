<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = new ClientRepository();
        $service->createPersonalAccessGrantClient('Personal Token');
        $service->createPasswordGrantClient('Password Token');
    }
}