<?php

namespace App\DTOs;

class WebPageDataDTO
{
    public function __construct(
        public string $content,
        public ?string $contentType,
        public int $statusCode,
    ) {
    }
}
