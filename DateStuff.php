<?php

/**
 * Library with date-related methods
 */
class DateStuff {
    /**
     * returns a number denoteing the day.
     * monday = 0, tuesday = 1,...
     * @param \DateTime $d
     * @return type
     */
    public static function weekDay(\DateTime $d) {
        return date("w", $d->getTimestamp());
    }

    /**
     * add 1 to given date
     * @param \DateTime $d
     * @return type
     */
    public static function incDay(\DateTime &$d) {
        return $d->add(new \DateInterval("P1D"));
    }

    /**
     * returns array holding days from now till given weekday.
     */
    public static function daysTillWeekday($tillDayNr = 0, $toFormat = null) {
        $d = new \DateTime();
        if ($toFormat == null) {
            $days[] = clone $d;
        } else {
            $days[] = $d->format($toFormat);
        }
        while (self::weekDay($d) != $tillDayNr) {
            self::incDay($d);
            if ($toFormat == null) {
                $days[] = clone $d;
            } else {
                $days[] = $d->format($toFormat);
            }
        }
        return $days;
    }

    /**
     * returns array of dates from today till end of month
     * @param type $toFormat
     * @return type
     */
    public static function daysTillEndOfMonth($toFormat = null) {
        $d = new \DateTime();
        $days[] = self::toFormat($d, $toFormat);
        $till = date("t/m/Y");
        while ($d->format("d/m/Y") != $till){
            self::incDay($d);
            $days[] = self::toFormat($d, $toFormat);
        }
        return $days;
    }

    private static function toFormat($d,$toFormat = null){
         if ($toFormat == null) {
            return clone $d;
        } else {
           return $d->format($toFormat);
        }
    }
    
    /**
     * get the date of today
     * @param type $toFormat
     * @return type
     */
    public static function today($toFormat = null){
        return self::toFormat(new \DateTime, $toFormat);
    }

    /**
     * get the date of tomorrow
     * @param type $toFormat
     * @return type
     */
    public static function tomorrow($toFormat = null){
        $d = self::today();
        return self::toFormat(self::incDay($d),$toFormat);
    }    
}

/*
 * Example code
 
echo DateStuff::tomorrow('d/m/Y');
$days = DateStuff::daysTillWeekday(0,'d/m/Y');
var_dump($days);
$d = DateStuff::daysTillEndOfMonth('d/m/Y');
var_dump($d);
var_dump(DateStuff::weekDay(DateStuff::today()));

*/