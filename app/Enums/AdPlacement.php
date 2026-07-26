<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AdPlacement: string implements HasLabel
{
    case HomepageBanner = 'homepage_banner';
    case Sidebar = 'sidebar';
    case IssueSponsor = 'issue_sponsor';
    case Newsletter = 'newsletter';

    public function getLabel(): string
    {
        return match ($this) {
            self::HomepageBanner => 'Homepage Banner',
            self::Sidebar => 'Sidebar',
            self::IssueSponsor => 'Issue Sponsor',
            self::Newsletter => 'Newsletter',
        };
    }
}
