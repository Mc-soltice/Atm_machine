<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use app\Models\BankAccount;


class TransactionResource extends JsonResource
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
             'account_number' => $this->account_number,
             'type'=> $this->type,
             'amount'=> $this->amount,
            'updated_at' => $this->updated_at, // Ajouter le timestamp de mise à jour
         ];
     
     }
}
