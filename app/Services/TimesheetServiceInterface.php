<?php

namespace App\Services;

interface TimesheetServiceInterface
{
    public function getListTimesheet($user);

    public function storeTimesheet($user, $params);

    public function showByDate($user, $date);

    public function update($user, $timesheet, $data);

    public function checkIn($user, string $date, string $time);

    public function checkOut($user, string $date, string $time);
}
