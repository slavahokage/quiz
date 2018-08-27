<?php

declare(strict_types=1);

namespace App\Twig;


class TimeExtension extends \Twig_Extension
{
    public function getFunctions() : array
    {
        $function = function($time) {
            $result = $this->formatTime($time);
            return $result;
        };
        return array(
            new \Twig_SimpleFunction('format_time', $function),
        );
    }

    private function formatTime($time) :string
    {
        $hours = floor($time/3600000);
        $time = $time - ($hours*3600000);
        if($this->countOfNumbers($hours) < 2){
            $hours = "0" . $hours;
        }

        $minutes = floor($time/60000);
        $time = $time - ($minutes*60000);
        if($this->countOfNumbers($minutes) < 2){
            $minutes = "0" . $minutes;
        }

        $seconds = floor($time/1000);
        if($this->countOfNumbers($seconds) < 2){
            $seconds = "0" . $seconds;
        }

        return $hours . ":" . $minutes . ":" . $seconds;
    }

    private function countOfNumbers($number) : float
    {
        return ceil(log10($number));
    }
}