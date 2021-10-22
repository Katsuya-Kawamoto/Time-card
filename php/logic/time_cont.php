<?php
/**
 * 月ごとの勤務状況算出
 */
$work_time_count=0;
$over_time_count=0;
$midnight_time_count=0;

foreach($result as $key=>$value){
    if((int)$value["work_time"]>0||(int)$value["work_minutes"]>0){
        $work_time_count+=1;
    }
    if((int)$value["over_time"]>0||(int)$value["over_minutes"]>0){   
        $over_time_count+=1;
    }
    if((int)$value["midnight_time"]>0||(int)$value["midnight_minutes"]>0){     
        $midnight_time_count+=1;
    }
}
