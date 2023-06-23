<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;


    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 10, function (User $user) {
            $user
                ->setFirstName($this->faker->firstName)
                ->setEmail($this->faker->email)
                ->setPassword($this->userPasswordEncoder->encodePassword($user, '123'))
                ->setRoles(['owner'])
                ->setIsActive($this->faker->boolean(70));
        });

        $manager->flush();
    }
}
