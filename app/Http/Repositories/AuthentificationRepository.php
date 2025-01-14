<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Models\Logging;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Resources\LoginResource;





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

    public function loginUser(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) 
        {
            $user = auth::user();
            $user->loginAttempt()->updateOrCreate(['user_id' => $user->id], ['attempts' => 0]);

            $name = $user->role->name; 
            $role = $user->role; 

            $permission = $role->permissions->pluck('name'); // Récupère les noms des permissions

            $token = $user->createToken("$name", $permission->toArray())->plainTextToken;
            Logging::store("Connexion succesfully! : {$data['email']}");

            return response()->json([
                            'message' => 'Login successful',
                            'token' => $token,
                        ]);
        } else 
            {
                $user = User::where('email', $data['email'])->first();

                if ($user) {
                    $loginAttempt = $user->loginAttempt()->firstOrCreate(['user_id' => $user->id]);
                    $loginAttempt->increment('attempts');
                }
                Logging::store("Connexion failled : {$data['email']}");
                return response()->json(['message' => 'Invalid credentials.'], 401);
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








// public function loginUser(array $data)
// {
//     // Récupérer l'utilisateur avec l'email fourni
//     $user = User::where('email', $data['email'])->first();
    
//     if ($user) 
//     {
//         // Vérifier si l'utilisateur est déjà bloqué
//         if ($user->is_locked) 
//         {
//             // Si l'utilisateur est bloqué et que le temps de blocage n'est pas encore écoulé

//             if (Carbon::parse($user->lock_time)->addMinutes(self::LOCK_TIME)->isFuture()) 
//             {
//                 // Si l'utilisateur est toujours bloqué, retour message d'erreur

//                 return back()->withErrors(['email' => 'Votre compte est temporairement bloqué. Réessayez plus tard.']);
//             } 
//             else 
//             {
//                 // Si le blocage est passé, réinitialiser le statut de blocage
                
//                 $user->update([
//                     'is_locked' => false,
//                     'failed_attempts' => 0,  // Réinitialiser les tentatives échouées
//                     'lock_time' => null,     // Réinitialiser l'heure du blocage
//                 ]);
//             }
//     }

//     if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) 
//     {
//         $user = auth::user();
        
//         // Si l'utilisateur est bloqué, empêcher la connexion

//         $role = $user->role; 
//         $name = $user->role->name; 

//         $permission = $role->permissions->pluck('name'); // Récupère les noms des permissions
        
//         $token = $user->createToken("$name", $permission->toArray())->plainTextToken;

//         Logging::store("Connexion succesfully! : {$data['email']}");

//         // Connexion réussie, réinitialiser les tentatives échouées
//         $user->update(['failed_attempts' => 0]);

//         return response([
//             'message' => 'Login successful',
//             'token' => $token,
//         ]);
//     }
//     else
//     {
//          // Connexion échouée, incrémenter les tentatives échouées
//          $user->increment('failed_attempts');

//          if ($user->failed_attempts >= self::MAX_ATTEMPTS) 
//         {
//             // Si l'utilisateur atteint le nombre maximal de tentatives échouées, on le bloque
//             $user->update([
//                 'is_locked' => true,
//                 'lock_time' => Carbon::now(),
//                 'failed_attempts' => 0,  // Réinitialiser les tentatives échouées après blocage
//             ]);

//             return back()->withErrors(['email' => 'Votre compte a été bloqué après plusieurs tentatives échouées.']);
//         }

//         Logging::store("Connexion failled : {$data['email']}");

//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }
    
// }