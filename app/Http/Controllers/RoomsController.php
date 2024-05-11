<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Models\Messages;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\UserRoom;

class RoomsController extends Controller
{
  protected $roomMD;
  protected $userRoomMD;
  protected $messagesMD;

  public function __construct(Room $room, UserRoom $userRoom, Messages $messages) {
    $this->roomMD = $room;
    $this->userRoomMD = $userRoom;
    $this->messagesMD = $messages;
  }

  public function createRoom(CreateRoomRequest $request) {
    try {
      $room = $this->roomMD->create([
        'name' => $request->room,
        'limit' => $request->limit
      ]);
      return response()->json([
        'success' => 'Sala criada com sucesso.',
        'room' => $room
      ], 201, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function deleteRoom($room_id) {
    try {
      $room = $this->roomMD->findOrFail($room_id);
      $this->userRoomMD->where('room_id', $room->id)->delete();
      $this->messagesMD->where('room_id', $room->id)->delete();
      $room->delete();

      return response()->json([
        'success' => 'Sala deletada com sucesso.'
      ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function enterRoom($room_id) {
    try {
      $user = auth()->user();
      $userRoom = $this->userRoomMD->where('room_id', $room_id)->where('user_id', $user->id)->first();
      if($userRoom)
        return response()->json([
          'success' => 'Usuário já esta na sala.'
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      $this->userRoomMD->create([
        'room_id' => $room_id,
        'user_id' => $user->id
      ]);
      return response()->json([
        'success' => 'Usuário entrou na sala.'
      ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function leaveRoom($room_id) {
    try {
      $user = auth()->user();
      $userRoom = $this->userRoomMD->where('room_id', $room_id)->where('user_id', $user->id)->first();
      if (!$userRoom)
        return response()->json([
          'error' => "Usuário não está na sala."
        ], 204, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      $userRoom->delete();
      return response()->json([
        'success' => 'Usuário saiu na sala.'
      ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function kickUser($room_id, $user_id) {
    try {
      $userRoom = $this->userRoomMD->where('room_id', $room_id)->where('user_id', $user_id)->first();
      if (!$userRoom)
        return response()->json([
          'error' => "Usuário não está na sala."
        ], 204, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      $userRoom->delete();
      return response()->json([
        'success' => 'Usuário removido da sala.'
      ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function message(Request $request, $room_id) {
    try {
      $room = $this->roomMD->where('id', $room_id)->first();
      if (!$room)
        return response()->json([
          'error' => "Sala não encontrada."
        ], 204, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      $userRoom = $this->userRoomMD->where('room_id', $room_id)->where('user_id', auth()->id())->first();
      if (!$userRoom)
        return response()->json([
          'error' => "Usuário não está cadastrado nesta sala."
        ], 204, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      if(isset($request->message)){
        $mes = $this->sendMessage($request->message, $room_id);
        if(!$mes)
          return response()->json([
            'error' => 'Erro ao tentar enviar a messagem, tente novamente mais tarde'
          ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      }

      $messages = $this->messagesMD->findAllByRoom($room_id);
      return response()->json([
        'room' => $room->name,
        'messages' => $messages
      ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function sendMessage($message, $room_id) {
    try {
      if ($message == '' || is_null($message)) return true;

      $room = $this->roomMD->where('id', $room_id)->first();
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
}
