<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | This value is the API key for OpenAI. This will be used to process
    | natural language queries from users. If not provided, the assistant
    | will fall back to direct keyword matching.
    |
    */
    'openai_api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Route Mappings
    |--------------------------------------------------------------------------
    |
    | This array maps user queries to application routes. Each key is a phrase
    | that might be found in a user query, and the value is an array containing
    | the route name, optional parameters, and a label for the link.
    |
    */
    'route_mappings' => [
        // Examples:
        'create purchase' => [
            'route' => 'purchases.create',
            'label' => 'Create Purchase'
        ],
        'create sale' => [
            'route' => 'sales.create',
            'label' => 'Create Sale'
        ],
        'create product' => [
            'route' => 'products.create',
            'label' => 'Create Product'
        ],
        // Add more mappings as needed
    ],
];
