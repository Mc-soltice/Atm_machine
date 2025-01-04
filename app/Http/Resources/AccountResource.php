<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'user_id' => $this->user_id,
             'balance' => $this->balance,
             'first_name' => $this->user->first_name,
         ];
     
     }
     
}
