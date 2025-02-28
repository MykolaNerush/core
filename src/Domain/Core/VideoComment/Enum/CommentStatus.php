<?php

declare(strict_types=1);

namespace App\Domain\Core\VideoComment\Enum;

enum CommentStatus: string
{
    // Pending: Comment has not yet been reviewed or approved for publication.
    case PENDING = 'pending'; // Example: The comment has been submitted but not yet approved.

    // Approved: Comment has been reviewed and approved for publication.
    case APPROVED = 'approved'; // Example: The comment is available to all users on the platform.

    // Rejected: Comment has been rejected due to rule violations or by moderation.
    case REJECTED = 'rejected'; // Example: The comment does not meet community standards and has been removed or blocked.

    // Spam: Comment is recognized as spam and has been removed or marked as unwanted.
    case SPAM = 'spam'; // Example: The comment has spam characteristics, such as excessive advertising content or illegitimate links.

    // Deleted: Comment has been deleted either by the user or an administrator.
    case DELETED = 'deleted'; // Example: The comment no longer exists in the system but may be stored in an archive or log.

    // Flagged: Comment has been flagged by other users for potential moderation. This usually means the comment needs further review.
    case FLAGGED = 'flagged'; // Example: A user noticed that the comment violates rules and flagged it for review.

    // Resolved: Status for a comment that was flagged (e.g., marked as spam or violation) but has been resolved after moderation.
    case RESOLVED = 'resolved'; // Example: The comment that was flagged has been reviewed and its status has been changed according to community rules.

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::SPAM => 'Spam',
            self::DELETED => 'Deleted',
            self::FLAGGED => 'Flagged',
            self::RESOLVED => 'Resolved',
        };
    }
}