<?php


namespace App\Service;


use Demontpx\ParsedownBundle\Parsedown;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownParser
{
    /**
     * @var Parsedown
     */
    private $pasredown;
    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Parsedown $pasredown, AdapterInterface $cache, LoggerInterface $logger)
    {
        $this->pasredown = $pasredown;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function parse(string $source): string
    {
        if(stripos($source, 'кофе') !== false){
            $this->logger->info('Мы нашли кофееее!!!11');
        }

        return $this->cache->get('markdown_' .md5($source), function () use ($source){
            return $this->pasredown->text($source);
        });
    }
}