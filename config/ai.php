<?php

return [
    'provider' => env('AI_PROVIDER', 'ollama'),

    'ollama' => [
        'url' => rtrim(env('OLLAMA_URL', 'http://127.0.0.1:11434'), '/'),
        'model' => env('OLLAMA_MODEL', 'qwen3:1.7b'),
        'connect_timeout' => (int) env('OLLAMA_CONNECT_TIMEOUT', 5),
        'timeout' => (int) env('OLLAMA_TIMEOUT', 120),
        'keep_alive' => env('OLLAMA_KEEP_ALIVE', '30s'),
        'context_length' => (int) env('OLLAMA_CONTEXT_LENGTH', 2048),
        'temperature' => (float) env('OLLAMA_TEMPERATURE', 0.2),
        'num_predict' => (int) env('OLLAMA_NUM_PREDICT', 420),
        'think' => filter_var(env('OLLAMA_THINK', false), FILTER_VALIDATE_BOOLEAN),
    ],
];
