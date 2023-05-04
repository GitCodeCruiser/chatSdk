<?php

namespace App\Http\Resources;

use App\Utilities\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->contact_id,
            'name' => $this->users[0]->name,
            'number' => $this->users[0]->number,
            'profile_pic' => $this->users[0]->profile_pic,
            'base_url' => env('BASE_URL') . Constants::USER_PROFILE_IMAGES_PATH . '/',
        ];
    }
}
