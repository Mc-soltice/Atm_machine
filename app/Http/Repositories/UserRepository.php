<?php

namespace App\Http\Repositories;

use App\Models\Logging;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getAuthentificatedUser()
    {
        return Auth::user();
    }


    public function getAllUsers()
    {
        return User::orderBy('created_at', 'desc')->get();
    }
    
    public function findById($id){
        return User::findOrFail($id);
    }

    public function findByEmail($email){
        return User::where('email', $email)->first();
    }

    public function UpdateUser( $id, array $data)
    {
        $user = $this->findById($id);
        $user->update($data);
        Logging::store("Admin {$user->email} updated succesfully");
        return $user;
    }

    public function unlockUser($user)
    {
        if ($user && $user->loginAttempt) 
        {
            $user->loginAttempt->update(['locked_until' => null, 'attempts' => 0]);

            Logging::store("Admin {$user->email} unlocked successfully!");

            return response()->json(['message' => 'User unlocked successfully.']);
        }
            return response()->json(['message' => 'User not found.'], 404);
    }

    public function delete($id)
    {
        $user = $this->findById($id);
                // Vérifier si l'utilisateur existe
        if (!$user) {
            return false;
        }else{
            $user->delete();
            Logging::store("Admin {$user->email} deleted succesfully");
            return true;
        }
    }
}
