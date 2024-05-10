<?php

namespace Database\Seeders;

use App\Models\UserRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoomSeeder extends Seeder
{
  protected $userRoom;
  public function __construct(UserRoom $userRoom) {
    $this->userRoom = $userRoom;
  }

  /**
   * Run the database seeds.
   */
  public function run(): void {
    for($x = 1; $x <= 20; $x++){
      $this->userRoom->create([
        'user_id' => fake()->numberBetween(1,2),
        'room_id' => $x
      ]);
    }

    for($x = 1; $x <= 20; $x++){
      $this->userRoom->create([
        'user_id' => fake()->numberBetween(3,22),
        'room_id' => $x
      ]);
    }

    for($x = 1; $x <= 20; $x++){
      $verify = true;
      while($verify){
        $user_id = fake()->numberBetween(3,22);
        if(!$this->userRoom->where('room_id', $x)->where('user_id', $user_id)->first()) $verify = false;
      }

      $this->userRoom->create([
        'user_id' => fake()->numberBetween(3,22),
        'room_id' => $x
      ]);
    }
  }
}
