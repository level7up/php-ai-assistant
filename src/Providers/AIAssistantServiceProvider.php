<?php

namespace Level7up\AIAssistant\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Level7up\AIAssistant\Services\AIAssistantWidgetService;

class AIAssistantServiceProvider extends ServiceProvider
{
    protected string $dir = __DIR__;

    protected string $namespace = 'ai-assistant';

    protected bool $views = true;
    protected bool $translations = true;

    protected array $routes = ['web'];

    public function register(): void
    {
        parent::register();

        // Register the AI service
        $this->app->singleton('ai-assistant', function ($app) {
            return new \Level7up\AIAssistant\Services\AIAssistantService();
        });

        // Register the widget service
        $this->app->singleton(AIAssistantWidgetService::class, function ($app) {
            return new AIAssistantWidgetService();
        });
    }

    public function boot(): void
    {
        // Register widget directive
        Blade::directive('aiAssistantWidget', function () {
            return "<?php echo app('\\Level7up\\AIAssistant\\Services\\AIAssistantWidgetService')->render(); ?>";
        });

        // Register widget CSS directive
        Blade::directive('aiAssistantWidgetCSS', function () {
            return "<?php echo app('\\Level7up\\AIAssistant\\Services\\AIAssistantWidgetService')->getCSS(); ?>";
        });

        // Register widget JavaScript directive
        Blade::directive('aiAssistantWidgetJS', function () {
            return "<?php echo app('\\Level7up\\AIAssistant\\Services\\AIAssistantWidgetService')->getJavaScript(); ?>";
        });

        // Register is_rtl helper directive
        Blade::directive('isRtl', function () {
            return "<?php if (in_array(app()->getLocale(), ['ar', 'fa', 'he', 'ur'])): ?>";
        });

        Blade::directive('endIsRtl', function () {
            return "<?php endif; ?>";
        });

        // Register is_rtl helper function
        if (!function_exists('is_rtl')) {
            $this->app->singleton('is_rtl', function ($app) {
                return function () use ($app) {
                    return in_array($app->getLocale(), ['ar', 'fa', 'he', 'ur']);
                };
            });
        }

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../Config/ai-assistant.php' => config_path('ai-assistant.php'),
        ], 'ai-assistant-config');
    }
}
