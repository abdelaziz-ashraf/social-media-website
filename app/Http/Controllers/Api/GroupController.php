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
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $groupService;
    public function __construct(GroupService $groupService) {
        $this->groupService = $groupService;
    }

    public function index() : JsonResponse {
        $groupsNotMember = $this->groupService->getGroups();
        return SuccessResponse::send('Groups', GroupResource::collection($groupsNotMember));
    }

    public function show(Group $group) : JsonResponse {
        return SuccessResponse::send('Group', GroupDetailsResource::make($group));
    }

    public function members(Group $group) : JsonResponse{
        $groupMembers = $group->users()->get();
        return SuccessResponse::send('Members', GroupUsersResource::collection($groupMembers));
    }

    public function store(StoreGroupRequest $request) : JsonResponse {
        $group = $this->groupService->createGroup($request->validated());
        return SuccessResponse::send('Group Created Successfully', GroupResource::make($group));
    }

    public function update(UpdateGroupRequest $request, Group $group) : JsonResponse {
        $group->update($request->validated());
        return SuccessResponse::send('Group Updated Successfully', GroupResource::make($group));
    }

    public function destroy(Group $group) : JsonResponse{
        $this->groupService->deleteGroup($group);
        return SuccessResponse::send('Group Deleted Successfully');
    }

    public function join(Group $group) : JsonResponse{
        $this->groupService->joinGroup($group);
        return SuccessResponse::send('Group Joined Successfully');
    }

    public function approveRequest(Group $group, User $user) : JsonResponse{
        $this->groupService->approveJoinRequest($group, $user);
        return SuccessResponse::send('Request Approved Successfully');
    }

    public function rejectRequest(Group $group, User $user) : JsonResponse{
        $this->groupService->rejectJoinRequest($group, $user);
        return SuccessResponse::send('Request Rejected Successfully');
    }

    public function updateAdminRole(Request $request, Group $group, $userId) : JsonResponse{
        $groupUser = $this->groupService->updateAdminRole($group, $userId, $request->input('role'));
        return SuccessResponse::send('Role Updated Successfully', GroupUsersResource::make($groupUser));
    }
}
