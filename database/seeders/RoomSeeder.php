<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
  protected $room;
  public function __construct(Room $room){
    $this->room = $room;
  }

  /**
   * Run the database seeds.
   */
  public function run(): void {
    $this->room->factory()->count(20)->create();
  }
}
