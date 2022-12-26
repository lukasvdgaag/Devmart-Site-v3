<?php

namespace App\Utils;

use Illuminate\Support\Carbon;

class WebUtils
{

    public static function formatDate($date, $withTime = true, $shortened = false): string
    {
        if ($date instanceof Carbon) {
            $date = $date->unix();
        } else if (is_string($date)) {
            $date = Carbon::parse($date)->unix();
        }
        $daysBetween = WebUtils::getDaysBetween($date);

        if ($shortened) {
            $difference = time() - $date;

            if ($difference < 60) {
                $seconds = round($difference);
                return "$seconds sec";
            } else if ($difference < 60 * 60) {
                $minutes = round($difference / 60);
                return "$minutes min";
            } else if ($difference < 60 * 60 * 24) {
                $hours = round($difference / 60 / 60);
                return "$hours hour" . ($hours > 1 ? "s" : "");
            } else if ($difference < 60 * 60 * 24 * 30) {
                $days = round($difference / 60 / 60 / 24);
                return "$days day" . ($days > 1 ? "s" : "");
            } else if ($difference < 60 * 60 * 24 * 365) {
                $months = round($difference / 60 / 60 / 24 / 30);
                return "$months month" . ($months > 1 ? "s" : "");
            } else {
                $years = round($difference / 60 / 60 / 24 / 356);
                return "$years year" . ($years > 1 ? "s" : "");
            }
        }

        if ($withTime) {
            if ($daysBetween == 0) {
                return "Today at " . date("g:i A", $date);
            } else if ($daysBetween == 1) {
                return "Yesterday at " . date("g:i A", $date);
            } else if ($daysBetween < 7) {
                return date("l \a\\t g:i A", $date);
            } else if ($daysBetween < 365) {
                return date('F j \a\t g:i a', $date);
            } else {
                return date('F j, Y \a\t g:i a', $date);
            }
        } else {
            if ($daysBetween === 0) {
                return "Today";
            } else if ($daysBetween === 1) {
                return 'Yesterday';
            } else if ($daysBetween < 7) {
                return date('l', $date);
            } else if ($daysBetween < 365) {
                return date('F j', $date);
            } else {
                return date('F j, Y', $date);
            }
        }
    }

    public static function getDaysBetween($d): int
    {
        $now = time();
        $diff = $now - (int)$d;

        return round($diff / (60 * 60 * 24));
    }
}
