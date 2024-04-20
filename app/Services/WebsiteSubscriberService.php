<?php

namespace App\Services;

use App\Repository\Interfaces\WebsiteSubscriberRepositoryInterface;

class WebsiteSubscriberService
{


    public function __construct(private readonly WebsiteSubscriberRepositoryInterface $websiteSubscriberRepository)
    {
    }

    public function addWebsiteSubscriber(array $data): void
    {
        $this->websiteSubscriberRepository->create($data);
    }

}
