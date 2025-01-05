<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\Group\GroupDetailsResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Group\GroupUsersResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\RequestJoinGroup;
use App\Models\User;
use App\Notifications\RequestToJoinGroupNotification;
use App\Notifications\RoleUpdatedNotification;
use App\Notifications\WelcomeToGroupNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    public function index() {
        $userGroupIds = auth()->user()->groups()->pluck('groups.id');
        $groupsNotMember = Group::whereNotIn('id', $userGroupIds)->get();
        return SuccessResponse::send('Groups', GroupResource::collection($groupsNotMember));
    }

    public function show(Group $group) {
        return SuccessResponse::send('Group', GroupDetailsResource::make($group));
    }

    public function members(Group $group) {
        $groupMembers = $group->users()->get();
        return SuccessResponse::send('Members', GroupUsersResource::collection($groupMembers));
    }

    public function store(StoreGroupRequest $request) {
        $data = $request->validated();
        $group = Group::create($data);
        GroupUser::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'role' => 'owner'
        ]);
        return SuccessResponse::send('Group Created Successfully', GroupResource::make($group));
    }

    public function update(UpdateGroupRequest $request, Group $group) {
        $group->update($request->validated());
        return SuccessResponse::send('Group Updated Successfully', GroupResource::make($group));
    }

    public function destroy(Group $group) {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
            ->where('role', 'owner')->first();
        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }
        $group->delete();
        return SuccessResponse::send('Group Deleted Successfully');
    }

    public function join(Group $group) {
        if($group->auto_approval) {
            RequestJoinGroup::create([
                'user_id' => auth()->id(),
                'group_id' => $group->id,
            ]);
            $owner = $group->users()->where('role', 'owner')->first();
            $owner->notify(new RequestToJoinGroupNotification($group));
            return SuccessResponse::send('Requested To Join Successfully');
        }
        GroupUser::create([
            'user_id' => auth()->id(),
            'group_id' => $group->id,
        ]);
        auth()->user()->notify(new WelcomeToGroupNotification($group));
        return SuccessResponse::send('Group Joined Successfully');
    }

    public function approveRequest(Group $group, User $user) {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
                ->whereIn('role', ['owner', 'admin'])->first();
        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }

        $joinRequest = RequestJoinGroup::where('user_id', $user->id)->where('group_id', $group->id)->first();
        if(is_null($joinRequest)) {
            throw ValidationException::withMessages(['request not found']);
        }
        GroupUser::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        auth()->user()->notify(new WelcomeToGroupNotification($group->name));
        $joinRequest->delete();
        return SuccessResponse::send('Request Approved Successfully');
    }

    public function rejectRequest(Group $group, User $user) {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
            ->whereIn('role', ['owner', 'admin'])->first();
        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }

        $joinRequest = RequestJoinGroup::where('user_id', $user->id)->where('group_id', $group->id)->first();
        if(is_null($joinRequest)) {
            throw ValidationException::withMessages(['request not found']);
        }
        $joinRequest->delete();
        return SuccessResponse::send('Request Rejected Successfully');
    }

    public function updateAdminRole(Request $request, Group $group, $userId) {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
            ->where('role', 'owner')->first();
        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            throw new UnauthorizedException();
        }

        $groupUser = GroupUser::where('group_id', $group->id)
            ->where('user_id', $userId)->first();
        if (!$groupUser) {
            return SuccessResponse::send('User not found in this group');
        }

        $role = $request->input('role');
        if (!in_array($role, ['admin', 'user'])) {
            return SuccessResponse::send('Invalid role provided');
        }

        $groupUser->role = $role;
        $groupUser->save();
        $groupUser->user->notify(new RoleUpdatedNotification($group->name, $role));

        return SuccessResponse::send('Role Updated Successfully', GroupUsersResource::make($groupUser));
    }
}
