<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'creationDate' => (string)$this->created_at,
            'lastChange' => (string)$this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $this),
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $this->id),
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', $this->id),
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $this->id),
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $this->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index) // to return the original name of the attribute not the resource attributes name.
    {
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
