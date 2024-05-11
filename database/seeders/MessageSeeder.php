<?php

namespace Database\Seeders;

use App\Models\Messages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
  protected $message;
  public function __construct(Messages $messages) {
    $this->message = $messages;
  }

  /**
   * Run the database seeds.
   */
  public function run(): void {
    $this->message->factory()->count(200)->create();
  }
}
