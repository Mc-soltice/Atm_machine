<?php

namespace App\Http\Repositories;

use App\Models\Logging;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class AuthentificationRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(array $data)
    {
        Logging::store("User create succesfully : {$data['email']}");

        return $this->model->create($data);    
    }

    public function loginAdminUser(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) 
        {
            $user = auth::user();

            $role = $user->role; 
            $name = $user->role->name; 

            $permission = $role->permissions->pluck('name'); // Récupère les noms des permissions
            
            $token = $user->createToken("$name", $permission->toArray())->plainTextToken;

            Logging::store("Connexion succesfully! : {$data['email']}");

            return response([
                'message' => 'Login successful',
                'token' => $token,
            ]);
        }
        else
        {
            Logging::store("Connexion failled : {$data['email']}");
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
    }
    

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function updateUser($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return true;
    }
}
