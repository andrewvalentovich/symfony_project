<?php


namespace App\Homework;


use App\Service\MarkdownParser;

class ArticleProvider
{

    public function articles()
    {
        $content_data = array(
            array(
                'title'     => 'Что делать, если надо верстать?',
                'slug'      => '1',
                'image'     => './images/article-1.jpeg'
            ),
            array(
                'title'     => 'Facebook ест твои данные',
                'slug'      => '2',
                'image'     => './images/article-2.jpeg'
            ),
            array(
                'title'     => 'Когда пролил кофе на клавиатуру',
                'slug'      => '3',
                'image'     => './images/article-3.jpg'
            ),
        );
        return $content_data;
    }

    public function article()
    {
        $content_array = $this->articles();
        $random_number = rand(0, count($content_array)-1);
        return $content_array[$random_number];
    }
}