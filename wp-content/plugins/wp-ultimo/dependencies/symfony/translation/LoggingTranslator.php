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

use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Exception\InvalidArgumentException;
use WP_Ultimo\Dependencies\Symfony\Contracts\Translation\LocaleAwareInterface;
use WP_Ultimo\Dependencies\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class LoggingTranslator implements TranslatorInterface, \Symfony\Component\Translation\TranslatorBagInterface, LocaleAwareInterface
{
    private TranslatorInterface $translator;
    private LoggerInterface $logger;
    /**
     * @param TranslatorInterface&TranslatorBagInterface&LocaleAwareInterface $translator The translator must implement TranslatorBagInterface
     */
    public function __construct(TranslatorInterface $translator, LoggerInterface $logger)
    {
        if (!$translator instanceof \Symfony\Component\Translation\TranslatorBagInterface || !$translator instanceof LocaleAwareInterface) {
            throw new InvalidArgumentException(\sprintf('The Translator "%s" must implement TranslatorInterface, TranslatorBagInterface and LocaleAwareInterface.', \get_debug_type($translator)));
        }
        $this->translator = $translator;
        $this->logger = $logger;
    }
    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null) : string
    {
        $trans = $this->translator->trans($id = (string) $id, $parameters, $domain, $locale);
        $this->log($id, $domain, $locale);
        return $trans;
    }
    public function setLocale(string $locale)
    {
        $prev = $this->translator->getLocale();
        $this->translator->setLocale($locale);
        if ($prev === $locale) {
            return;
        }
        $this->logger->debug(\sprintf('The locale of the translator has changed from "%s" to "%s".', $prev, $locale));
    }
    public function getLocale() : string
    {
        return $this->translator->getLocale();
    }
    public function getCatalogue(string $locale = null) : \Symfony\Component\Translation\MessageCatalogueInterface
    {
        return $this->translator->getCatalogue($locale);
    }
    public function getCatalogues() : array
    {
        return $this->translator->getCatalogues();
    }
    /**
     * Gets the fallback locales.
     */
    public function getFallbackLocales() : array
    {
        if ($this->translator instanceof \Symfony\Component\Translation\Translator || \method_exists($this->translator, 'getFallbackLocales')) {
            return $this->translator->getFallbackLocales();
        }
        return [];
    }
    /**
     * Passes through all unknown calls onto the translator object.
     */
    public function __call(string $method, array $args)
    {
        return $this->translator->{$method}(...$args);
    }
    /**
     * Logs for missing translations.
     */
    private function log(string $id, ?string $domain, ?string $locale)
    {
        $domain ??= 'messages';
        $catalogue = $this->translator->getCatalogue($locale);
        if ($catalogue->defines($id, $domain)) {
            return;
        }
        if ($catalogue->has($id, $domain)) {
            $this->logger->debug('Translation use fallback catalogue.', ['id' => $id, 'domain' => $domain, 'locale' => $catalogue->getLocale()]);
        } else {
            $this->logger->warning('Translation not found.', ['id' => $id, 'domain' => $domain, 'locale' => $catalogue->getLocale()]);
        }
    }
}
