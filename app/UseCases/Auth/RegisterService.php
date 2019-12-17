<?php
namespace App\UseCases\Auth;
use App\Entity\User\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\Auth\VerifyMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer as MailerInterface;
use Illuminate\Support\Facades\Mail;


class RegisterService
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(MailerInterface $mailer, Dispatcher $dispatcher)
    {
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }

    public function register(RegisterRequest $request): void
    {
        $user = User::register(
            $request['name'],
            $request['email'],
            $request['password']
        );

        $this->mailer->to($user->email)->send(new VerifyMail($user));
        $this->dispatcher->dispatch(new Registered($user));
    }

    public function verify($id): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $user->verify();
    }
}
