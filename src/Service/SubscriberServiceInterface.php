<?php

namespace App\Service;

use App\Model\SubscriberRequest;

interface SubscriberServiceInterface
{
    public function subscribe(SubscriberRequest $request): void;
}
