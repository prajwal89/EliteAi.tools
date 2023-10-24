<?php

namespace App\Services;

use App\Enums\UtmCampaign;
use App\Enums\UtmSource;

class UtmQueryBuilder
{
    public function __construct(
        public UtmSource $source,
        public string $medium,
        public UtmCampaign $campaign,
    ) {
    }

    public function build(): string
    {
        return http_build_query([
            'utm_source' => $this->source->value,
            'utm_medium' => $this->medium,
            'utm_campaign' => $this->campaign->value,
        ]);
    }
}
