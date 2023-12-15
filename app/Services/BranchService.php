<?php

namespace App\Services;


use App\Http\Requests\BranchRequest;
use App\Http\Requests\PaginateRequest;
use App\Models\Branch;
use Exception;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class BranchService
{
    protected array $branchFilter = [
        'name',
        'email',
        'phone',
        'latitude',
        'longitude',
        'city',
        'state',
        'zip_code',
        'address',
        'status'
    ];

    protected $exceptFilter = [
        'excepts'
    ];
    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Branch::with('media')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->branchFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            )->where('status', 5);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(BranchRequest $request)
    {

        try {
            $branch =  Branch::create($request->validated());
            if ($request->image) {
                $branch->addMediaFromRequest('image')->toMediaCollection('item-restaurant');
            }
            return $branch;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(BranchRequest $request, Branch $branch)
    {
        try {
            $branch =  tap($branch)->update($request->validated());
            if ($request->image) {
                $branch->clearMediaCollection('item-restaurant');
                $branch->addMediaFromRequest('image')->toMediaCollection('item-restaurant');
            }
            return $branch;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Branch $branch): void
    {
        try {
            if (Settings::group('site')->get("site_default_branch") != $branch->id) {
                $branch->delete();
            } else {
                throw new Exception("Default branch not deletable", 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Branch $branch): Branch
    {
        try {
            return $branch;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function latestBranches()
    {
        try {
            return Branch::orderBy('created_at', 'desc')->where('status', 5)->limit(8)->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function mostPopularBranches()
    {
        try {
            $branches = Branch::with(['items' => function ($query) {
                $query->withCount('orders');
            }])
            ->where('status', 5)
            ->get()
            ->sortByDesc(function ($branch) {
                return $branch->items->sum('orders_count');
            })
            ->take(8);

            return $branches;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
