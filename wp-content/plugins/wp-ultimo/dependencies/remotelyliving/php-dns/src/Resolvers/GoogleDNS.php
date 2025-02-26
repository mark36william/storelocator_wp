<?php

namespace WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Resolvers;

use WP_Ultimo\Dependencies\GuzzleHttp\Client;
use WP_Ultimo\Dependencies\GuzzleHttp\ClientInterface;
use WP_Ultimo\Dependencies\GuzzleHttp\Exception\RequestException;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\DNSRecord;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\DNSRecordCollection;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\Interfaces\DNSRecordInterface;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\DNSRecordType;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities\Hostname;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Mappers\GoogleDNS as GoogleDNSMapper;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Resolvers\Exceptions\QueryFailure;
use Throwable;
use function http_build_query;
use function json_decode;
final class GoogleDNS extends ResolverAbstract
{
    protected const BASE_URI = 'https://dns.google.com';
    protected const DEFAULT_TIMEOUT = 5.0;
    public const DEFAULT_OPTIONS = ['base_uri' => self::BASE_URI, 'strict' => \true, 'allow_redirects' => \false, 'connect_timeout' => self::DEFAULT_TIMEOUT, 'protocols' => ['https'], 'headers' => ['Accept' => 'application/json']];
    private ClientInterface $http;
    private GoogleDNSMapper $mapper;
    private int $consensusAttempts;
    /**
     * @var array<string, mixed>
     */
    private array $options;
    public function __construct(ClientInterface $http = null, GoogleDNSMapper $mapper = null, int $consensusAttempts = 3, array $options = self::DEFAULT_OPTIONS)
    {
        $this->http = $http ?? new Client();
        $this->mapper = $mapper ?? new GoogleDNSMapper();
        $this->consensusAttempts = $consensusAttempts;
        $this->options = $options;
    }
    /**
     * Google DNS has consistency issues so this tries a few times to get an answer
     */
    public function hasRecord(DNSRecordInterface $record) : bool
    {
        $attempts = 0;
        do {
            $hasRecord = $this->getRecords((string) $record->getHostname(), (string) $record->getType())->has($record);
            ++$attempts;
        } while ($hasRecord === \false && $attempts < $this->consensusAttempts);
        return $hasRecord;
    }
    protected function doQuery(Hostname $hostname, DNSRecordType $recordType) : DNSRecordCollection
    {
        $results = $this->doApiQuery(['name' => (string) $hostname, 'type' => (string) $recordType]);
        return $this->mapResults($this->mapper, $results);
    }
    /**
     * @throws \RemotelyLiving\PHPDNS\Resolvers\Exceptions\QueryFailure
     */
    private function doApiQuery(array $query = []) : array
    {
        try {
            $response = $this->http->request('GET', '/resolve?' . http_build_query($query), $this->options);
        } catch (Throwable $e) {
            throw new QueryFailure("Unable to query GoogleDNS API", 0, $e);
        }
        $result = (array) json_decode((string) $response->getBody(), \true);
        if (isset($result['Answer'])) {
            return $result['Answer'];
        }
        if (isset($result['Authority'])) {
            return $result['Authority'];
        }
        return [];
    }
}
