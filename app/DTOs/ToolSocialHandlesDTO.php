<?php

namespace App\DTOs;

class ToolSocialHandlesDTO
{
    public function __construct(
        public ?string $tiktokUserId = null,
        public ?string $twitterUserId = null,
        public ?string $facebookUserId = null,
        public ?string $behanceUserId = null,
        public ?string $dribbbleUserId = null,
        public ?string $instagramUserId = null,
        public ?string $youtubeChannelId = null,
        public ?string $pinterestUserId = null,
        public ?string $linkedinCompanyId = null,
        public ?string $telegramChannelId = null,
        public ?string $subredditId = null,
        public ?string $discordChannelInviteId = null,

        public ?string $emailId = null,

        public ?string $androidApId = null,
        public ?string $IOSAppID = null,
        public ?string $windowStoreId = null,
        public ?string $macStoreId = null,

        public ?string $chromeExtensionId = null,
        public ?string $firefoxExtensionId = null,

        public ?string $githubRepositoryPath = null,
    ) {
    }

    public static function fromArray(array $array): self
    {
        return new ToolSocialHandlesDTO(
            tiktokUserId: $array['tiktok'] ?? null,
            twitterUserId: $array['twitter'] ?? null,
            facebookUserId: $array['facebook'] ?? null,
            dribbbleUserId: $array['dribbble'] ?? null,
            behanceUserId: $array['behance'] ?? null,
            instagramUserId: $array['instagram'] ?? null,
            youtubeChannelId: $array['youtube_channel'] ?? null,
            pinterestUserId: $array['pinterest'] ?? null,
            linkedinCompanyId: $array['linkedin_company'] ?? null,
            telegramChannelId: $array['telegram_channel_id'] ?? null,
            subredditId: $array['subreddit_id'] ?? null,
            discordChannelInviteId: $array['discord_channel_invite_id'] ?? null,

            emailId: $array['email'] ?? null,

            androidApId: $array['android_app'] ?? null,
            IOSAppID: $array['ios_app'] ?? null,
            macStoreId: $array['mac_store_id'] ?? null,
            windowStoreId: $array['window_store_id'] ?? null,

            chromeExtensionId: $array['chrome_extension_id'] ?? null,
            firefoxExtensionId: $array['firefox_extension_id'] ?? null,

            githubRepositoryPath: $array['github_repository_path'] ?? null,
        );
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        return new ToolSocialHandlesDTO(
            tiktokUserId: $data['tiktokUserId'] ?? null,
            twitterUserId: $data['twitterUserId'] ?? null,
            facebookUserId: $data['facebookUserId'] ?? null,
            dribbbleUserId: $data['dribbbleUserId'] ?? null,
            behanceUserId: $data['behanceUserId'] ?? null,
            instagramUserId: $data['instagramUserId'] ?? null,
            youtubeChannelId: $data['youtubeChannelId'] ?? null,
            pinterestUserId: $data['pinterestUserId'] ?? null,
            linkedinCompanyId: $data['linkedinCompanyId'] ?? null,
            telegramChannelId: $data['telegramChannelId'] ?? null,
            subredditId: $data['subredditId'] ?? null,
            discordChannelInviteId: $data['discordChannelInviteId'] ?? null,

            emailId: $data['emailId'] ?? null,

            androidApId: $data['androidApId'] ?? null,
            IOSAppID: $data['IOSAppID'] ?? null,
            macStoreId: $data['macStoreId'] ?? null,
            windowStoreId: $data['windowStoreId'] ?? null,

            chromeExtensionId: $data['chromeExtensionId'] ?? null,
            firefoxExtensionId: $data['firefoxExtensionId'] ?? null,

            githubRepositoryPath: $data['githubRepositoryPath'] ?? null,
        );
    }
}
