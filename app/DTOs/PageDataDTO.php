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

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'canonicalUrl' => $this->conicalUrl,
            'thumbnailUrl' => $this->thumbnailUrl,
        ];
    }
}
