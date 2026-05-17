<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Member;
use Illuminate\Support\Facades\Log;

class GroupService
{
    /**
     * Automatically assign a member to communities based on defined criteria.
     */
    public function autoAssignMemberToCommunities(Member $member)
    {
        $communities = Group::where('type', 'Community')
            ->where('is_active', true)
            ->get();

        foreach ($communities as $community) {
            $criteria = $community->criteria;
            
            $matches = false;
            if (!empty($criteria)) {
                $matches = $this->memberMatchesCriteria($member, $criteria);
            }

            if ($matches) {
                $this->assignMemberToGroup($member, $community);
            } else {
                // If they were in it but no longer match criteria, remove them
                // BUT ONLY if it's a community (which we already filtered for)
                $this->removeMemberFromGroupIfAutoAssigned($member, $community);
            }
        }
    }

    /**
     * Check if a member matches the given criteria.
     */
    protected function memberMatchesCriteria(Member $member, array $criteria)
    {
        foreach ($criteria as $field => $value) {
            if (empty($value)) continue;

            // Support for address matching (case-insensitive contains)
            if ($field === 'address') {
                if (!str_contains(strtolower($member->address ?? ''), strtolower($value))) {
                    return false;
                }
            } elseif ($field === 'category_id') {
                // Support for Member Category ID matching
                if ($member->category_id != $value) {
                    return false;
                }
            } elseif ($field === 'program_id') {
                // Support for Program ID matching
                if ($member->program_id != $value) {
                    return false;
                }
            } else {
                // Exact match for other fields (region, diocese, parish, etc.)
                // Use data_get to handle potential nested or attribute access
                if ($member->{$field} != $value) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Assign a member to a group if not already a member.
     */
    protected function assignMemberToGroup(Member $member, Group $group)
    {
        if (!$member->groups()->where('groups.id', $group->id)->exists()) {
            $member->groups()->attach($group->id, [
                'join_date' => now(),
                'is_active' => true,
            ]);
            
            Log::info("Member #{$member->id} automatically assigned to Community: {$group->name}");
        }
    }

    /**
     * Remove a member from a group if they no longer match criteria.
     */
    protected function removeMemberFromGroupIfAutoAssigned(Member $member, Group $group)
    {
        if ($member->groups()->where('groups.id', $group->id)->exists()) {
            $member->groups()->detach($group->id);
            Log::info("Member #{$member->id} automatically removed from Community: {$group->name} (criteria no longer matches)");
        }
    }
}
