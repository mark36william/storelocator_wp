<?php

namespace WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Resolvers;

use Psr\Cache\CacheItemPoolInterface;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\DNSRecordCollection;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\DNSRecordType;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\Hostname;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Resolvers\Traits\Time;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Resolvers\Interfaces\Resolver;
use function count;
use function max;
use function md5;
use function min;
use function sprintf;
final class Cached extends ResolverAbstract
{
    use Time;
    protected const DEFAULT_CACHE_TTL = 300;
    private const CACHE_KEY_TEMPLATE = '%s:%s:%s';
    private CacheItemPoolInterface $cache;
    private Resolver $resolver;
    /**
     * Bump this number on breaking changes to invalidate cache
     */
    private const NAMESPACE = 'php-dns-v4.0.1';
    private ?int $ttlSeconds;
    private bool $shouldCacheEmptyResults = \true;
    public function __construct(CacheItemPoolInterface $cache, Resolver $resolver, int $ttlSeconds = null)
    {
        $this->cache = $cache;
        $this->resolver = $resolver;
        $this->ttlSeconds = $ttlSeconds;
    }
    public function flush() : void
    {
        $this->cache->clear();
    }
    public function withEmptyResultCachingDisabled() : self
    {
        $emptyCachingDisabled = new self($this->cache, $this->resolver, $this->ttlSeconds);
        $emptyCachingDisabled->shouldCacheEmptyResults = \false;
        return $emptyCachingDisabled;
    }
    protected function doQuery(Hostname $hostname, DNSRecordType $recordType) : DNSRecordCollection
    {
        $cachedResult = $this->cache->getItem($this->buildCacheKey($hostname, $recordType));
        if ($cachedResult->isHit()) {
            return $this->unwrapResults($cachedResult->get());
        }
        $dnsRecords = $this->resolver->getRecords((string) $hostname, (string) $recordType);
        if ($dnsRecords->isEmpty() && $this->shouldCacheEmptyResults === \false) {
            return $dnsRecords;
        }
        $ttlSeconds = $this->ttlSeconds ?? $this->extractLowestTTL($dnsRecords);
        $cachedResult->expiresAfter($ttlSeconds);
        $cachedResult->set(['recordCollection' => $dnsRecords, 'timestamp' => $this->getTimeStamp()]);
        $this->cache->save($cachedResult);
        return $dnsRecords;
    }
    private function buildCacheKey(Hostname $hostname, DNSRecordType $recordType) : string
    {
        return md5(sprintf(self::CACHE_KEY_TEMPLATE, self::NAMESPACE, (string) $hostname, (string) $recordType));
    }
    private function extractLowestTTL(DNSRecordCollection $recordCollection) : int
    {
        $ttls = [];
        /** @var \RemotelyLiving\PHPDNS\Entities\DNSRecord $record */
        foreach ($recordCollection as $record) {
            /** @scrutinizer ignore-call */
            if ($record->getTTL() <= 0) {
                continue;
            }
            $ttls[] = $record->getTTL();
        }
        return count($ttls) ? min($ttls) : self::DEFAULT_CACHE_TTL;
    }
    /**
     * @param array $results ['recordCollection' => $recordCollection, 'timestamp' => $timeStamp]
     */
    private function unwrapResults(array $results) : DNSRecordCollection
    {
        /** @var DNSRecordCollection $records */
        $records = $results['recordCollection'];
        /**
         * @var int $key
         * @var \RemotelyLiving\PHPDNS\Entities\DNSRecord $record
         */
        foreach ($records as $key => $record) {
            $records[$key] = $record->setTTL(max($record->getTTL() - ($this->getTimeStamp() - (int) $results['timestamp']), 0));
        }
        return $records;
    }
}
