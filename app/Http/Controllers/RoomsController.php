<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\UserRoom;

class RoomsController extends Controller
{
  protected $roommd;
  protected $userRoommd;

  public function __construct(Room $room, UserRoom $userRoom)
  {
    $this->roommd = $room;
    $this->userRoommd = $userRoom;
  }

  public function createRoom(CreateRoomRequest $request)
  {
    try {
      $room = $this->roommd->create([
        'name' => $request->room,
        'status' => true,
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

  public function deleteRoom($room_id)
  {
    try {
      $this->roommd->findOrFail($room_id)->update([
        'status' => false
      ]);

      return response()->json([
        'success' => 'Sala deletada com sucesso.'
      ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function enterRoom($room_id)
  {
    try {
      $user = auth()->user();
      $this->userRoommd->create([
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

  public function leaveRoom($room_id)
  {
    try {
      $user = auth()->user();
      $userRoom = $this->userRoommd->where('room_id', $room_id)->where('user_id', $user->id)->first();
      if (!$userRoom) {
        return response()->json([
          'error' => "Usuário não está na sala."
        ], 204, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      }
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

  public function kickUser($room_id, $user_id)
  {
    try {
      $userRoom = $this->userRoommd->where('room_id', $room_id)->where('user_id', $user_id)->first();
      if (!$userRoom) {
        return response()->json([
          'error' => "Usuário não está na sala."
        ], 204, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      }
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
}
