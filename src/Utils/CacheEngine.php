<?php

namespace App\Utils;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class CacheEngine extends TagAwareAdapter
{
    public function __construct(){
        return parent::__construct(new FilesystemAdapter('erc-map'));
    }
}
