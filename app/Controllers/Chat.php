<?php

namespace App\Controllers;

/**
 * Chat Controller
 *
 * Provides the API endpoint for the chatbot widget.
 * POST /chat/message — accepts a user message, finds the best matching FAQ from the database,
 * and returns a JSON reply. No login required.
 */
class Chat extends BaseController
{
    /**
     * Handle incoming chat message (from chatbot widget).
     * Expects POST with JSON body {"message": "user text"} or form field "message".
     * Returns JSON: {"reply": "bot answer"} or {"reply": "fallback message"}.
     */
    public function message()
    {
        $message = '';

        if ($this->request->getHeaderLine('Content-Type') && str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $json = $this->request->getJSON(true);
            $message = is_array($json) && isset($json['message']) ? trim((string) $json['message']) : '';
        } else {
            $message = trim((string) $this->request->getPost('message'));
        }

        $faqModel = model(\App\Models\FaqModel::class);
        $match = $faqModel->findBestMatchForMessage($message);

        if ($match !== null) {
            $reply = $match['answer'];
        } else {
            $reply = "I couldn't find an exact match for that. You can browse all questions on the FAQs page or use the search. For specific requests, please use the Contact page.";
        }

        return $this->response
            ->setContentType('application/json')
            ->setBody(json_encode(['reply' => $reply], JSON_THROW_ON_ERROR));
    }
}
