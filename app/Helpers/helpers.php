<?php

use Carbon\Carbon;
use Carbon\CarbonInterface;

if (!function_exists('humanReadableDate')) {
    function humanReadableDate($date): string
    {
        if (!$date instanceof CarbonInterface) {
            try {
                $date = Carbon::parse($date);
            } catch (\Exception $e) {
                return 'Invalid date';
            }
        }

        $now = Carbon::now();
        $diff = $date->diffInSeconds($now);

        if ($diff < 5) {
            return 'just now';
        }

        if ($diff < 60) {
            return $diff . ' second' . ($diff !== 1 ? 's' : '') . ' ago';
        }

        if ($diff < 3600) {
            $minutes = $date->diffInMinutes($now);
            return $minutes . ' minute' . ($minutes !== 1 ? 's' : '') . ' ago';
        }

        if ($diff < 86400) {
            $hours = $date->diffInHours($now);
            return $hours . ' hour' . ($hours !== 1 ? 's' : '') . ' ago';
        }

        if ($diff < 604800) {
            $days = $date->diffInDays($now);
            return $days . ' day' . ($days !== 1 ? 's' : '') . ' ago';
        }

        if ($diff < 2592000) { // Approximately 30 days
            $weeks = $date->diffInWeeks($now);
            return $weeks . ' week' . ($weeks !== 1 ? 's' : '') . ' ago';
        }

        if ($diff < 31536000) { // Approximately 365 days
            $months = $date->diffInMonths($now);
            return $months . ' month' . ($months !== 1 ? 's' : '') . ' ago';
        }

        $years = $date->diffInYears($now);
        return $years . ' year' . ($years !== 1 ? 's' : '') . ' ago';
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format a date to "Month Day, Year Hour:Minute am/pm" format.
     *
     * @param  mixed $date
     * @param  string $format
     * @return string
     */
    function formatDate($date, $format = 'F j, Y g:i A')
    {
        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::parse($date);
            } catch (\Exception $e) {
                return 'Invalid date';
            }
        }

        return $date->format($format);
    }
}

if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}
