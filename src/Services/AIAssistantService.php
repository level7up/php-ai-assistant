<?php

namespace Level7up\AIAssistant\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class AIAssistantService
{
    /**
     * Route mappings from config file
     *
     * @var array
     */
    protected array $routeMappings;

    /**
     * OpenAI API key
     *
     * @var string|null
     */
    protected ?string $openaiApiKey;

    /**
     * Use AI model flag
     *
     * @var bool
     */
    protected bool $useAiModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routeMappings = $this->loadRouteMappings();
        $this->openaiApiKey = config('ai-assistant.openai_api_key', env('OPENAI_API_KEY'));
        $this->useAiModel = !empty($this->openaiApiKey);
    }

    /**
     * Load route mappings from config file
     *
     * @return array
     */
    protected function loadRouteMappings(): array
    {
        return config('ai-assistant.route_mappings', []);
    }

    /**
     * Process a user query and return a response
     *
     * @param string $query The user's query
     * @return array The response data
     */
    public function processQuery(string $query): array
    {
        $query = strtolower($query);

        // If we have an OpenAI API key, try to use the AI model
        if ($this->useAiModel) {
            try {
                return $this->processWithAI($query);
            } catch (Exception $e) {
                // Log the error
                Log::warning('AI Assistant API Error: ' . $e->getMessage());

                // If API processing fails, fall back to direct matching
                return $this->processWithDirectMatching($query);
            }
        }

        // Otherwise, use direct matching
        return $this->processWithDirectMatching($query);
    }

    /**
     * Process a query using the OpenAI API
     *
     * @param string $query The user's query
     * @return array The response data
     * @throws Exception If the API call fails
     */
    protected function processWithAI(string $query): array
    {
        // Prepare the list of available routes for the AI
        $availableRoutes = [];
        foreach ($this->routeMappings as $phrase => $routeInfo) {
            $availableRoutes[] = [
                'phrase' => $phrase,
                'route' => $routeInfo['route'],
                'label' => $routeInfo['label'],
                'params' => $routeInfo['params'] ?? []
            ];
        }

        // Prepare the prompt for the AI
        $prompt = [
            [
                'role' => 'system',
                'content' => 'You are an AI assistant for a business management application. Your task is to understand user queries and direct them to the appropriate page in the application. You will be given a list of available routes and a user query. You should return the most relevant route for the query in JSON format.'
            ],
            [
                'role' => 'user',
                'content' => json_encode([
                    'query' => $query,
                    'available_routes' => $availableRoutes
                ])
            ]
        ];

        // Call the OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $prompt,
            'temperature' => 0.3,
            'max_tokens' => 150
        ]);

        // Check if the API call was successful
        if ($response->successful()) {
            $aiResponse = $response->json();

            // Check for quota exceeded error
            if (isset($aiResponse['error']) &&
                (Str::contains($aiResponse['error']['message'] ?? '', 'quota') ||
                 Str::contains($aiResponse['error']['message'] ?? '', 'billing'))) {

                // Log the quota error
                Log::warning('OpenAI API quota exceeded. Falling back to direct matching.');

                // Disable AI model for the rest of the session
                $this->useAiModel = false;

                // Throw exception to trigger fallback
                throw new Exception('OpenAI API quota exceeded');
            }

            $content = $aiResponse['choices'][0]['message']['content'] ?? '';

            // Try to parse the JSON response
            try {
                $result = json_decode($content, true);

                // If the AI returned a valid route
                if (isset($result['route']) && isset($result['label'])) {
                    return $this->generateLinkResponse(
                        $result['route'],
                        $result['params'] ?? [],
                        $result['label']
                    );
                }
            } catch (Exception $e) {
                // If JSON parsing fails, fall back to direct matching
                throw new Exception('Failed to parse AI response: ' . $e->getMessage());
            }
        } else {
            // Check for quota exceeded error in response
            $errorResponse = $response->json();
            if (isset($errorResponse['error']) &&
                (Str::contains($errorResponse['error']['message'] ?? '', 'quota') ||
                 Str::contains($errorResponse['error']['message'] ?? '', 'billing'))) {

                // Log the quota error
                Log::warning('OpenAI API quota exceeded. Falling back to direct matching.');

                // Disable AI model for the rest of the session
                $this->useAiModel = false;

                // Throw exception to trigger fallback
                throw new Exception('OpenAI API quota exceeded');
            }

            // Other API error
            throw new Exception('API call failed: ' . ($errorResponse['error']['message'] ?? 'Unknown error'));
        }

        // If the API call failed or returned invalid data, fall back to direct matching
        throw new Exception('AI processing failed');
    }

    /**
     * Process a query using direct matching
     *
     * @param string $query The user's query
     * @return array The response data
     */
    protected function processWithDirectMatching(string $query): array
    {
        // Direct matching - check if the query contains any of our mapped phrases
        foreach ($this->routeMappings as $phrase => $routeInfo) {
            if (Str::contains($query, $phrase)) {
                return $this->generateLinkResponse(
                    $routeInfo['route'],
                    $routeInfo['params'] ?? [],
                    $routeInfo['label']
                );
            }
        }

        // If no specific match is found, provide a generic response
        return [
            'type' => 'message',
            'message' => "I'm sorry, I couldn't understand your request. Please try asking about creating or viewing resources like purchases, sales, products, customers, suppliers, or reports."
        ];
    }

    /**
     * Generate a link response
     *
     * @param string $routeName The route name
     * @param array $params Optional route parameters
     * @param string $label The link label
     * @return array
     */
    private function generateLinkResponse(string $routeName, array $params = [], string $label = null): array
    {
        try {
            $url = route($routeName, $params);

            return [
                'type' => 'link',
                'message' => "Here's what I found for you:",
                'url' => $url,
                'label' => $label ?? Str::title(str_replace('.', ' ', $routeName))
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'message',
                'message' => "I'm sorry, I couldn't find that page. The route may not exist or you may not have permission to access it."
            ];
        }
    }
}
