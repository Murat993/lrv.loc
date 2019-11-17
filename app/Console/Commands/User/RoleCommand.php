<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use App\UseCases\Auth\RegisterService;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:role {email} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set role for user';
    /**
     * @var RegisterService
     */
    private $registerService;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email =  $this->argument('email');
        $role =  $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with this email ' . $email);
            return false;
        }

        try {
            $user->changeRole($role);
            $this->info('Role is successfully changed');
            return true;
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }
    }
}
