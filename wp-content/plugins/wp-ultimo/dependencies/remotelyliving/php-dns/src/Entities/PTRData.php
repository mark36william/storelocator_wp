<?php

namespace WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities;

use function serialize;
use function unserialize;
final class PTRData extends DataAbstract
{
    private Hostname $hostname;
    public function __construct(Hostname $hostname)
    {
        $this->hostname = $hostname;
    }
    public function __toString() : string
    {
        return (string) $this->hostname;
    }
    public function getHostname() : Hostname
    {
        return $this->hostname;
    }
    public function toArray() : array
    {
        return ['hostname' => (string) $this->hostname];
    }
    public function serialize() : string
    {
        return serialize($this->toArray());
    }
    /**
     * @param string $serialized
     */
    public function unserialize($serialized) : void
    {
        $unserialized = unserialize($serialized);
        $this->hostname = new Hostname($unserialized['hostname']);
    }
}
