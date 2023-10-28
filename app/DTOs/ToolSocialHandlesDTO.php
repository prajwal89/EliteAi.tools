<?php

namespace App\DTOs;

class ToolSocialHandlesDTO
{
    public function __construct(
        public ?string $tiktokUserId = null,
        public ?string $twitterUserId = null,
        public ?string $facebookUserId = null,
        public ?string $instagramUserId = null,
        public ?string $youtubeChannelId = null,
        public ?string $linkedinCompanyId = null,
        public ?string $emailId = null,
    ) {
    }

    public static function fromArray(array $array): self
    {
        return new ToolSocialHandlesDTO(
            tiktokUserId: $array['tiktok'] ?? null,
            twitterUserId: $array['twitter'] ?? null,
            facebookUserId: $array['facebook'] ?? null,
            instagramUserId: $array['instagram'] ?? null,
            youtubeChannelId: $array['youtube_channel'] ?? null,
            linkedinCompanyId: $array['linkedin_company'] ?? null,
            emailId: $array['email'] ?? null,
        );
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        return new ToolSocialHandlesDTO(
            tiktokUserId: $data['tiktokUserId'] ?? null,
            twitterUserId: $data['twitterUserId'] ?? null,
            facebookUserId: $data['facebookUserId'] ?? null,
            instagramUserId: $data['instagramUserId'] ?? null,
            youtubeChannelId: $data['youtubeChannelId'] ?? null,
            linkedinCompanyId: $data['linkedinCompanyId'] ?? null,
            emailId: $data['emailId'] ?? null
        );
    }
}
