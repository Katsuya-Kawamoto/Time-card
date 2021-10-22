<?php
    $number=$_SESSION["e-id"];
    $keey=$Year.$Month.$Day."_".$number;
        /**
        * 月ごとの勤務状況取得
        */
        $sql="  SELECT * FROM `working_hours` WHERE keey=:key";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':key',$keey);
        $stmt->execute();
        $Result=$stmt->fetch();

        function back_page($comment){
            $output["err-form"]=$comment;
            $output["header-sei"]=$_SESSION["header-sei"];
            $output["e-id"]=$_SESSION['e-id'];
            $_SESSION=$output;
            $address='attendance_form.php';             //戻るaddress
            if(isset($_GET["id"])){                     //GET値ある場合は付与
                $address.="?id=".$_POST["id"];
            }
            header('Location: '.$address);
            return;
        }
    if(isset($_POST["id"])){
        if(!$Result){
            $comment="登録日のデータが存在しません。";
            back_page($comment);
        }
    }else{
        if($Result){
            $comment="登録日のデータが既に存在します。";
            back_page($comment);
        }
    }

?>