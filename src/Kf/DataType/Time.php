<?php

namespace Kf\DataType;

class Time {

    public $hours = 0;
    public $minutes = 0;

    public function appendTime($time) {
        $time = explode(':', $time);
        $this->addHours($time[0]);
        $this->addMinutes($time[1]);
    }

    public function addHours($hours) {
        $this->hours+= (int) $hours;
    }

    public function addMinutes($minutes) {
        $this->minutes+= $minutes;
        if ($this->minutes >= 60) {
            $hours = (int) ($this->minutes / 60);
            $this->minutes-= $hours * 60;
            $this->hours+= $hours;
        }
        if ($this->minutes < 0) {
            $this->minutes+= 60;
            $this->addHours(-1);
        }
    }

    public static function time2decimal($time) {
        $hms = explode(":", $time);
        return ($hms[0] + ($hms[1] / 60) + (isset($hms[2]) ? ($hms[2] / 3600) : 0));
    }

    public static function decimal2time($decimal) {
        $hours = floor((int) $decimal / 60);
        $minutes = floor((int) $decimal % 60);
        $seconds = $decimal - (int) $decimal;
        $seconds = round($seconds * 60);
        return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
    }

    public static function db2time($dbValue) {
        try {
            if (strpos($dbValue, ' ')) {
                $dbValue = explode(' ', $dbValue)[1];
            }
            return substr($dbValue, 0, 5);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Verify if the day is a weekend
     * @param integer $day
     * @param integer $month
     * @param integer $year
     * @return bool
     * @throws \Kf\DataType\Exception
     */
    public static function isWeekend($day, $month, $year) {
        try {
            return \in_array(self::dayOfWeek($day, $month, $year), ['0', '6']);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public static function isSaturday($day, $month, $year) {
        try {
            return \in_array(self::dayOfWeek($day, $month, $year), ['6']);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public static function isSunday($day, $month, $year) {
        try {
            return \in_array(self::dayOfWeek($day, $month, $year), ['0']);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function numberOfDaysInMonth($month, $year) {
        try {
            return \date('t', mktime(0, 0, 0, $month, 1, $year));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dayOfWeek($day, $month, $year) {
        try {
            return \date('w', mktime(0, 0, 0, $month, $day, $year));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dayNameOfWeek($day, $month, $year) {
        try {
            switch (self::dayOfWeek($day, $month, $year)) {
                case 0: return 'Domingo';
                case 1: return 'Segunda-Feira';
                case 2: return 'Terça-Feira';
                case 3: return 'Quarta-Feira';
                case 4: return 'Quinta-Feira';
                case 5: return 'Sexta-Feira';
                case 6: return 'Sábado';
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __toString() {
        try {
            return "{$this->hours}:{$this->minutes} Hs";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
