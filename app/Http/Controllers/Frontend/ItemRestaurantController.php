<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Resources\BranchResource;
use App\Http\Resources\ItemRestaurantMenuResource;
use App\Models\Item;
use App\Models\Branch;
use App\Services\BranchService;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ItemRestaurantResource;


class ItemRestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private BranchService $itemRestaurantService;

    public function __construct(BranchService $itemRestaurant)
    {
        $this->itemRestaurantService = $itemRestaurant;
    }

    public function index(PaginateRequest $request) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return BranchResource::collection($this->itemRestaurantService->listFront($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Branch $itemRestaurant) : \Illuminate\Http\Response | ItemRestaurantMenuResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemRestaurantMenuResource($this->itemRestaurantService->show($itemRestaurant));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function latestBranches() : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
        {
            try {
                return ItemRestaurantMenuResource::collection($this->itemRestaurantService->latestBranches());
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception->getMessage()], 422);
            }
        }

    public function mostPopularBranches() : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemRestaurantMenuResource::collection($this->itemRestaurantService->mostPopularBranches());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
