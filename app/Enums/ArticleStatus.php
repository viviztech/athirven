<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Full editorial state machine (transitions enforced by ArticleWorkflowService
 * + ArticlePolicy starting Phase 2). Phase 1 only uses this as a plain
 * attribute on the basic Filament CRUD form.
 */
enum ArticleStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case InReview = 'in_review';
    case Approved = 'approved';
    case NeedsRevision = 'needs_revision';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::InReview => 'In Review',
            self::Approved => 'Approved',
            self::NeedsRevision => 'Needs Revision',
            self::Scheduled => 'Scheduled',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }
}
