<?php

namespace Database\Seeders;

use App\Enums\ProviderType;
use Illuminate\Database\Seeder;

class AdminAccountSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Prajwal Hallale',
            'email' => '00prajwal@gmail.com',
            'provider_type' => ProviderType::GOOGLE->value,
            'provider_id' => '118385223791225348577',
            'avatar_url' => 'https://lh3.googleusercontent.com/a/AAcHTtdf5LXn0jDbjb2We9JI1SnHIrhuqf9MRRriP2J064STVig=s96-c',
        ]);
    }
}
