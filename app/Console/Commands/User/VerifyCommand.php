<?php

namespace App\Console\Commands\User;

use App\Entity\User\User;
use App\UseCases\Auth\RegisterService;
use Illuminate\Console\Command;

class VerifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify user email';
    /**
     * @var RegisterService
     */
    private $registerService;

    public function __construct(RegisterService $registerService)
    {
        parent::__construct();
        $this->registerService = $registerService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email =  $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }
        try {
            $this->registerService->verify($user->id);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }

        $this->info('Success');
        return true;
    }
}
