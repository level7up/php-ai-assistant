<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('ai-assistant::ai-assistant.ai_assistant') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .chat-container {
            max-width: 800px;
            margin: 50px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
        }
        .chat-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background-color: white;
        }
        .chat-input {
            padding: 15px;
            background-color: #f1f3f5;
            border-top: 1px solid #dee2e6;
        }
        .ai-message {
            margin-bottom: 15px;
            display: flex;
        }
        .ai-message-user {
            justify-content: {{ is_rtl() ? 'flex-start' : 'flex-end' }};
        }
        .ai-message-bot {
            justify-content: {{ is_rtl() ? 'flex-end' : 'flex-start' }};
        }
        .ai-message-content {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .ai-message-bot .ai-message-content {
            background-color: #e9ecef;
            color: #212529;
        }
        .ai-message-user .ai-message-content {
            background-color: #007bff;
            color: white;
        }
        .ai-link-button {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }
        .ai-link-button:hover {
            background-color: #218838;
            color: white;
            text-decoration: none;
        }
        .ai-typing-indicator {
            display: flex;
            align-items: center;
        }
        .ai-typing-indicator span {
            height: 8px;
            width: 8px;
            margin: 0 2px;
            background-color: #6c757d;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 1.5s infinite ease-in-out;
        }
        .ai-typing-indicator span:nth-child(1) {
            animation-delay: 0s;
        }
        .ai-typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }
        .ai-typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes bounce {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-5px);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-container">
            <div class="chat-header">
                <h1>{{ trans('ai-assistant::ai-assistant.ai_assistant') }}</h1>
            </div>
            <div class="chat-messages" id="chat-messages">
                <div class="ai-message ai-message-bot">
                    <div class="ai-message-content">
                        {{ trans('ai-assistant::ai-assistant.welcome_message') }}
                    </div>
                </div>
            </div>
            <div class="chat-input">
                <form id="chat-form">
                    <div class="input-group">
                        <input type="text" id="chat-query" class="form-control" placeholder="{{ trans('ai-assistant::ai-assistant.ask_anything') }}" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-paper-plane"></i> {{ trans('ai-assistant::ai-assistant.send') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('chat-form');
            const input = document.getElementById('chat-query');
            const messages = document.getElementById('chat-messages');

            // Translations
            const translations = {
                error_message: "{{ trans('ai-assistant::ai-assistant.error_message') }}",
                thinking: "{{ trans('ai-assistant::ai-assistant.thinking') }}"
            };

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const query = input.value.trim();
                if (!query) return;

                // Add user message
                addMessage(query, 'user');

                // Clear input
                input.value = '';

                // Show loading indicator
                addLoadingMessage();

                // Send request
                fetch('{{ route("ai-assistant.query") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ query: query })
                })
                .then(response => response.json())
                .then(data => {
                    // Remove loading indicator
                    removeLoadingMessage();

                    // Process response
                    if (data.type === 'link') {
                        const message = data.message + '<br><a href="' + data.url + '" class="ai-link-button">' + data.label + '</a>';
                        addMessage(message, 'bot', true);
                    } else {
                        addMessage(data.message, 'bot');
                    }
                })
                .catch(error => {
                    // Remove loading indicator
                    removeLoadingMessage();

                    // Show error message
                    addMessage(translations.error_message, 'bot');
                    console.error('AI Assistant Error:', error);
                });
            });

            function addMessage(message, sender, isHtml = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'ai-message ai-message-' + sender;

                const contentDiv = document.createElement('div');
                contentDiv.className = 'ai-message-content';

                if (isHtml) {
                    contentDiv.innerHTML = message;
                } else {
                    contentDiv.textContent = message;
                }

                messageDiv.appendChild(contentDiv);
                messages.appendChild(messageDiv);

                // Scroll to bottom
                messages.scrollTop = messages.scrollHeight;
            }

            function addLoadingMessage() {
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'ai-message ai-message-bot';
                loadingDiv.id = 'ai-loading';

                const contentDiv = document.createElement('div');
                contentDiv.className = 'ai-message-content';

                const indicatorDiv = document.createElement('div');
                indicatorDiv.className = 'ai-typing-indicator';

                for (let i = 0; i < 3; i++) {
                    const span = document.createElement('span');
                    indicatorDiv.appendChild(span);
                }

                contentDiv.appendChild(indicatorDiv);
                loadingDiv.appendChild(contentDiv);
                messages.appendChild(loadingDiv);

                // Scroll to bottom
                messages.scrollTop = messages.scrollHeight;
            }

            function removeLoadingMessage() {
                const loadingMessage = document.getElementById('ai-loading');
                if (loadingMessage) {
                    loadingMessage.remove();
                }
            }
        });
    </script>
</body>
</html>
