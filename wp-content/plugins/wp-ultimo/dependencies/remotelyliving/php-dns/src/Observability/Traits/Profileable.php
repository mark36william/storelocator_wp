<?php

namespace WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Observability\Traits;

use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Observability\Performance\Profile;
use WP_Ultimo\Dependencies\RemotelyLiving\PHPDNS\Observability\Performance\ProfileFactory;
trait Profileable
{
    private ?ProfileFactory $profileFactory = null;
    public function createProfile(string $transactionName) : Profile
    {
        return $this->getProfileFactory()->create($transactionName);
    }
    public function setProfileFactory(ProfileFactory $profileFactory) : void
    {
        $this->profileFactory = $profileFactory;
    }
    private function getProfileFactory() : ProfileFactory
    {
        if ($this->profileFactory === null) {
            $this->profileFactory = new ProfileFactory();
        }
        return $this->profileFactory;
    }
}
