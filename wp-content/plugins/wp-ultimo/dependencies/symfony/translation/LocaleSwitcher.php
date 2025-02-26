<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Symfony\Component\Translation;

use WP_Ultimo\Dependencies\Symfony\Component\Routing\RequestContext;
use WP_Ultimo\Dependencies\Symfony\Contracts\Translation\LocaleAwareInterface;
/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class LocaleSwitcher implements LocaleAwareInterface
{
    private string $defaultLocale;
    /**
     * @param LocaleAwareInterface[] $localeAwareServices
     */
    public function __construct(private string $locale, private iterable $localeAwareServices, private ?RequestContext $requestContext = null)
    {
        $this->defaultLocale = $locale;
    }
    public function setLocale(string $locale) : void
    {
        \Locale::setDefault($this->locale = $locale);
        $this->requestContext?->setParameter('_locale', $locale);
        foreach ($this->localeAwareServices as $service) {
            $service->setLocale($locale);
        }
    }
    public function getLocale() : string
    {
        return $this->locale;
    }
    /**
     * Switch to a new locale, execute a callback, then switch back to the original.
     *
     * @template T
     *
     * @param callable():T $callback
     *
     * @return T
     */
    public function runWithLocale(string $locale, callable $callback) : mixed
    {
        $original = $this->getLocale();
        $this->setLocale($locale);
        try {
            return $callback();
        } finally {
            $this->setLocale($original);
        }
    }
    public function reset() : void
    {
        $this->setLocale($this->defaultLocale);
    }
}
