<!--
    CHATBOT WIDGET
    Floating button opens a panel; user types a message, JS sends it to POST /chat/message,
    and the bot reply (from FAQ match) is shown. No page reload.
-->
<div id="chat-widget" class="chat-widget" data-api-url="<?= esc(base_url('chat/message')) ?>" data-csrf="<?= esc(csrf_hash()) ?>">
    <div id="chat-panel" class="chat-panel" aria-hidden="true">
        <div class="chat-panel-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-robot me-2"></i> FAQ Bot</span>
            <button type="button" class="btn btn-link btn-sm text-white p-0" id="chat-close" aria-label="Close chat">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-messages" id="chat-messages">
            <div class="chat-message bot">
                <p class="mb-0">Hi! Ask me anything from our FAQs (e.g. "How do I download?" or "contact").</p>
            </div>
        </div>
        <div class="chat-input-wrap">
            <form id="chat-form" class="d-flex gap-2">
                <input type="text" id="chat-input" class="form-control" placeholder="Type your question..." autocomplete="off" aria-label="Your message">
                <button type="submit" class="btn btn-primary" id="chat-send" aria-label="Send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
    <button type="button" id="chat-toggle" class="chat-toggle btn btn-primary" aria-label="Open chat">
        <i class="fas fa-comments" id="chat-toggle-icon"></i>
    </button>
</div>
