<?php

namespace App\Twig;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AgoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('ago', [$this, 'getDiff'], ['is_safe' => ['html']]),
        ];
    }

    public function getDiff($value)
    {
        return Carbon::parse($value)->locale('ru')->diffForHumans();
    }
}
