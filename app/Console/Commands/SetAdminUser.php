<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:set {email : Email address of the user to make admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a user as admin by email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("User {$email} has been set as admin successfully!");
        return 0;
    }
}
