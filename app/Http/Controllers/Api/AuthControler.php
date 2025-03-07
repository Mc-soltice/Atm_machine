<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PinRequest;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Service\AuthentificationService;
use App\Http\Service\UserService;
use App\Http\Resources\UserResource;
use App\Http\Resources\FindUserResource;
class AuthControler extends Controller
{
    protected $authentificationService;
    protected $userService;

    public function __construct(AuthentificationService $authentificationService, UserService $userService)
    {
        $this->authentificationService = $authentificationService;
        $this->userService = $userService;
    }
    
    public function register(RegisterAuthRequest $request)
    {
        return $this->authentificationService->register($request);
    }

    public function login(LoginAuthRequest $request)
    {
        return $this->authentificationService->loginUser($request);
    }

    public function logout(Request $data)
    {
        $this->authentificationService->logout($data);
        return response(['message' => 'Logged out successfully']);
    }

    public function getUsers()
    {
        return UserResource::collection($this->userService->getAllUsers());
    }

    public function findUser($id)
    {
        return new FindUserResource(User::findOrFail($id));
    }

    public function UpdateUser(Request $request, $id)
    {
        return new UserResource($this->userService->UpdateUser($request, $id));
    }

    public function UpdatePin(PinRequest $request)
    {   
        return $this->userService->UpdatePin($request);
    }
    
    public function DeleteUser($id)
    {
        return $this->userService->deleteUser($id);
    }

    public function unlockUser($userId)
    {
        return $this->userService->unlockUser($userId);
    }
}
