<?php

declare(strict_types=1);

namespace App\Domain\Core\UserVideo\Enum;

enum Role: string
{
    // Owner: User who uploaded or created the video. Has full control over the content.
    case OWNER = 'owner'; // Can edit, delete, comment on the video, change its status, etc.

    // Viewer: User who has access to view the video but cannot edit or change its status.
    case VIEWER = 'viewer'; // Can watch the video, like it, comment (if allowed).

    // Editor: User who can edit the video but cannot change its status or delete it.
    case EDITOR = 'editor'; // Can change the description, add tags, modify metadata, but cannot delete the video or change its publication status.

    // Moderator: User who can view and edit the video for moderation but cannot delete it.
    case MODERATOR = 'moderator'; // Can delete comments, pause the video, or hide it if it violates rules.

    // Admin: User with maximum rights on the video, including editing, deleting, publishing, or changing its status.
    case ADMIN = 'admin'; // Can perform any operation on the video, including changing the roles of other users.

    // Contributor: User who can add videos but cannot edit or delete other videos.
    case CONTRIBUTOR = 'contributor'; // Can add new videos to the system but cannot modify or delete existing videos.

    // Restricted: User with very limited rights, such as only viewing certain videos or within specific groups.
    case RESTRICTED = 'restricted'; // Can only watch certain videos or has restricted access based on conditions.

    // Pending: User who is currently waiting for approval or activation of access to the video (e.g., new user or video under review).
    case PENDING = 'pending'; // Created for new users or videos that are not yet published or need review.

    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Owner',
            self::VIEWER => 'Viewer',
            self::EDITOR => 'Editor',
            self::MODERATOR => 'Moderator',
            self::ADMIN => 'Admin',
            self::CONTRIBUTOR => 'Contributor',
            self::RESTRICTED => 'Restricted',
            self::PENDING => 'Pending',
        };
    }
}