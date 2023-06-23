<?php


namespace App\Homework;


class ArticleWordsFilter
{
    public function filter($string, $words = [])
    {
        for ($i = 0; $i < count($words); $i++) {
            $string = trim(preg_replace('/[\s]?[^\s]*[а-яА-я]?(?i)('.$words[$i].')[а-яА-я]?[^\s]*[\s]?+/uim', ' ', $string));
            $string = trim(preg_replace('/[\s]{2,}/', ' ', $string));
        }

        return $string;
    }
}