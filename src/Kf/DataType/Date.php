<?php

namespace Kf\DataType;

class Date {

    /**
     * @var string
     */
    public $date;

    /**
     * @var integer
     */
    public $day;

    /**
     * @var integer
     */
    public $month;

    /**
     * @var integer
     */
    public $year;

    public function __construct($date = null) {
        try {
            if ($date) {
                $this->date = $date;
                $date = explode('/', $date);
                $this->day = array_shift($date);
                $this->month = array_shift($date);
                $this->year = array_shift($date);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getDatesFrom($date) {
        try {
            
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get days in a range of dates
     * @param \Kf\DataType\Date $dateTo
     * @return array
     * @throws \Kf\DataType\Exception
     */
    public function getDatesTo(Date $dateTo) {
        try {
            $totalDaysStart = self::numberOfDaysInMonth($this->month, $this->year);
            $days = [];

            for ($i = $this->day, $l = $totalDaysStart; $i <= $l; $i++) {
                $days[(int) $this->month][$i] = null;
            }

            for ($i = 1, $l = $dateTo->day; $i <= $l; $i++) {
                $days[(int) $dateTo->month][$i] = null;
            }

            return $days;
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

    public static function db2date($value) {
        try {
            $value = explode(' ', $value)[0];
            $value = explode('-', $value);
            krsort($value);
            return implode('/', $value);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
