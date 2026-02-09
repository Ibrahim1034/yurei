<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleIntaSend(Request $request)
    {
        Log::info('IntaSend webhook received', [
            'headers' => $request->headers->all(),
            'body'    => $request->getContent(),
            'parsed'  => $request->all(),
        ]);

        // Process invoice here
        $invoice = $request->all();

        return response()->json(['status' => 'ok']);
    }
}
