<?php

class Email extends CI_Controller {

    // public function index() {
    //     // Email configuration
    //     $server = '{mail.altsportal.com:993/imap/ssl}INBOX';
    //     $username = 'test@altsportal.com';
    //     $password = 'MM*98ZaLhkGC';

    //     // Connect to the IMAP server
    //     $imap_stream = imap_open($server, $username, $password);

    //     // Check if connection is successful
    //     if (!$imap_stream) {
    //         show_error('Unable to connect to the IMAP server');
    //         return;
    //     }

    //   // Fetch emails
    //     $emails = imap_search($imap_stream, 'ALL');
        
    //     // Display emails
    //     if ($emails) {
    //         foreach ($emails as $email_number) {
    //             $header = imap_headerinfo($imap_stream, $email_number);
    //             $from = $header->fromaddress;
    //             $subject = $header->subject;
        
    //             // Fetch the structure of the email
    //             $structure = imap_fetchstructure($imap_stream, $email_number);
                
    //             // Initialize variables for storing the email body
    //             $body = '';
                
    //             // If email is plain text, fetch the body
    //             if ($structure->type == 0) {
    //                 $body = imap_body($imap_stream, $email_number);
    //             }
    //             // If email is multipart, iterate through the parts to find the text/plain part
    //             elseif ($structure->type == 1) {
    //                 foreach ($structure->parts as $part_number => $part) {
    //                     // Check if part is text/plain
    //                     if ($part->type == 0 && $part->subtype == 'PLAIN') {
    //                         // Fetch body from this part
    //                         $body = imap_fetchbody($imap_stream, $email_number, $part_number + 1);
    //                         break;
    //                     }
    //                 }
    //             }
        
    //             // Decode the body if necessary
    //             $body = imap_utf8($body);
        
    //             echo "From: $from<br>";
    //             echo "Subject: $subject<br>";
    //             echo "Body: $body<br>";
    //             echo "<hr>";
    //         }
    //     } else {
    //         echo "No emails found";
    //     }
        
    //     // Close connection
    //     imap_close($imap_stream);

    // }
    
    public function index() {
        // Email configuration
        $server = '{mail.altsportal.com:993/imap/ssl}INBOX';
        $username = 'test@altsportal.com';
        $password = 'MM*98ZaLhkGC';

        // Connect to the IMAP server
        $imap_stream = imap_open($server, $username, $password);

        // Check if connection is successful
        if (!$imap_stream) {
            $data['error_message'] = 'Unable to connect to the IMAP server';
            $this->load->view('email_view', $data);
            return;
        }

        // Search emails addressed to test@altsportal.com
        $emails = imap_search($imap_stream, 'TO "test@altsportal.com"');

        $email_list = [];
        if ($emails) {
            foreach ($emails as $email_number) {
                $header = imap_headerinfo($imap_stream, $email_number);
                $from = $header->fromaddress;
                $subject = $header->subject;
                $message_id = $header->message_id;
                $date = date('Y-m-d H:i:s', strtotime($header->date));

                // Fetch the structure of the email
                $structure = imap_fetchstructure($imap_stream, $email_number);

                // Initialize variables for storing the email body
                $body = '';

                if ($structure->type == 0 && $structure->subtype == 'PLAIN') {
                    $body = imap_body($imap_stream, $email_number);
                } elseif ($structure->type == 1) {
                    foreach ($structure->parts as $part_number => $part) {
                        if ($part->type == 0 && $part->subtype == 'PLAIN') {
                            $body = imap_fetchbody($imap_stream, $email_number, $part_number + 1);
                            break;
                        } elseif ($part->type == 0 && $part->subtype == 'HTML') {
                            $body = imap_fetchbody($imap_stream, $email_number, $part_number + 1);
                            break;
                        }
                    }
                } elseif ($structure->type == 0 && $structure->subtype == 'HTML') {
                    $body = imap_body($imap_stream, $email_number);
                }

                // Decode the body if necessary
                if (isset($structure->encoding)) {
                    switch ($structure->encoding) {
                        case 3: // BASE64
                            $body = base64_decode($body);
                            break;
                        case 4: // QUOTED-PRINTABLE
                            $body = quoted_printable_decode($body);
                            break;
                    }
                }

                $email_list[] = [
                    'from' => $from,
                    'subject' => $subject,
                    'date' => $date,
                    'body' => $body, // Do not encode HTML body here
                    'message_id' => $message_id // Add message ID to the list
                ];
            }
        } else {
            $data['error_message'] = 'No emails found';
        }

        // Close connection
        imap_close($imap_stream);

        $data['emails'] = $email_list;
        $this->load->view('email_view', $data);
    }
    
    public function send_reply() {
        $to = $this->input->post('email');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');
         $in_reply_to = $this->input->post('in_reply_to');

        $this->load->library('email');
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('test@altsportal.com', 'RedTax Financial Services');
        $this->email->to($to);
        // $this->email->subject('REDTax Support');
        $this->email->subject('REDTax Document Upload Success');
        $this->email->message($message);
        
        // Add In-Reply-To and References headers
        $this->email->set_header('In-Reply-To', $in_reply_to);
        $this->email->set_header('References', $in_reply_to);

        if ($this->email->send()) {
            $this->session->set_flashdata('msg', 'Email sent successfully');
        } else {
            $this->session->set_flashdata('msg', 'Failed to send email');
        }

        redirect('view_mail');
    }
}
