<script>
document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('ai-assistant-widget');
    const toggle = widget.querySelector('.ai-assistant-widget-toggle');
    const container = widget.querySelector('.ai-assistant-widget-container');
    const closeBtn = widget.querySelector('.ai-assistant-widget-close');
    const form = document.getElementById('ai-widget-form');
    const input = document.getElementById('ai-widget-query');
    const messages = document.getElementById('ai-widget-messages');

    // Check if the layout is RTL
    const isRTL = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';

    // Add RTL class if needed
    if (isRTL) {
        widget.classList.add('rtl');
    }

    // Translations
    const translations = {
        error_message: "{{ __('ai-assistant::ai-assistant.error_message') }}"
    };

    // Toggle widget visibility
    toggle.addEventListener('click', function() {
        container.classList.add('active');
        input.focus();
        // Ensure messages are scrolled to bottom when opening
        setTimeout(scrollToBottom, 100);
    });

    // Close widget
    closeBtn.addEventListener('click', function() {
        container.classList.remove('active');
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const query = input.value.trim();
        if (!query) return;

        // Add user message to chat
        addMessage(query, 'user');

        // Clear input
        input.value = '';

        // Show loading indicator
        addLoadingMessage();

        // Send request to server
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

        // Scroll to bottom with a slight delay to ensure rendering is complete
        setTimeout(scrollToBottom, 10);
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

        // Scroll to bottom with a slight delay
        setTimeout(scrollToBottom, 10);
    }

    function removeLoadingMessage() {
        const loadingMessage = document.getElementById('ai-loading');
        if (loadingMessage) {
            loadingMessage.remove();
        }
    }

    // Scroll messages container to bottom
    function scrollToBottom() {
        if (messages) {
            messages.scrollTop = messages.scrollHeight;
        }
    }

    // Store widget state in localStorage
    function saveWidgetState() {
        localStorage.setItem('aiWidgetOpen', container.classList.contains('active'));
    }

    // Load widget state from localStorage
    function loadWidgetState() {
        const isOpen = localStorage.getItem('aiWidgetOpen') === 'true';
        if (isOpen) {
            container.classList.add('active');
            // Ensure messages are scrolled to bottom when opening
            setTimeout(scrollToBottom, 100);
        }
    }

    // Save state when widget is opened or closed
    toggle.addEventListener('click', saveWidgetState);
    closeBtn.addEventListener('click', saveWidgetState);

    // Add window resize handler to ensure proper scrolling
    window.addEventListener('resize', function() {
        if (container.classList.contains('active')) {
            setTimeout(scrollToBottom, 100);
        }
    });

    // Load state on page load
    loadWidgetState();
});
</script>
