<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => (int)$this->id,
            'name' => (string)$this->name,
            'email' => (string)$this->email,
            'isVerified' => (int)$this->verified,
            'isAdmin' => ($this->admin === 'true'), // to return 'true' or 'false' string insted of bool
            'creationDate' => $this->created_at,
            'lastChange' => $this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }
}
