<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimesheetService implements TimesheetServiceInterface
{
    public function getListTimesheet($user) {
        return $user->timesheets()->with('tasks')->get()->toArray();
    }

    public function storeTimesheet($user, $params) {
        DB::beginTransaction();
        try {
            $timesheet = $user->timesheets()->create(['difficulties' => $params['difficulties'], 'todo' => $params['todo'], 'date' => $params['date']]);

            $tasks = [];
            foreach ($params['tasks'] as $item) {
                if (isset($item['title']) || isset($item['content']) || isset($item['hours_used'])) {
                    $item['user_id'] = $user->id;
                    $tasks[] = $item;
                }
            }
            $tasks && $timesheet->tasks()->insert($tasks);
            DB::commit();
            return $timesheet;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function showByDate($user, $date) {
        return $user->timesheets()->whereDate('date', $date)->first();
    }

    public function update($user, $timesheet, $data) {
        if (!$user->can('update', $timesheet)) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $timesheet->difficulties = $data['difficulties'];
            $timesheet->todo = $data['todo'];
            $timesheet->save();

            $timesheet->tasks()->delete();

            $tasks = [];
            foreach ($data['tasks'] as $item) {
                if (isset($item['title']) || isset($item['content']) || isset($item['hours_used'])) {
                    $item['user_id'] = $user->id;
                    $tasks[] = $item;
                }
            }
            $tasks && $timesheet->tasks()->insert($tasks);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function checkIn($user, string $date, string $time) {
        return $user->timesheets()->updateOrCreate(['user_id' => $user->id, 'date' => $date], ['check_in' => $time]);
    }

    public function checkOut($user, string $date, string $time) {
        $timesheet = $user->whereDate('date', $date)->first();
        if (!$timesheet || !$timesheet->check_in) {
            return false;
        }

        $timesheet->check_out = $time;
        $timesheet->save();
        return $timesheet;
    }
}
