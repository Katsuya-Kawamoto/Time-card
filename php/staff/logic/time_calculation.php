<?php
/**
 * 月ごとの勤務状況算出
 */
$work_time=0;
$work_minutes=0;
$over_time=0;
$over_minutes=0;    
$midnight_time=0;
$midnight_minutes=0;
foreach($result as $key=>$value){
    $work_time+=$value["work_time"];
    $work_minutes+=$value["work_minutes"];
    $over_time+=$value["over_time"];
    $over_minutes+=$value["over_minutes"];    
    $midnight_time+=$value["midnight_time"];
    $midnight_minutes+=$value["midnight_minutes"];
}

function time_ip($time,$minutes){
    $TIME=$time;
    $MINUTES=$minutes;
    while(1){
        if((int)$MINUTES>=60){
            $MINUTES-=60;
            $TIME+=1;
        }else{
            $flag=false;
            break;
        }
    }
    return array("time"=>$TIME,"minutes"=>$MINUTES);
}
//関数で勤務時間取得
$work=time_ip($work_time,$work_minutes);
//時間と分に細分化
$work_time=($work["time"]);
$work_minutes=($work["minutes"]);

$over=time_ip($over_time,$over_minutes);
//時間と分に細分化
$over_time=($over["time"]);
$over_minutes=($over["minutes"]);


//休憩時間
$midnight=time_ip($midnight_time,$midnight_minutes);
//時間と分に細分化
$midnight_time=($midnight["time"]);
$midnight_minutes=($midnight["minutes"]);
?>