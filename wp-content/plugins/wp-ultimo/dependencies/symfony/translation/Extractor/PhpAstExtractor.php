<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Symfony\Component\Translation\Extractor;

use WP_Ultimo\Dependencies\PhpParser\NodeTraverser;
use WP_Ultimo\Dependencies\PhpParser\NodeVisitor;
use WP_Ultimo\Dependencies\PhpParser\Parser;
use WP_Ultimo\Dependencies\PhpParser\ParserFactory;
use WP_Ultimo\Dependencies\Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Extractor\Visitor\AbstractVisitor;
use Symfony\Component\Translation\MessageCatalogue;
/**
 * PhpAstExtractor extracts translation messages from a PHP AST.
 *
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 */
final class PhpAstExtractor extends \Symfony\Component\Translation\Extractor\AbstractFileExtractor implements \Symfony\Component\Translation\Extractor\ExtractorInterface
{
    private Parser $parser;
    public function __construct(
        /**
         * @param iterable<AbstractVisitor&NodeVisitor> $visitors
         */
        private readonly iterable $visitors,
        private string $prefix = ''
    )
    {
        if (!\class_exists(ParserFactory::class)) {
            throw new \LogicException(\sprintf('You cannot use "%s" as the "nikic/php-parser" package is not installed. Try running "composer require nikic/php-parser".', static::class));
        }
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }
    public function extract(iterable|string $resource, MessageCatalogue $catalogue) : void
    {
        foreach ($this->extractFiles($resource) as $file) {
            $traverser = new NodeTraverser();
            /** @var AbstractVisitor&NodeVisitor $visitor */
            foreach ($this->visitors as $visitor) {
                $visitor->initialize($catalogue, $file, $this->prefix);
                $traverser->addVisitor($visitor);
            }
            $nodes = $this->parser->parse(\file_get_contents($file));
            $traverser->traverse($nodes);
        }
    }
    public function setPrefix(string $prefix) : void
    {
        $this->prefix = $prefix;
    }
    protected function canBeExtracted(string $file) : bool
    {
        return 'php' === \pathinfo($file, \PATHINFO_EXTENSION) && $this->isFile($file);
    }
    protected function extractFromDirectory(array|string $resource) : iterable|Finder
    {
        if (!\class_exists(Finder::class)) {
            throw new \LogicException(\sprintf('You cannot use "%s" as the "symfony/finder" package is not installed. Try running "composer require symfony/finder".', static::class));
        }
        return (new Finder())->files()->name('*.php')->in($resource);
    }
}
