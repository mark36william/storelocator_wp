<?php

namespace WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Entities;

use function serialize;
use function unserialize;
final class TXTData extends DataAbstract
{
    private string $value;
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    public function __toString() : string
    {
        return $this->value;
    }
    public function getValue() : string
    {
        return $this->value;
    }
    public function toArray() : array
    {
        return ['value' => $this->value];
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
        $this->value = $unserialized['value'];
    }
}
