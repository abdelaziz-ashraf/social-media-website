<?php

namespace App\Services;

use App\Http\Responses\SuccessResponse;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\RequestJoinGroup;
use App\Models\User;
use App\Notifications\RequestToJoinGroupNotification;
use App\Notifications\RoleUpdatedNotification;
use App\Notifications\WelcomeToGroupNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class GroupService
{
    public function getGroups() {
        $userGroupIds = auth()->user()->groups()->pluck('groups.id');
        return Group::whereNotIn('id', $userGroupIds)->get();
    }

    public function createGroup($data) {
        return DB::transaction(function () use ($data) {
            $group = Group::create($data);
            GroupUser::create([
                'group_id' => $group->id,
                'user_id' => auth()->id(),
                'role' => 'owner'
            ]);
            return $group;
        });
    }

    public function deleteGroup(Group $group) {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
            ->where('role', 'owner')->first();
        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }
        $group->delete();
    }

    public function joinGroup(Group $group) {
        if($group->auto_approval) {
            RequestJoinGroup::create([
                'user_id' => auth()->id(),
                'group_id' => $group->id,
            ]);
            $owner = $group->users()->where('role', 'owner')->first();
            $owner->user->notify(new RequestToJoinGroupNotification($group));
            return SuccessResponse::send('Requested To Join Successfully');
        }
        GroupUser::create([
            'user_id' => auth()->id(),
            'group_id' => $group->id,
        ]);
        auth()->user()->notify(new WelcomeToGroupNotification($group));
    }

    public function approveJoinRequest (Group $group, User $user) {
        $joinRequest = $this->canApproveRejectRequest($group->id, $user->id);
        DB::transaction(function () use ($group, $user, $joinRequest) {
            GroupUser::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
            ]);
            auth()->user()->notify(new WelcomeToGroupNotification($group->name));
            $joinRequest->delete();
        });
    }

    public function rejectJoinRequest (Group $group, User $user) {
        $joinRequest = $this->canApproveRejectRequest($group->id, $user->id);
        $joinRequest->delete();
    }

    private function canApproveRejectRequest($group_id, $user_id) {
        $admin = GroupUser::where('group_id', request()->route('group')->id)
            ->whereIn('role', ['owner', 'admin'])->first();
        if(is_null($admin) || $admin->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }

        $joinRequest = RequestJoinGroup::where('user_id', $user_id)->where('group_id', $group_id)->first();
        if(is_null($joinRequest)) {
            throw ValidationException::withMessages(['request not found']);
        }

        return $joinRequest;
    }

    public function updateAdminRole (Group $group, $user_id, $role) {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
            ->where('role', 'owner')->first();
        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }

        $groupUser = GroupUser::where('group_id', $group->id)
            ->where('user_id', $user_id)->first();
        if (!$groupUser) {
            return SuccessResponse::send('User not found in this group');
        }

        if (!in_array($role, ['admin', 'user'])) {
            return SuccessResponse::send('Invalid role provided');
        }

        $groupUser->role = $role;
        $groupUser->save();
        $groupUser->user->notify(new RoleUpdatedNotification($group->name, $role));

        return $groupUser;
    }
}
