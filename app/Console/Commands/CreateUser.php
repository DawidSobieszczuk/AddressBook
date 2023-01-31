<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--u|username= : Username of the newly created user.} {--e|email= : Email of the newly created user.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->option('username');
        if ($name === null) {
            $name = $this->ask('Please enter your username.');
        }

        $email = $this->option('email');
        if ($email === null) {
            $email = $this->ask('Please enter your email.');
        }
        
        $password = $this->secret('Please enter a new password.');
        $password_confirmation = $this->secret('Please confirm the password.');

        $isAdmin = $this->confirm('Do you want to be administrator?');

        $validator = Validator::make([
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'email' => $email,
            'isAdmin' => $isAdmin,
        ], [
            'name' => ['required', 'unique:users,name'],
            'password' => ['required', 'confirmed'],
            'email' => ['required', 'email', 'unique:users,email'],
            'isAdmin' => ['required'],
        ]);

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');
        
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'password' => $password,
            'email' => $email,
        ]);        

        if($isAdmin) 
            $user->attachRole('admin');

        // Success message
        $this->info('User created successfully!');
        $this->info('New user id: ' . $user->id);

        return 0;
    }
}
