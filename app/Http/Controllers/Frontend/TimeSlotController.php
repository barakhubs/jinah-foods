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
        $now = Carbon::now(); // or use Carbon::now('TimeZone') for specific time zones
        $dayOfWeek = $now->dayOfWeek;
        $timeSlot = TimeSlot::where('branch_id', $branchId)
            ->where('day', $dayOfWeek)
            ->first();

        if ($timeSlot) {
            $openingTime = Carbon::createFromFormat('H:i', $timeSlot->opening_time);
            $closingTime = Carbon::createFromFormat('H:i', $timeSlot->closing_time);

            // Adjust if closing time is past midnight
            if ($closingTime->lessThan($openingTime)) {
                $closingTime->addDay();
            }

            $isOpen = $now->between($openingTime, $closingTime, true);
            return response()->json(['isOpen' => $isOpen]);
        }

        return response()->json(['isOpen' => false], 404);
    }

}
