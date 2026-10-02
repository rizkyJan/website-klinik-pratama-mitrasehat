<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use App\Services\AI\ClinicAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function stream(Request $request, ClinicAiService $ai)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:1000'],
            'visitor_key' => ['required', 'string', 'min:12', 'max:80'],
        ]);

        $message = trim($validated['message']);
        $visitorKey = preg_replace('/[^A-Za-z0-9\-_.]/', '', $validated['visitor_key']) ?: Str::uuid()->toString();

        return response()->stream(function () use ($ai, $message, $visitorKey) {
            ignore_user_abort(true);

            $emit = function (string $event, array $payload): void {
                echo 'event: '.$event."\n";
                echo 'data: '.json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n\n";

                if (ob_get_level() > 0) {
                    @ob_flush();
                }
                @flush();
            };

            $emit('ready', [
                'assistant_name' => AiSetting::valueOf('assistant_name', 'Asisten Klinik Mitra Sehat'),
            ]);

            $ai->stream($message, $visitorKey, $emit);
        }, 200, [
            'Content-Type' => 'text/event-stream; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
