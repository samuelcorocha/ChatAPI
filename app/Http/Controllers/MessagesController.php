<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
  protected $messagesmd;
  public function __construct(Messages $messages){
    $this->messagesmd = $messages;
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

      $messages = $this->messagesmd->findAllByRoom($receiver_id);
      return response()->json($messages, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }catch (\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
  }

  public function sendMessage($message, $receiver_id) {
    try {
      if ($message == '' || is_null($message)) return;

      $this->messagesmd->create([
        'message' => $message,
        'room_id' => $receiver_id,
        'user_id' => auth()->id()
      ]);
      return true;
    }catch(\Exception $e){
      return false;
    }
  }
}
