<?php

namespace App\Services;

use App\Libraries\AppLibrary;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\TimeSlot;
use Smartisan\Settings\Facades\Settings;
use Illuminate\Support\Facades\Auth; // Make sure to include Auth facade

class FrontendTimeSlotService
{
    public mixed $now = '';

    /**
     * @throws Exception
     */
    public function todayTimeSlot($userDefaultId)
    {
        try {

            $j = 0;
            $times = [];
            $today = Carbon::now()->dayOfWeek;
            $defaultScheduleTime = 300;

            // Adjust the query to include branch_id matching the user's default_id
            $todayTimes = TimeSlot::select('opening_time', 'closing_time')
                ->where(['day' => $today, 'branch_id' => $userDefaultId])
                ->orderBy('opening_time', 'asc')
                ->get()
                ->toArray();
            $orderSetup = Settings::group('order_setup')->get('order_setup_schedule_order_slot_duration');
            if (!empty($orderSetup)) {
                $defaultScheduleTime = (int) $orderSetup;
            }
            foreach ($todayTimes as $time) {
                $arrays = $this->todayTimeSlotCalculation(
                    $defaultScheduleTime,
                    $time['opening_time'],
                    $time['closing_time'],

                );
                if (count($arrays)) {
                    foreach ($arrays as $array) {
                        $times[$j] = (object) $array;
                        $j++;
                    }
                }
            }
            return collect($times);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function tomorrowTimeSlot(): \Vanilla\Support\Collection|\IlluminateAgnostic\Str\Support\Collection|\IlluminateAgnostic\Collection\Support\Collection|\IlluminateAgnostic\StrAgnostic\Str\Support\Collection|\IlluminateAgnostic\ArrAgnostic\Arr\Support\Collection|\Illuminate\Support\Collection|\IlluminateAgnostic\Arr\Support\Collection
    {
        try {
            $tomorrow = Carbon::tomorrow()->dayOfWeek;
            $defaultScheduleTime = 30;
            $tomorrowTimes = TimeSlot::select('opening_time', 'closing_time')->where(
                ['day' => $tomorrow]
            )->orderBy(
                    'id',
                    'asc'
                )->get()->toArray();
            $orderSetup = Settings::group('order_setup')->get('order_setup_schedule_order_slot_duration');

            if (!empty($orderSetup)) {
                $defaultScheduleTime = (int) $orderSetup;
            }

            $tomorrowSlots = [];
            foreach ($tomorrowTimes as $key => $time) {
                $arrays = $this->tomorrowTimeSlotCalculation(
                    $defaultScheduleTime,
                    $time['opening_time'],
                    $time['closing_time']
                );
                if (count($arrays)) {
                    foreach ($arrays as $array) {
                        $tomorrowSlots[] = (object) $array;
                    }
                }
            }
            return collect($tomorrowSlots);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    function todayTimeSlotCalculation($interval, $startTime, $endTime): array
    {
        $i = 0;
        $time = [];
        $strCurrentTime = strtotime(date('H:i'));
        $strStartTime = strtotime($startTime);
        $strEndTime = strtotime($endTime);

        // Check if now is less than endTime
        if ($strCurrentTime >= $strEndTime) {
            // If current time is greater than or equal to endTime, return empty array
            return [];
        }

        while ($strStartTime < $strEndTime) {
            $convertStartTime = date('H:i', $strStartTime);
            $convertEndTime = date('H:i', strtotime('+' . $interval . ' minutes', $strStartTime));

            // Only include slots where startTime is greater than current time
            if ($strStartTime > $strCurrentTime) {
                $time[$i]['label'] = AppLibrary::deliveryTime($convertStartTime . ' - ' . $convertEndTime);
                $time[$i]['from_time'] = $convertStartTime;
                $time[$i]['to_time'] = $convertEndTime;
                $time[$i]['time'] = $convertStartTime . ' - ' . $convertEndTime;
                $i++;
            }
            $strStartTime = strtotime('+' . $interval . ' minutes', $strStartTime);
        }
        return $time;
    }


    function tomorrowTimeSlotCalculation($interval, $startTime, $endTime): array
    {
        $i = 0;
        $time = [];
        $strStartTime = strtotime($startTime);
        $strEndTime = strtotime($endTime);

        while ($strStartTime < $strEndTime) {
            $convertStartTime = date('H:i', $strStartTime);
            $convertEndTime = date('H:i', strtotime('+' . $interval . ' minutes', $strStartTime));

            if ($strStartTime <= strtotime($endTime)) {
                $time[$i]['label'] = AppLibrary::deliveryTime($convertStartTime . ' - ' . $convertEndTime);
                $time[$i]['from_time'] = $convertStartTime;
                $time[$i]['to_time'] = $convertEndTime;
                $time[$i]['time'] = $convertStartTime . ' - ' . $convertEndTime;
                $i++;
            }
            $strStartTime = strtotime('+' . $interval . ' minutes', $strStartTime);
        }
        return $time;
    }
}
