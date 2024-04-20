<?php

namespace App\Repository;

use App\Models\WebsiteSubscriber;
use App\Repository\Interfaces\WebsiteSubscriberRepositoryInterface;

class WebsiteSubscriberRepository implements WebsiteSubscriberRepositoryInterface
{

    public function create(array $data): void
    {
        WebsiteSubscriber::create($data);
    }
}
