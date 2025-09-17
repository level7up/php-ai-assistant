<style>
.ai-assistant-widget {
    position: fixed;
    bottom: 20px;
    {{ is_rtl() ? 'left: 20px;' : 'right: 20px;' }}
    z-index: 9999;
    font-family: inherit;
}

.ai-assistant-widget-toggle {
    width: 60px;
    height: 60px;
    background-color: #007bff;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ai-assistant-widget-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
}

.ai-assistant-widget-toggle img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.ai-assistant-widget-container {
    position: absolute;
    bottom: 70px;
    {{ is_rtl() ? 'left: 0;' : 'right: 0;' }}
    width: 350px;
    height: 500px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.ai-assistant-widget-container.active {
    display: flex;
}

.ai-assistant-widget-header {
    padding: 15px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex: 0 0 auto; /* Fixed height, won't grow or shrink */
}

.ai-assistant-widget-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.ai-assistant-widget-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6c757d;
    font-size: 16px;
    padding: 0;
}

.ai-assistant-widget-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    height: calc(100% - 56px); /* Subtract header height */
    overflow: hidden;
}

.ai-assistant-widget-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background-color: #f9f9f9;
    max-height: calc(100% - 70px); /* Subtract input area height */
}

.ai-assistant-widget-input {
    padding: 10px 15px;
    background-color: white;
    border-top: 1px solid #e0e0e0;
    flex: 0 0 auto; /* Fixed height, won't grow or shrink */
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
    max-width: 80%;
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

/* RTL-specific styles */
.ai-assistant-widget.rtl .ai-message-user {
    justify-content: flex-start;
}

.ai-assistant-widget.rtl .ai-message-bot {
    justify-content: flex-end;
}

.ai-assistant-widget.rtl .input-group-append {
    margin-right: -1px;
    margin-left: 0;
}

.ai-assistant-widget.rtl .input-group > .form-control:not(:last-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

.ai-assistant-widget.rtl .input-group > .input-group-append > .btn {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
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
    font-size: 14px;
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

/* Responsive styles */
@media (max-width: 576px) {
    .ai-assistant-widget-container {
        width: 300px;
        {{ is_rtl() ? 'left: 0;' : 'right: 0;' }}
    }
}
</style>
