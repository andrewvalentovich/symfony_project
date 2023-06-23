<?php

namespace App\Command;

use App\Homework\ArticleContentProvider;
#use App\Homework\ArticleContentProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
#use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArticleContentProviderCommand extends Command
{
    protected static $defaultName = 'app:article:content_provider';
    protected static $defaultDescription = 'This command returns service ArticleContentProvider function get result';

    private $articleContentProvider;

    /**
     * ArticleContentProviderCommand constructor.
     */

    public function __construct(ArticleContentProvider $articleContentProvider)
    {
        parent::__construct();
        $this->articleContentProvider = $articleContentProvider;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Эта команда выводит результат работы функции get сервиса ArticleContentProvider')
            ->addArgument('paragraphs', InputArgument::REQUIRED, 'Paragraphs count')
            ->addArgument('word', InputArgument::OPTIONAL, 'Word that be input in the text')
            ->addArgument('wordsCount', InputArgument::OPTIONAL, 'Count of putting of the word $word')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $paragraphs = $input->getArgument('paragraphs');
        $word = $input->getArgument('word');
        $wordsCount = $input->getArgument('wordsCount');

        if ($paragraphs) {
            $io->note(sprintf('You passed an argument $paragraphs: %s', $paragraphs));
        }
        if ($word) {
            $io->note(sprintf('You passed an argument $word: %s', $word));
        }
        if ($wordsCount) {
            $io->note(sprintf('You passed an argument $wordsCount: %s', $wordsCount));
        }

        $io->writeln($this->articleContentProvider->get($paragraphs, $word, $wordsCount));
        $io->success('Function successfully completed');

        return Command::SUCCESS;
    }
}
