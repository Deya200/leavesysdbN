<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\Password;

class SendRealInvite extends Command
{
    protected $signature = 'mail:send-real-invite {email}';
    protected $description = 'Send a real password invitation via SMTP';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Sending real invitation to: $email");

        $emp = Employee::where('email', $email)->first();

        if (!$emp) {
            $this->error("Employee with email $email not found!");
            return 1;
        }

        try {
            $token = Password::getRepository()->create($emp);
            $emp->sendPasswordResetNotification($token);
            
            $this->info("✓ Email sent successfully to: " . $emp->email);
            $this->comment("Check your Gmail inbox!");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return 1;
        }
    }
}
