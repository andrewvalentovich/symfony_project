<?php


namespace App\EventSubscriber;

use App\Events\UserRegisteredEvent;
use App\Service\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();

        $this->mailer->sendMail(
            $user->getEmail(),
            $user->getFirstName(),
            'Приветствуем тебя, новый пользователь',
            'email/welcome.html.twig',
            function (TemplatedEmail $email) use ($user){
                $email
                    ->context([
                        'user'  =>  $user
                    ])
                ;
            }
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered'
        ];
    }
}