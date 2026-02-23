/**
 * CHATBOT WIDGET
 * Opens/closes the chat panel and sends user messages to POST /chat/message.
 * The server returns JSON { reply: "..." } (from FAQ match or fallback).
 * We need the API URL from the widget's data-api-url attribute.
 */
(function () {
    'use strict';

    function getWidget() {
        return document.getElementById('chat-widget');
    }

    function getPanel() {
        return document.getElementById('chat-panel');
    }

    function getMessagesContainer() {
        return document.getElementById('chat-messages');
    }

    function getForm() {
        return document.getElementById('chat-form');
    }

    function getInput() {
        return document.getElementById('chat-input');
    }

    function getSendButton() {
        return document.getElementById('chat-send');
    }

    function isOpen() {
        var panel = getPanel();
        return panel && panel.classList.contains('is-open');
    }

    function openPanel() {
        var panel = getPanel();
        if (panel) {
            panel.classList.add('is-open');
            panel.setAttribute('aria-hidden', 'false');
            getInput().focus();
        }
    }

    function closePanel() {
        var panel = getPanel();
        if (panel) {
            panel.classList.remove('is-open');
            panel.setAttribute('aria-hidden', 'true');
        }
    }

    function togglePanel() {
        if (isOpen()) {
            closePanel();
        } else {
            openPanel();
        }
    }

    function addMessage(text, isUser) {
        var container = getMessagesContainer();
        if (!container) return;
        var div = document.createElement('div');
        div.className = 'chat-message ' + (isUser ? 'user' : 'bot');
        var p = document.createElement('p');
        p.className = 'mb-0';
        p.textContent = text;
        div.appendChild(p);
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function setSendLoading(loading) {
        var btn = getSendButton();
        if (!btn) return;
        if (loading) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }
    }

    function sendMessage() {
        var input = getInput();
        var widget = getWidget();
        if (!input || !widget) return;

        var message = (input.value || '').trim();
        if (!message) return;

        var apiUrl = widget.getAttribute('data-api-url');
        if (!apiUrl) {
            addMessage('Chat is not configured.', false);
            return;
        }

        addMessage(message, true);
        input.value = '';
        setSendLoading(true);

        var csrf = widget.getAttribute('data-csrf');
        var headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        if (csrf) {
            headers['X-CSRF-TOKEN'] = csrf;
        }
        fetch(apiUrl, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ message: message })
        })
            .then(function (res) {
                if (!res.ok) throw new Error('Request failed');
                return res.json();
            })
            .then(function (data) {
                var reply = (data && data.reply) ? data.reply : 'Sorry, I could not get a response.';
                addMessage(reply, false);
            })
            .catch(function () {
                addMessage('Something went wrong. Please try the FAQs page or contact us.', false);
            })
            .finally(function () {
                setSendLoading(false);
            });
    }

    function init() {
        var widget = getWidget();
        if (!widget) return;

        var toggleBtn = document.getElementById('chat-toggle');
        var closeBtn = document.getElementById('chat-close');
        var form = getForm();
        var input = getInput();

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                togglePanel();
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closePanel();
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                sendMessage();
            });
        }

        if (input) {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
