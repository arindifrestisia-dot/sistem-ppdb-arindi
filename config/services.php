<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'),
        'model' => env('OLLAMA_MODEL', 'mistral'),
        'system_prompt' => env('OLLAMA_SYSTEM_PROMPT', 'Kamu adalah asisten virtual RA Fadhilah. Jawab dengan bahasa Indonesia yang ramah, singkat, dan membantu. Jika informasi sekolah tidak tersedia, katakan dengan jujur dan sarankan menghubungi pihak sekolah.'),
        'timeout' => (int) env('OLLAMA_TIMEOUT', 120),
        'keep_alive' => env('OLLAMA_KEEP_ALIVE', '10m'),
        'num_predict' => (int) env('OLLAMA_NUM_PREDICT', 60),
        'num_ctx' => (int) env('OLLAMA_NUM_CTX', 1024),
        'embedding_model' => env('OLLAMA_EMBEDDING_MODEL', 'embeddinggemma'),
        'embedding_timeout' => (int) env('OLLAMA_EMBEDDING_TIMEOUT', 60),
        'rag_limit' => (int) env('OLLAMA_RAG_LIMIT', 5),
        'rag_min_score' => (float) env('OLLAMA_RAG_MIN_SCORE', 0.15),
    ],

    'wablas' => [
        'base_url' => env('WABLAS_BASE_URL', ''),
        'token' => env('WABLAS_TOKEN', ''),
        'secret_key' => env('WABLAS_SECRET_KEY', ''),
        'send_endpoint' => env('WABLAS_SEND_ENDPOINT', '/api/send-message'),
        'timeout' => (int) env('WABLAS_TIMEOUT', 15),
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY', ''),
        'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'timeout' => (int) env('MIDTRANS_TIMEOUT', 20),
    ],

];
