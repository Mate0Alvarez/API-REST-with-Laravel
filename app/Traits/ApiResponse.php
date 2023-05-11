<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    private function successResponse(array $data, int $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse(string | array $message, int $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection | SupportCollection $collection, int $code = Response::HTTP_OK)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection, $transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, int $code = Response::HTTP_OK)
    {
        $transformer = $instance->transformer;

        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse($instance, $code);
    }

    protected function showMessage(string $message, int $code = Response::HTTP_OK)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function sortData(SupportCollection $collection, $transformer) : SupportCollection
    {
        if (!request()->has('sort_by')) {
            return $collection;
        }

        $attribute = $transformer::originalAttribute(request()->sort_by);

        return $collection->sortBy->{$attribute};
    }

    protected function paginate(SupportCollection $collection) : LengthAwarePaginator
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = request()->has('per_page') ? (int) request()->per_page : 15;

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function filterData(SupportCollection $collection, string $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    protected function transformData(LengthAwarePaginator | Model $data, string $transformer) : Array
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    protected function cacheResponse(Array $data) : Array
    {
        $url = request()->url();

        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 15, function() use ($data) {
            return $data;
        });
    }
}
