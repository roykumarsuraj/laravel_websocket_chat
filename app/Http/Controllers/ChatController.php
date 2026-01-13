<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        try {
            $request->validate([
                'to_user_id' => 'required',
                'message' => 'required|string',
            ]);

            $from = session('uid'); 
            $to   = $request->to_user_id;

            if(!$from){
                Log::error('Chat send failed: User not logged in.');
                return response()->json(['status' => 'error', 'message' => 'Not logged in'], 401);
            }

            Log::info("Chat send request from $from to $to: " . $request->message);

            event(new PrivateMessageSent($from, $to, $request->message));

            return response()->json(['status' => 'sent']);

        } catch (\Exception $e) {
            // Log the full exception
            Log::error('Chat send failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}