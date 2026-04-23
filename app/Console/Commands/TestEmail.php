<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to check mail configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'deyamanmasina@gmail.com';
        
        $this->info("Sending test email to: {$email}");
        
        try {
            Mail::raw('This is a test email from ABC Leave Management System.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - ABC Leave Management System');
            });
            
            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
