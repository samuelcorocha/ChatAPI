<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Room;
use App\Models\UserRoom;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
  protected $messagesMD;
  protected $roomMD;
  protected $userRoomMD;
  public function __construct(Messages $messages, Room $room, UserRoom $userRoom){
    $this->messagesMD = $messages;
    $this->roomMD = $room;
    $this->userRoomMD = $userRoom;
  }

  public function getMessage(Request $request, $receiver_id = 0) {
    try{
      if(isset($request->message)){
        $mes = $this->sendMessage($request->message, $receiver_id);
        if(!$mes)
          return response()->json([
            'error' => 'Erro ao tentar enviar a messagem, tente novamente mais tarde'
          ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      }
      $room = $this->roomMD->where('name', 'receiver_'.$receiver_id.'_'.auth()->id())->first();
      $messages = $this->messagesMD->findAllByRoom($room->id);

      return response()->json($messages, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }catch (\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function sendMessage($message, $receiver_id) {
    try {
      if ($message == '' || is_null($message)) return true;

      $room = $this->roomMD->where('name', 'receiver_'.$receiver_id.'_'.auth()->id())->first();
      if(!$room){
        $res = $this->createPrivateChat($receiver_id);
        if(!$res) return false;

        $room = $this->roomMD->where('name', 'receiver_'.$receiver_id.'_'.auth()->id())->first();
      }

      $this->messagesMD->create([
        'message' => $message,
        'room_id' => $room->id,
        'user_id' => auth()->id()
      ]);
      return true;
    }catch(\Exception $e){
      return false;
    }
  }

  public function createPrivateChat($receiver_id){
    try {
      $room = $this->roomMD->create([
        'name' => 'receiver_'.$receiver_id.'_'.auth()->id(),
        'limit' => 2
      ]);
      $res = $this->createConnectionPrivate($room->id, $receiver_id);
      if(!$res) return false;

      return true;
    }catch(\Exception $e){
      return false;
    }
  }

  public function createConnectionPrivate($room_id, $receiver_id) {
    try {
      $this->userRoomMD->create([
        'user_id' => $receiver_id,
        'room_id' => $room_id
      ]);
      $this->userRoomMD->create([
        'user_id' => auth()->id(),
        'room_id' => $room_id
      ]);
      return true;
    }catch(\Exception $e){
      return false;
    }
  }
}
