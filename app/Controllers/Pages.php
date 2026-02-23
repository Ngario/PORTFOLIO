<?php

namespace App\Controllers;

/**
 * Pages Controller
 * 
 * Handles static pages: About, Contact, Terms, Privacy
 * These are pages with mostly static content
 */
class Pages extends BaseController
{
    /**
     * Show last fatal error from writable/logs/last-fatal.txt (for Render 500 debugging).
     * Visit /render-debug after a 500 to see the saved error. Remove route when done.
     */
    public function renderDebug()
    {
        $file = WRITEPATH . 'logs' . DIRECTORY_SEPARATOR . 'last-fatal.txt';
        if (! is_file($file)) {
            return $this->response->setBody('No saved fatal error. Trigger a 500 on the homepage first.')
                ->setStatusCode(404);
        }
        $body = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Last fatal</title></head><body>';
        $body .= '<h1>Last fatal error (saved)</h1><pre>' . htmlspecialchars(file_get_contents($file)) . '</pre>';
        $body .= '<p><a href="' . base_url() . '">Home</a></p></body></html>';
        return $this->response->setBody($body)->setStatusCode(200);
    }

    /**
     * About Page
     * 
     * URL: /about
     * Shows information about you
     */
    public function about()
    {
        // Data to pass to view
        $data = [
            'title' => 'About Me',
            'description' => 'Learn more about my background, skills, and experience',
        ];
        
        return view('pages/about', $data);
    }
    
    /**
     * Contact Page
     * 
     * URL: /contact
     * Shows contact form
     */
    public function contact()
    {
        $data = [
            'title' => 'Contact Me',
            'description' => 'Get in touch for projects, collaborations, or inquiries',
        ];
        
        return view('pages/contact', $data);
    }
    
    /**
     * Send Contact Message
     * 
     * URL: POST /contact/send
     * Processes contact form submission
     */
    public function sendMessage()
    {
        // Load form validation
        $validation = \Config\Services::validation();
        
        // Validation rules
        $validation->setRules([
            'name'    => 'required|min_length[3]|max_length[100]',
            'email'   => 'required|valid_email',
            'subject' => 'required|min_length[5]|max_length[200]',
            'message' => 'required|min_length[10]',
        ]);
        
        // Check if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            // Return to contact page with errors
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        // Get form data
        $name    = $this->request->getPost('name');
        $email   = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        
        // TODO: Save to database (contact_messages table)
        // $contactModel = new ContactModel();
        // $contactModel->insert([
        //     'name' => $name,
        //     'email' => $email,
        //     'subject' => $subject,
        //     'message' => $message,
        //     'created_at' => date('Y-m-d H:i:s'),
        // ]);
        
        // TODO: Send email notification
        // $email = \Config\Services::email();
        // $email->setTo('your@email.com');
        // $email->setFrom($email, $name);
        // $email->setSubject($subject);
        // $email->setMessage($message);
        // $email->send();
        
        // Success message
        return redirect()->to('/contact')
            ->with('success', 'Thank you! Your message has been sent successfully.');
    }
    
    /**
     * Terms of Service Page
     * 
     * URL: /terms
     */
    public function terms()
    {
        $data = [
            'title' => 'Terms of Service',
            'description' => 'Terms and conditions for using our services',
        ];
        
        return view('pages/terms', $data);
    }
    
    /**
     * Privacy Policy Page
     * 
     * URL: /privacy
     */
    public function privacy()
    {
        $data = [
            'title' => 'Privacy Policy',
            'description' => 'How we collect, use, and protect your data',
        ];
        
        return view('pages/privacy', $data);
    }

    /**
     * FAQs Page
     *
     * URL: /faqs
     * Shows frequently asked questions in an accordion.
     * FAQ data is kept here for now; you can later move it to a database table.
     */
    public function faqs()
    {
        $data = [
            'title' => 'FAQs',
            'description' => 'Frequently asked questions about my services, downloads, and how to get started',
            'faqs' => $this->getFaqs(),
        ];
        
        return view('pages/faqs', $data);
    }

    /**
     * Search Page
     *
     * URL: /search or /search?q=keyword
     * Filters FAQs by the search term and shows results. Actionable: user types and sees matching FAQs.
     */
    public function search()
    {
        $query = $this->request->getGet('q');
        $query = is_string($query) ? trim($query) : '';
        $allFaqs = $this->getFaqs();
        
        if ($query !== '') {
            $queryLower = mb_strtolower($query);
            $allFaqs = array_filter($allFaqs, static function ($faq) use ($queryLower) {
                $question = mb_strtolower($faq['question'] ?? '');
                $answer = mb_strtolower($faq['answer'] ?? '');
                return str_contains($question, $queryLower) || str_contains($answer, $queryLower);
            });
            $allFaqs = array_values($allFaqs); // re-index 0, 1, 2...
        }
        
        $data = [
            'title' => 'Search',
            'description' => 'Search FAQs and site content',
            'query' => $query,
            'faqs' => $allFaqs,
        ];
        
        return view('pages/search', $data);
    }

    /**
     * Returns the list of FAQs (question + answer).
     * Shared by faqs() and search(). Later you can load from database instead.
     *
     * @return array<int, array{question: string, answer: string}>
     */
    private function getFaqs(): array
    {
        return [
            [
                'question' => 'How do I download a resource?',
                'answer' => 'Create an account or log in, then go to Downloads. Click the resource you want and use the download button. Some items are free; others may require purchase or membership.',
            ],
            [
                'question' => 'Do I need to create an account?',
                'answer' => 'For free public downloads you can browse without an account. To download files and access member-only content, you need to register. Registration is free.',
            ],
            [
                'question' => 'How can I contact you for a project?',
                'answer' => 'Use the Contact page to send a message. You can also reach me via the email or phone listed in the footer. I typically reply within 1–2 business days.',
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'For paid downloads and services, payment options are shown at checkout. This site may support M-Pesa and other methods depending on configuration.',
            ],
            [
                'question' => 'Can I use your code or templates in my projects?',
                'answer' => 'It depends on the license of each resource. Check the download or project page for licensing terms. Many resources are for personal or commercial use with attribution.',
            ],
            [
                'question' => 'Where can I see your portfolio projects?',
                'answer' => 'Go to the Projects page from the main menu or homepage. Each project has a description, technologies used, and links to live demos or repositories where applicable.',
            ],
        ];
    }

    /**
     * Public CV download (FREE for everyone)
     *
     * URL: /download-cv
     *
     * Put your CV file here:
     *   public/uploads/cv/cv.pdf
     *
     * Then this method will serve it as a download.
     */
    public function downloadCv()
    {
        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'cv' . DIRECTORY_SEPARATOR . 'cv.pdf';
        $path = realpath($path);

        if ($path === false || ! is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('CV file not found. Add it to public/uploads/cv/cv.pdf');
        }

        return $this->response->download($path, null, true);
    }
}
