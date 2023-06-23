<?php


namespace App\EventSubscriber;

use App\Events\ArticleCreatedEvent;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;

class ArticleCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(Mailer $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function onArticleCreated(ArticleCreatedEvent $event)
    {
        $article = $event->getArticle();

        if ($article->getAuthor()->getEmail() != 'admin@symfony.skillbox') {
            $this->mailer->sendMail(
                'admin@symfony.skillbox',
                '',
                'Создана новая статья',
                'email/weekly-newsletter.html.twig',
                function (TemplatedEmail $email) use ($article){
                    $email
                        ->context([
                            'articles'  =>  $article
                        ])
                        ->from(new Address($article->getAuthor()->getEmail(), $article->getAuthor()->getFirstName()))
                    ;
                }
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ArticleCreatedEvent::class => 'onArticleCreated'
        ];
    }
}