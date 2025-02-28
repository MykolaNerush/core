<?php

declare(strict_types=1);

namespace App\Domain\Core\Video\Enum;

enum Status: string
{
    // Active statuses
    case PUBLISHED = 'published'; // Video is available for viewing
    case DRAFT = 'draft'; // Video is not ready for publication
    case IN_REVIEW = 'in_review'; // Video is awaiting review before publication
    case SCHEDULED = 'scheduled'; // Video is scheduled for publication at a specific time

    // Availability statuses
    case HIDDEN = 'hidden'; // Video is hidden from public view but still exists
    case UNAVAILABLE = 'unavailable'; // Video is not available for viewing (e.g., due to technical issues or restrictions)
    case DISABLED = 'disabled'; // Video is disabled and no longer available for viewing

    // Access rights statuses
    case RESTRICTED = 'restricted'; // Access to the video is restricted by certain conditions (e.g., subscription, geographic location)
    case GROUP_ONLY = 'group_only'; // Video is available only to a specific group of users (e.g., friends, premium users)
    case PRIVATE = 'private'; // Video is available only to a specific circle of people (e.g., only the author or by individual invitation)

    // Content or processing statuses
    case PROCESSING = 'processing'; // Video is uploaded but not yet ready for viewing due to processing (e.g., format conversion or thumbnail generation)
    case TRANSCODING = 'transcoding'; // Video is being converted to other formats or resolutions to support different devices
    case UPLOADING = 'uploading'; // Video is still being uploaded to the server

    // Monetization statuses
    case MONETIZED = 'monetized'; // Video is available for monetization through ads or other means
    case NOT_MONETIZED = 'not_monetized'; // Video is not monetized

    // Other special statuses
    case TEMPORARILY_UNAVAILABLE = 'temporarily_unavailable'; // Video is temporarily unavailable (e.g., due to technical issues or rights problems)
    case BANNED = 'banned'; // Video is removed due to violations of terms of use or copyright

    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => 'Published',
            self::DRAFT => 'Draft',
            self::IN_REVIEW => 'In Review',
            self::SCHEDULED => 'Scheduled',
            self::HIDDEN => 'Hidden',
            self::UNAVAILABLE => 'Unavailable',
            self::DISABLED => 'Disabled',
            self::RESTRICTED => 'Restricted',
            self::GROUP_ONLY => 'Group Only',
            self::PRIVATE => 'Private',
            self::PROCESSING => 'Processing',
            self::TRANSCODING => 'Transcoding',
            self::UPLOADING => 'Uploading',
            self::MONETIZED => 'Monetized',
            self::NOT_MONETIZED => 'Not Monetized',
            self::TEMPORARILY_UNAVAILABLE => 'Temporarily Unavailable',
            self::BANNED => 'Banned',
        };
    }
}
