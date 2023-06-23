<?php


namespace App\Homework\PasteWords;


class PasteWords
{
    public function paste(string $text, string $word, int $wordsCount = 1): string
    {

        if ($word != null) {

            for ($i = 0; $i < $wordsCount; $i++) {
                $text = substr_replace(
                    $text,
                    ' '.$word,
                    stripos($text, ' ', rand(0, iconv_strlen($text, 'UTF-8'))),
                    0
                );
            }

        }

        return $text;
    }
}