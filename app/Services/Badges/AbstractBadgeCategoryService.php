<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Interfaces\BadgeCategoryInterface;
use App\Models\User;

abstract class AbstractBadgeCategoryService implements BadgeCategoryInterface
{
    protected $badgeAssigner;
    protected $rules = [];

    public function __construct(BadgeAssignerInterface $badgeAssigner, array $rules)
    {
        $this->badgeAssigner = $badgeAssigner;
        $this->rules = $rules;
    }

    public function checkAndAssignBadges(User $user): void
    {
        foreach ($this->rules as $rule) {
            if ($rule->appliesTo($user)) {
                $this->badgeAssigner->assignBadge($user, $rule->getBadgeName());
            }
        }
    }
}
