<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->user->create([
            'name'=>'SAMU',
            'password'=>bcrypt('1234'),
            'email'=>'boss@chatapi.com',
            'access_token'=>'txt'
        ]);
        $this->user->create([
            'name'=>'MEGAX',
            'password'=>bcrypt('1112'),
            'email'=>'slave@chatapi.com',
            'access_token'=>'png'
        ]);
    }
}
