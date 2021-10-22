<?php
$number=$_SESSION["e-id"];
/**
 * 月ごとの勤務状況取得
 */
$sql="  SELECT * FROM `working_hours` 
        LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
        LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey
        LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
        WHERE number=:number AND month=:month
        ORDER BY day ASC";
$stmt=connect()->prepare($sql);
$stmt->bindParam(':number',$number);
$stmt->bindParam(':month',$month);
$stmt->execute();
$result=$stmt->fetchAll();

?>
