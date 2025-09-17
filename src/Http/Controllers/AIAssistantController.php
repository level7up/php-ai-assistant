<?php

namespace Level7up\AIAssistant\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Level7up\AIAssistant\Services\AIAssistantService;

class AIAssistantController extends Controller
{
    /**
     * The AI assistant service instance.
     *
     * @var AIAssistantService
     */
    protected AIAssistantService $aiService;

    /**
     * Create a new controller instance.
     *
     * @param AIAssistantService $aiService
     */
    public function __construct(AIAssistantService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show the AI assistant page.
     *
     * @return \Inertia\Response|\Illuminate\View\View
     */
    public function index()
    {
        if (class_exists('Inertia\Inertia')) {
            return Inertia::render('AIAssistant/Index');
        }

        return view('ai-assistant::index');
    }

    /**
     * Process a query from the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function query(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $query = $request->input('query');
        $response = $this->aiService->processQuery($query);

        return response()->json($response);
    }
}
