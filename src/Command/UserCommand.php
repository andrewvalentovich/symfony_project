<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCommand extends Command
{
    protected static $defaultName = 'app:user:deactivate';
    protected static $defaultDescription = 'Deactivate user with its id';
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserCommand constructor.
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'User`s id')
            ->addOption('reverse', null, InputOption::VALUE_OPTIONAL, 'Activate user', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user_id = $input->getArgument('id');

        $user = $this->userRepository->findOneBy(['id' => $user_id]);

        $isOption = (false === $input->getOption('reverse')) ? false : true;
        $this->makeVerdict($isOption, $user_id, $user, $io);

        return Command::SUCCESS;
    }

    private function makeVerdict(bool $verdict, int $user_id, User $user, SymfonyStyle $io)
    {
        if ($user->getIsActive() == $verdict) {
            $io->error(sprintf($this->errorMessage[(int)$verdict], $user_id));
        } else {
            $this->entityManagerDatabaseVerdictSet($verdict, $user);
            $io->success(sprintf($this->successMessage[(int)$verdict], $user_id));
        }
    }

    private $successMessage = [
        '1'       =>  'User with id = %d has been successly activated',
        '0'     =>  'User with id = %d has been successly deactivated'
    ];

    private $errorMessage = [
        '1'       =>  'User with id = %d is active',
        '0'     =>  'User with id = %d is deactive',
    ];

    private function entityManagerDatabaseVerdictSet(bool $verdict, User $user)
    {
        $user->setIsActive($verdict);
        $this->em->persist($user);
        $this->em->flush();
    }
}
