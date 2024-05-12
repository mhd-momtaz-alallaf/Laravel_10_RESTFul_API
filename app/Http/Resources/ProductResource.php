<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => (string)$this->name,
            'details' => (string)$this->description,
            'quantity' => (int)$this->quantity,
            'status' => (string)$this->status,
            'picture' => url("img/{$this->image}"),
            'seller' => (int)$this->seller_id,
            'creationDate' => $this->created_at,
            'lastChange' => $this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }
}
