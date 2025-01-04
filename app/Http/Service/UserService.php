<?php

namespace App\Http\Service;


use App\Http\Repositories\UserRepository;
use App\Http\Repositories\AuthentificationRepository;

class UserService
{

    protected $authentificationRepository;
    protected $userRepository;

    public function __construct(AuthentificationRepository $authentificationRepository, UserRepository $userRepository)
    {
        $this->authentificationRepository = $authentificationRepository;
        $this->userRepository = $userRepository;
    }

    public function getAuthentificatedUser()
    {
        return $this->userRepository->getAuthentificatedUser();
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function UpdateUser($request, $id,)
    {
        $data = [];
        $data["first_name"] = $request->first_name;
        $data["last_name"] = $request->last_name;

        $user = $this->userRepository->findByEmail($request->email);

        if (!$user) {
            $data["email"] = $request->email;
        }

        return $this->userRepository->UpdateUser($id, $data);
    }
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
        
    }

    public function findUser($email)
    {
        $user = $this->userRepository->findByEmail($email);
        return $user;
    }
}
