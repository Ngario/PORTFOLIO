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
}
