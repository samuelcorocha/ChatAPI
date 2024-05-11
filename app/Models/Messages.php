<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Messages extends Model
{
  use HasFactory;
  protected $table = 'messages';
  protected $fillable = [
    'message',
    'user_id',
    'room_id'
  ];

  public function findAllByRoom($room_id){
    return DB::table($this->table. ' as m')
      ->select('u.name', 'm.message')
      ->join('users as u', 'u.id', '=', 'm.user_id')
      ->join('rooms as r', 'r.id', '=', 'm.room_id')
      ->where('r.id', $room_id)
      ->orderBy('m.id', 'DESC')
      ->get();
  }
}
