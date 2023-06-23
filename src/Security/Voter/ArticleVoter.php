<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{

    /** @var Article $subject */

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['VOTER_ARTICLE_EDIT', 'VOTER_ARTICLE_API'])
            && $subject instanceof Article;
    }


    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VOTER_ARTICLE_EDIT':
                if ($this->security->isGranted('ROLE_ADMIN_ARTICLE')) {
                    return true;
                }

                if ($subject->getAuthor() == $user) {
                    return true;
                }

                break;

            case 'VOTER_ARTICLE_API':
                if ($this->security->isGranted('ROLE_API')) {
                    return true;
                }

                if ($subject->getAuthor() == $user) {
                    return true;
                }

                break;
        }

        return false;
    }
}
