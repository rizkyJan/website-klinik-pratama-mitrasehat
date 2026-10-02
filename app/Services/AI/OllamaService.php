<?php

namespace App\Services\AI;

use RuntimeException;

class OllamaService
{
    /**
     * @param array<int,array{role:string,content:string}> $messages
     * @param callable(string):void $onDelta
     * @return array<string,mixed>
     */
    public function streamChat(array $messages, callable $onDelta, array $overrides = []): array
    {
        $config = config('ai.ollama');
        $this->extendExecutionTime((int) ($config['timeout'] ?? 120));
        $url = rtrim((string) $config['url'], '/').'/api/chat';

        $payload = [
            'model' => (string) $config['model'],
            'messages' => $messages,
            'stream' => true,
            'think' => (bool) ($overrides['think'] ?? $config['think']),
            'keep_alive' => (string) ($overrides['keep_alive'] ?? $config['keep_alive']),
            'options' => [
                'num_ctx' => (int) ($overrides['context_length'] ?? $config['context_length']),
                'temperature' => (float) ($overrides['temperature'] ?? $config['temperature']),
                'num_predict' => (int) ($overrides['num_predict'] ?? $config['num_predict']),
            ],
        ];

        $ch = curl_init($url);
        if ($ch === false) {
            throw new RuntimeException('Tidak dapat memulai koneksi ke Ollama.');
        }

        $buffer = '';
        $metrics = [];

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/x-ndjson'],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_CONNECTTIMEOUT => (int) $config['connect_timeout'],
            CURLOPT_TIMEOUT => (int) $config['timeout'],
            CURLOPT_WRITEFUNCTION => function ($curl, string $chunk) use (&$buffer, &$metrics, $onDelta): int {
                $buffer .= $chunk;

                while (($position = strpos($buffer, "\n")) !== false) {
                    $line = trim(substr($buffer, 0, $position));
                    $buffer = substr($buffer, $position + 1);

                    if ($line === '') {
                        continue;
                    }

                    $data = json_decode($line, true);
                    if (! is_array($data)) {
                        continue;
                    }

                    $content = $data['message']['content'] ?? '';
                    if (is_string($content) && $content !== '') {
                        $onDelta($content);
                    }

                    if (($data['done'] ?? false) === true) {
                        $metrics = [
                            'total_duration' => $data['total_duration'] ?? null,
                            'load_duration' => $data['load_duration'] ?? null,
                            'prompt_eval_count' => $data['prompt_eval_count'] ?? null,
                            'eval_count' => $data['eval_count'] ?? null,
                            'eval_duration' => $data['eval_duration'] ?? null,
                        ];
                    }
                }

                return strlen($chunk);
            },
        ]);

        $ok = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($ok === false || $error !== '') {
            throw new RuntimeException('Ollama tidak dapat dihubungi: '.$error);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException('Ollama mengembalikan HTTP '.$httpCode.'.');
        }

        return $metrics;
    }

    /**
     * Request non-streaming untuk tugas kecil yang harus divalidasi sebelum ditampilkan,
     * misalnya enrichment edukasi umum pada fakta klinik yang belum diketahui.
     *
     * @param array<int,array{role:string,content:string}> $messages
     * @param array<string,mixed> $overrides
     */
    public function chatText(array $messages, array $overrides = []): string
    {
        $config = config('ai.ollama');
        $this->extendExecutionTime((int) ($config['timeout'] ?? 120));
        $url = rtrim((string) $config['url'], '/').'/api/chat';

        $payload = [
            'model' => (string) $config['model'],
            'messages' => $messages,
            'stream' => false,
            'think' => false,
            'keep_alive' => (string) ($overrides['keep_alive'] ?? $config['keep_alive']),
            'options' => [
                'num_ctx' => (int) ($overrides['context_length'] ?? $config['context_length']),
                'temperature' => (float) ($overrides['temperature'] ?? $config['temperature']),
                'num_predict' => (int) ($overrides['num_predict'] ?? $config['num_predict']),
            ],
        ];

        $ch = curl_init($url);
        if ($ch === false) {
            throw new RuntimeException('Tidak dapat memulai koneksi ke Ollama.');
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => (int) $config['connect_timeout'],
            CURLOPT_TIMEOUT => (int) $config['timeout'],
        ]);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false || $error !== '') {
            throw new RuntimeException('Ollama tidak dapat dihubungi: '.$error);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException('Ollama mengembalikan HTTP '.$httpCode.'.');
        }

        $data = json_decode((string) $body, true);
        if (! is_array($data)) {
            throw new RuntimeException('Respons Ollama tidak valid.');
        }

        return trim((string) ($data['message']['content'] ?? ''));
    }

    /**
     * @return array{ok:bool,message:string,model_found:bool}
     */
    public function testConnection(): array
    {
        $config = config('ai.ollama');
        $url = rtrim((string) $config['url'], '/').'/api/tags';

        $ch = curl_init($url);
        if ($ch === false) {
            return ['ok' => false, 'message' => 'Tidak dapat memulai koneksi.', 'model_found' => false];
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => (int) $config['connect_timeout'],
            CURLOPT_TIMEOUT => 12,
        ]);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false || $error !== '' || $httpCode !== 200) {
            return [
                'ok' => false,
                'message' => $error !== '' ? $error : 'HTTP '.$httpCode,
                'model_found' => false,
            ];
        }

        $data = json_decode((string) $body, true);
        $models = collect($data['models'] ?? [])->pluck('name')->filter()->values();
        $wanted = (string) $config['model'];
        $found = $models->contains(fn ($name) => $name === $wanted || str_starts_with((string) $name, $wanted.':'));

        return [
            'ok' => true,
            'message' => $found
                ? "Terhubung. Model {$wanted} tersedia."
                : "Terhubung ke Ollama, tetapi model {$wanted} belum terlihat pada /api/tags.",
            'model_found' => $found,
        ];
    }

    /**
     * PHP/Herd dapat memiliki max_execution_time 30 detik, sedangkan model lokal
     * CPU-only kadang memerlukan waktu lebih lama. Naikkan batas hanya untuk
     * request Ollama, mengikuti timeout aplikasi + margin aman.
     */
    private function extendExecutionTime(int $ollamaTimeout): void
    {
        if (! function_exists('set_time_limit')) {
            return;
        }

        $seconds = max(180, $ollamaTimeout + 30);
        @set_time_limit($seconds);
    }
}
