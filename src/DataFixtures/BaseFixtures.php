<?php

namespace App\DataFixtures;
use App\Entity\Article;
use App\Homework\ArticleContentProvider;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BaseFixtures extends Fixture
{
    static $titleArray = [
        'Три предметно-ориентированных языка программирования для цифровой обработки сигналов',
        'Как составить ТЗ, чтобы не пришлось икать?',
        'Как создать 3d игру прямо в браузере'
    ];

    static $authorArray = [
        'Владимир Петрушкин',
        'Александр Правильный',
        'Петр Создатель'
    ];

    static $filenameArray = [
        'images/article-1.jpeg',
        'images/article-2.jpeg',
        'images/article-3.jpg'
    ];

    /**
     * BaseFixtures constructor.
     */
    private $articleContentProvider;

    public function __construct(ArticleContentProvider $articleContentProvider)
    {
        $this->articleContentProvider = $articleContentProvider;
    }

    public function generateContent():string
    {
        $wordArray = ['статья', 'новость', 'ИТ', 'технологии', 'инженерия', 'роботы', 'производство', 'хакинг'];

        $word = (rand(0, 10) <= 7) ? $wordArray[rand(0, 7)] : null;

        $contentText = $this->articleContentProvider->get(rand(1, 2), $word, rand(2, 12));

        if (strlen($contentText) >= 990) {
            $contentText = substr($contentText, 0, stripos($contentText, ' ', 990));
        }

        return $contentText;
    }

    public function generateKeywords(int $count = 0):string
    {
        $wordArray = ['статья', 'новость', 'ИТ', 'технологии', 'инженерия', 'роботы', 'производство', 'хакинг'];

        for ($i = 0; $i < $count; $i++) {
            $text = $wordArray[$i] . ' ';
        }

        return $text;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();

            $article
                ->setTitle($faker->randomElement(self::$titleArray))
                ->setDescription($faker->realText(100, 1))
                ->setBody($this->generateContent())
                ->setKeywords($this->generateKeywords($faker->numberBetween(1, 4)));

            if ($faker->boolean(60)) {
                $article->setPublishedAt($faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article
                ->setAuthor($faker->randomElement(self::$authorArray))
                ->setVoteCount(rand(0, 10))
                ->setImageFilename($faker->randomElement(self::$filenameArray))
            ;

            $manager->persist($article);
        }

        $manager->flush();
    }


}
