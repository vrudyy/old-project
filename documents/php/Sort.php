<?php

require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");

class Sort{
    
    public function sortSchoolYears($schoolYears){
        $date = new Date();
        $dates = [];
        $sorted = [];
        foreach($schoolYears as $schoolYear){
            $date->periodToDate($schoolYear->schoolYearStart);
            array_push($dates, strtotime($date->toDB()));
        }
        $length = sizeof($schoolYears);
        for($i = 0; $i<$length; $i++){
            $max = array_search(max($dates), $dates);
            array_push($sorted, $schoolYears[$max]);
            unset($schoolYears[$max]);
            unset($dates[$max]);
        }
        return $sorted;
    }
    
    public function sortPeriods($periods){
        $date = new Date();
        $dates = [];
        $sorted = [];
        foreach($periods as $period){
            $date->periodToDate($period->periodStart);
            array_push($dates, strtotime($date->toDB()));
        }
        $length = sizeof($periods);
        for($i = 0; $i<$length; $i++){
            $min = array_search(min($dates), $dates);
            array_push($sorted, $periods[$min]);
            unset($periods[$min]);
            unset($dates[$min]);
        }
        return $sorted;
    }
    
    
}

