<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait ApiResponser
{
	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		if ($collection->isEmpty()) {
			return $this->successResponse(['data' => $collection], $code);
		}
		
		$modelResource = $collection->first()->modelResource;

		$collection = $this->filterData($collection, $modelResource); // to filter the data by any requested route parameters.
		$collection = $this->sortData($collection, $modelResource); // to sort the data by the requested route parameter.
		$collection = $this->paginate($collection);
		$collection = $this->applyCollectionResource($collection,$modelResource);
		
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function showOne(Model $model, $code = 200)
	{
		$modelResource = $model->modelResource;

		$model = $this->applyResource($model,$modelResource);

		return $this->successResponse(['data' => $model], $code);
	}

	protected function showMessage($message, $code = 200)
	{
		return $this->successResponse(['data' => $message], $code);
	}

	protected function filterData(Collection $collection, $modelResource) // to filter the data by any attributes
	{
		foreach (request()->query() as $query => $value) {
			$attribute = $modelResource::originalAttribute($query); // the filtring will made using the resource attributes names.

			if (isset($attribute, $value)) { // if the $attribute, $value is passed from the request..
				$collection = $collection->where($attribute, $value); // so get the results whrere (name = Frankie) by example.
			}
		}

		return $collection;
	}

	/**
 * Paginate a given Laravel Collection.
 *
 * @param Collection $collection The collection to paginate.
 * @param int $perPage The number of items per page (default is 15).
 * @return LengthAwarePaginator The paginated collection.
 */
protected function paginate(Collection $collection, $perPage = 15)
{
    // Resolve the current page number from the request (or default to 1 if not present)
    $page = LengthAwarePaginator::resolveCurrentPage();

    // Slice the collection to get the items for the current page
    $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

    // Create a new LengthAwarePaginator instance with the sliced results
    return new LengthAwarePaginator(
        $results, // The sliced collection items for the current page
        $collection->count(), // The total number of items in the collection
        $perPage, // The number of items per page
        $page, // The current page number
        [
            'path' => request()->url(), // The base URL for pagination links
            'query' => request()->query(), // The query parameters for the pagination links
        ]
    );
}

	protected function sortData(Collection $collection, $modelResource) // to sort the data by the requested route parameter.
	{
		if (request()->has('sort_by')) {
			$attribute = $modelResource::originalAttribute(request()->sort_by);

			$collection = $collection->sortBy->{$attribute};
		}

		return $collection;
	}

	protected function applyCollectionResource($model, $modelResource)
	{
		return $modelResource::collection($model);
	}

	protected function applyResource($model, $modelResource)
	{
		return new $modelResource($model);
	}
}