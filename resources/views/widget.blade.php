<div id="ai-assistant-widget" class="ai-assistant-widget">
    <div class="ai-assistant-widget-toggle">
        <img src="{{ global_asset('images/gdawel-ai.png') }}" alt="AI Assistant">
    </div>
    <div class="ai-assistant-widget-container">
        <div class="ai-assistant-widget-header">
            <h4>{{ trans('ai-assistant::ai-assistant.ai_assistant') }}</h4>
            <button class="ai-assistant-widget-close">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="ai-assistant-widget-body">
            <div class="ai-assistant-widget-messages" id="ai-widget-messages">
                <div class="ai-message ai-message-bot">
                    <div class="ai-message-content">
                        {{ trans('ai-assistant::ai-assistant.welcome_message') }}
                    </div>
                </div>
            </div>
            <div class="ai-assistant-widget-input">
                <form id="ai-widget-form">
                    <div class="input-group">
                        <input type="text" id="ai-widget-query" class="form-control" placeholder="{{ trans('ai-assistant::ai-assistant.ask_anything') }}" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
