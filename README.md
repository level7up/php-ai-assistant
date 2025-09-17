# AI Assistant Package

A Laravel package that provides an AI-powered assistant widget for HashStudio applications.

## Features

- Floating chat widget that can be embedded anywhere in your application
- Natural language processing using OpenAI API
- Fallback to direct keyword matching when AI is not available
- RTL (Right-to-Left) language support
- Customizable route mappings
- Responsive design

## Installation

You can install the package via composer:

```bash
composer require hashstudio/ai-assistant
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=ai-assistant-config
```

### OpenAI API Key

Set your OpenAI API key in your `.env` file:

```
OPENAI_API_KEY=your-api-key
```

If the API key is not provided, the assistant will fall back to direct keyword matching.

### Route Mappings

Edit the `config/ai-assistant.php` file to customize the route mappings:

```php
'route_mappings' => [
    'create purchase' => [
        'route' => 'purchases.create',
        'label' => 'Create Purchase'
    ],
    // Add more mappings as needed
],
```

## Usage

### Adding the Widget to Your Layout

Add the following Blade directives to your main layout file:

```blade
@aiAssistantWidgetCSS
@aiAssistantWidget
@aiAssistantWidgetJS
```

Make sure to include the CSS directive in the `<head>` section and the JS directive before the closing `</body>` tag.

### Standalone Page

The package also provides a standalone page at `/ai-assistant` that can be accessed directly.

## Customization

### Translations

The package comes with English and Arabic translations. You can publish the translation files to customize them:

```bash
php artisan vendor:publish --tag=ai-assistant-translations
```

### Views

You can publish the views to customize them:

```bash
php artisan vendor:publish --tag=ai-assistant-views
```

## License

This package is open-sourced software licensed under the MIT license.
