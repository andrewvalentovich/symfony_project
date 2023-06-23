<?php


namespace App\Twig;

use App\Service\MarkdownParser;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{

    /**
     * @var MarkdownParser
     */
    private $markdownParser;


    /**
     * AppExtension constructor.
     */
    public function __construct(MarkdownParser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    public function doMarkdown($contentText)
    {
        return $contentText = $this->markdownParser->parse($contentText);
    }
}