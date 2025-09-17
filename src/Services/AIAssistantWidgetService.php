<?php

namespace Level7up\AIAssistant\Services;

use Illuminate\Support\Facades\View;

class AIAssistantWidgetService
{
    /**
     * Render the AI assistant widget
     *
     * @return string
     */
    public function render(): string
    {
        return View::make('ai-assistant::widget')->render();
    }

    /**
     * Get the JavaScript required for the widget
     *
     * @return string
     */
    public function getJavaScript(): string
    {
        return View::make('ai-assistant::widget_js')->render();
    }

    /**
     * Get the CSS required for the widget
     *
     * @return string
     */
    public function getCSS(): string
    {
        return View::make('ai-assistant::widget_css')->render();
    }
}
