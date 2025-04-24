<?php

namespace AgeChecker\Subscriber;

use Shopware\Core\Framework\Struct\Struct;

class ApiKeyStruct extends Struct
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
