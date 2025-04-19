<?php

namespace App\Http\Controllers;

use App\Events\QRCodeGenerated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QRController extends Controller
{
    public function upload(Request $request)
    {
        Log::info($request);
        $request->validate([
            'qr_code' => 'required|image|mimes:png'
        ]);

        $path = $request->file('qr_code')->store('qr-codes', 'public');
        $url = asset('storage/' . $path);

        // Broadcast via WebSocket
        broadcast(new QRCodeGenerated($url))->toOthers();

        return response()->json(['qr_url' => $url]);
    }
}
