<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'from_account' => [
                'id' => $this->from_account_id,
                // 'balance' => $this->from_account_balance,
            ],
            'to_account' => [
                'id' => $this->to_account_id,
                'balance' => $this->to_account_balance,
            ],
            'amount' => $this->amount,
            'message' => 'Funds transfered successfully',
        ];
    }
}
