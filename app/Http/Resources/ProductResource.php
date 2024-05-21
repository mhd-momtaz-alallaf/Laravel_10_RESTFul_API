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
            'creationDate' => (string)$this->created_at,
            'lastChange' => (string)$this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }

    public static function originalAttribute($index) // to return the original name of the attribute not the resource attributes name.
    {
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'quantity' => 'quantity',
            'status' => 'status',
            'picture' => 'image',
            'seller' => 'seller_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
