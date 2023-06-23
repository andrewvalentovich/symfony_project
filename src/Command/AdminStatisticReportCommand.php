<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminStatisticReportCommand extends Command
{
    protected static $defaultName = 'app:admin-statistic-report';
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * AdminStatisticReportCommand constructor.
     */
    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, Mailer $mailer)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('adminEmail', InputArgument::REQUIRED, 'Argument admin`s email')
            ->addOption(
                'dateFrom',
                null,
                InputOption::VALUE_OPTIONAL,
                'Option date start period',
                new \DateTime('-1 week')
            )
            ->addOption(
                'dateTo',
                null,
                InputOption::VALUE_OPTIONAL,
                'Option date finish period',
                new \DateTime()
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $adminEmail = $input->getArgument('adminEmail');
        $dateFrom = $input->getOption('dateFrom');
        $dateTo = $input->getOption('dateTo');

        $articlesPublished = $this->articleRepository->findAllPublishedByParams($dateFrom, $dateTo);
        $articlesCreated = $this->articleRepository->findAllCreatedByParams($dateFrom, $dateTo);
        $users = $this->userRepository->findAllActive();

        $io->success($adminEmail . "\n" . $dateFrom->format('d.m.Y') . "\n" . $dateTo->format('d.m.Y'));

        $this->createStatistics(
            $dateFrom->format('d.m.Y'),
            $dateTo->format('d.m.Y'),
            $users,
            $articlesCreated,
            $articlesPublished,
        );

        $this->mailer->sendMail(
            $adminEmail,
            '',
            'Spill-Coffee-On-The-Keyboard',
            'email/email-base.html.twig',
            function (TemplatedEmail $email) {
                $email
                    ->attachFromPath('adminFile.xlsx', 'Statistics.xlsx')
                ;
            }
        );

        return Command::SUCCESS;
    }

    protected function createStatistics(
        $dateFrom,
        string $dateTo,
        $users,
        $articlesCreated,
        $articlesPublished
    ) {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $styleArrayFirstRow = [
            'font' => [
                'bold' => true,
            ],
        ];

        $worksheet->getColumnDimension('A',)->setAutoSize(true);
        $worksheet->getColumnDimension('B',)->setAutoSize(true);
        $worksheet->getColumnDimension('C',)->setAutoSize(true);
        $worksheet->getColumnDimension('D',)->setAutoSize(true);

        $worksheet->getCell('A1')->setValue('Период')->getStyle()->applyFromArray($styleArrayFirstRow);
        $worksheet->getCell('A2')->setValue($dateFrom . ' - ' . $dateTo);
        $worksheet->getCell('B1')->setValue('Активных пользователей')->getStyle()->applyFromArray($styleArrayFirstRow);
        $worksheet->getCell('B2')->setValue(count($users));
        $worksheet->getCell('C1')->setValue('Статей создано за период')->getStyle()->applyFromArray($styleArrayFirstRow);
        $worksheet->getCell('C2')->setValue(count($articlesCreated));
        $worksheet->getCell('D1')->setValue('Статей опубликовано за период')->getStyle()->applyFromArray($styleArrayFirstRow);
        $worksheet->getCell('D2')->setValue(count($articlesPublished));

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setPreCalculateFormulas(false);
        $writer->save('adminFile.xlsx');
    }
}
