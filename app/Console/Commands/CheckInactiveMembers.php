<?php

namespace App\Console\Commands;

use App\Models\GroupMember;
use App\Models\Warnings;
use App\Models\Announcements;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckInactiveMembers extends Command
{
    protected $signature = 'members:check-inactivity';
    protected $description = 'Warn and blacklist group members who have not communicated within the configured window';

    protected int $hoursForFirstWarning = 48;
    protected int $hoursForSecondWarning = 48;
    protected int $hoursToComplyAfterSecondWarning = 24;
    protected int $blacklistDurationDays = 7;

    public function handle(): void
    {
        $this->liftExpiredBlacklists();

        $members = GroupMember::where('status', 'active')->get();

        foreach ($members as $member) {
            $lastMessageAt = DB::table('messages')
                ->join('topics', 'messages.topic_id', '=', 'topics.id')
                ->where('topics.group_id', $member->group_id)
                ->where('messages.user_id', $member->user_id)
                ->max('messages.created_at');

            // If they've posted since their last warning, they've complied — reset.
            if ($member->inactivity_warning_count > 0 && $lastMessageAt && $member->last_warned_at
                && $lastMessageAt > $member->last_warned_at) {
                $member->update(['inactivity_warning_count' => 0, 'last_warned_at' => null]);
                $this->info("User {$member->user_id} complied in group {$member->group_id}; warnings reset.");
                continue;
            }

            $lastActive = $lastMessageAt ?? $member->created_at;
            $hoursInactive = now()->diffInHours($lastActive);

            if ($member->inactivity_warning_count === 0 && $hoursInactive >= $this->hoursForFirstWarning) {
                $this->issueWarning($member, 1);
            } elseif ($member->inactivity_warning_count === 1
                && $member->last_warned_at
                && now()->diffInHours($member->last_warned_at) >= $this->hoursForSecondWarning) {
                $this->issueWarning($member, 2);
            } elseif ($member->inactivity_warning_count >= 2
                && $member->last_warned_at
                && now()->diffInHours($member->last_warned_at) >= $this->hoursToComplyAfterSecondWarning) {
                $this->blacklist($member);
            }
        }
    }

    protected function issueWarning(GroupMember $member, int $warningNumber): void
    {
        Warnings::create([
            'user_id' => $member->user_id,
            'warning_number' => $warningNumber,
            'reason' => 'Inactivity in group',
            'issued_at' => now(),
            'status' => 'active',
        ]);

        Announcements::create([
            'user_id' => $member->user_id,
            'content' => "Warning #{$warningNumber}: You have received warning #{$warningNumber} for inactivity in your group. Please participate to avoid suspension.",
        ]);

        $member->update([
            'inactivity_warning_count' => $warningNumber,
            'last_warned_at' => now(),
        ]);

        $this->info("Warning #{$warningNumber} issued to user {$member->user_id} in group {$member->group_id}");
    }

    protected function blacklist(GroupMember $member): void
    {
        $member->update([
            'status' => 'blacklisted',
            'blacklisted_until' => now()->addDays($this->blacklistDurationDays),
        ]);

        Announcements::create([
            'user_id' => $member->user_id,
            'content' => "Blacklisted: You have been blacklisted from your group for {$this->blacklistDurationDays} days due to inactivity.",
        ]);

        $this->info("User {$member->user_id} blacklisted from group {$member->group_id} until "
            . now()->addDays($this->blacklistDurationDays)->toDateTimeString());
    }

    protected function liftExpiredBlacklists(): void
    {
        GroupMember::where('status', 'blacklisted')
            ->whereNotNull('blacklisted_until')
            ->where('blacklisted_until', '<=', now())
            ->update([
                'status' => 'active',
                'blacklisted_until' => null,
                'inactivity_warning_count' => 0,
                'last_warned_at' => null,
            ]);
    }
}