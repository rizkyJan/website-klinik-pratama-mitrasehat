<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use App\Services\AI\OllamaService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'enabled' => AiSetting::bool('enabled', true),
            'assistant_name' => AiSetting::valueOf('assistant_name', 'Asisten Klinik Mitra Sehat'),
            'welcome_message' => AiSetting::valueOf('welcome_message', ''),
            'out_of_scope_message' => AiSetting::valueOf('out_of_scope_message', ''),
            'unknown_message' => AiSetting::valueOf('unknown_message', ''),
            'allow_health' => AiSetting::bool('allow_health', true),
            'store_history' => AiSetting::bool('store_history', true),
            'store_unanswered' => AiSetting::bool('store_unanswered', true),
            'max_history_messages' => (int) AiSetting::valueOf('max_history_messages', 8),
        ];

        $runtime = [
            'url' => config('ai.ollama.url'),
            'model' => config('ai.ollama.model'),
            'context_length' => config('ai.ollama.context_length'),
            'keep_alive' => config('ai.ollama.keep_alive'),
        ];

        return view('admin.ai.settings.edit', compact('settings', 'runtime'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'assistant_name' => ['required', 'string', 'max:100'],
            'welcome_message' => ['required', 'string', 'max:1000'],
            'out_of_scope_message' => ['required', 'string', 'max:1000'],
            'unknown_message' => ['required', 'string', 'max:1500'],
            'max_history_messages' => ['required', 'integer', 'min:2', 'max:12'],
        ]);

        AiSetting::put('enabled', $request->boolean('enabled'));
        AiSetting::put('allow_health', $request->boolean('allow_health'));
        AiSetting::put('store_history', $request->boolean('store_history'));
        AiSetting::put('store_unanswered', $request->boolean('store_unanswered'));

        foreach ($data as $key => $value) {
            AiSetting::put($key, $value);
        }

        return redirect()->route('admin.ai.settings.edit')->with('success', 'Pengaturan Asisten Klinik berhasil disimpan.');
    }

    public function test(OllamaService $ollama)
    {
        $result = $ollama->testConnection();

        return redirect()->route('admin.ai.settings.edit')->with(
            $result['ok'] && $result['model_found'] ? 'success' : 'error',
            $result['message']
        );
    }
}
