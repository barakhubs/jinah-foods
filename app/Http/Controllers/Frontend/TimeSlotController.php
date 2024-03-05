<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use App\Services\FrontendTimeSlotService;
use App\Http\Resources\FrontendTimeSlotResource;
use Carbon\Carbon;
use Exception;

class TimeSlotController extends Controller
{
    public FrontendTimeSlotService $frontendTimeSlotService;

    public function __construct(FrontendTimeSlotService $frontendTimeSlotService)
    {
        $this->frontendTimeSlotService = $frontendTimeSlotService;
    }

    public function todayTimeSlot(
        $userDefaultId
    ): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return FrontendTimeSlotResource::collection($this->frontendTimeSlotService->todayTimeSlot($userDefaultId));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function tomorrowTimeSlot(
    ): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return FrontendTimeSlotResource::collection($this->frontendTimeSlotService->tomorrowTimeSlot());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    // custom code added
    public function todaySlot($branchId)
    {
        // Get today's day of the week (0 for Sunday, 1 for Monday, ... 6 for Saturday)
        $today = Carbon::now()->dayOfWeek;

        // Find the time slot for the given branchId and today
        $timeSlot = TimeSlot::where('branch_id', $branchId)
            ->where('day', $today)
            ->first(['opening_time', 'closing_time']);

        // Check if a time slot was found
        if ($timeSlot) {
            return response()->json([
                'success' => true,
                'data' => $timeSlot
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No time slot found for today.'
            ], 404);
        }
    }

}
