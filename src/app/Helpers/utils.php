<?php

use Carbon\Carbon;

const SUCCESS_FALSE = 0;
const SUCCESS_OK    = 1;

function responseOkAPI($code, $data = null)
{
    if (!empty($data)) {
        if (is_array($data)) {
            $data = count($data) > 0 ? $data : null;
        } elseif (is_object($data)) {
            $data = $data->count() > 0 ? $data : null;
        } else {
            $data = $data ? $data : null;
        }
    }

    $output = [
        'success' => SUCCESS_OK,
        'data'    => $data,
        'errors'  => null
    ];
    return response()->json($output, $code);
}

function responseErrorAPI($code, $errorCode, $message, $data = null)
{
    $output = [
        'success' => SUCCESS_FALSE,
        'data'    => $data,
        'errors'  => [
            'error_code'    => $errorCode,
            'error_message' => $message
        ]
    ];
    return response()->json($output, $code);
}

function formatTimeToCarbon($timeNeedFormat)
{
    $time = Carbon::createFromFormat('H:i:s', $timeNeedFormat);

    if ($time->hour > 24) {
        $daysToAdd = floor($time->hour / 24);
        $time->addDays($daysToAdd);
    }

    return $time;
}
