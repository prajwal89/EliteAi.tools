<?php

namespace App\DTOs;

class PageDataDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public string $conicalUrl,
        public ?string $thumbnailUrl = null,
    ) {
    }
}
