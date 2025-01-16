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
            'from_account' => $this['from_account_number'],
            'to_account' => $this['to_account_number'],
            'amount transfered' => number_format($this['amount'], 2),
            'message' => 'Funds transfered successfully',
            'New balance' => $this['from_account_balance']
        ];
    
    }

}

