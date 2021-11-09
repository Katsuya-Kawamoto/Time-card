<?php
/*
管理者用フォーム
①投稿or編集の判定機能
②フォームに情報を代入する為、DBに接続
③従業員の情報の取得
*/

    //編集・投稿判定
    $select_path = "/timecard/php/admin/attendance/mb_ad_time.php";  //判定path
    $flag        = edit_flag($select_path);
    $Form        = new Form_input();
    $result      = [];

    if($flag){                                                                  //編集の条件に合ったらデータ取得
        $key            = $_GET["id"];
        $_SESSION["id"] = $key;

        $result         = $Form -> staff_time_key($key);                       //勤務情報の取得
        $log            = $Form -> attendance_log_output($key);                //書き込みログの取得
        $title          = "編集";
    }else{
        //今日の日付取得
        require_once "../../logic/time_input.php";
        $time           = Time_input();                                         //現在の日付取得
        $title          = "投稿";
    }

    $input      = $Form -> form_return($output,$result);                        //フォームに情報代入
    $m_number   = $Form -> staff_list();                                        
    $list       = $Form -> staff_number($m_number);

    class Form_input{
        /**
         * フォームに情報代入
         * ①エラーで情報が返って来た場合は入力した情報を返す。
         * ②上記が無くて、リストからアクセスされた場合は選択されたKEYの情報を返す
         *
         * @param   array  $output    ->  バリデーションで返って来たフォームの内容
         * @param   array  $result    ->  編集時に取得された勤務内容
         * @return  array  $input     ->  フォームに返される値
         */
        function form_return($output,$result){
            $input=[];
            //form情報を返す
            if(isset($output["Day"])){                                      //①エラー情報があった場合
                foreach($output as $o_key => $o_value){
                    if(isset($o_value)){                                    //引数inputに代入
                        $input[$o_key]=$o_value;
                    }  
                }
            }else if(isset($result)){                                       //②リストから情報の取得を選択された場合
                foreach($result as $r_key => $r_value){
                    if(isset($r_value)){                                    //引数にinput代入
                        $input[$r_key]=$r_value;
                    }
                }
            }else{
                return false; 
            }
            return $input;
        }

        /**
         * 就業員情報の取得
         * @param  array    $m_number   ->  SQLで取得した従業員の情報
         *          [number]    ->  社員番号
         *          [sei]       ->  姓
         */
        function staff_list(){
            $sql="SELECT `number`,`sei` FROM `staff` ORDER BY `number` ASC";
            $stmt=connect()->prepare($sql);
            $stmt->execute();
            $m_number=$stmt->fetchAll();
            return $m_number;
        }

        /**
         * 従業員番号と情報の付与し直し。
         * @param  array    $m_number   ->  SQLで取得した従業員の情報
         *                  [number]    ->  社員番号
         *                  [sei]       ->  姓
         * @return array    $list       ->  整理した後の従業員の情報
         */
        function staff_number($m_number){
            $list=[];
            foreach($m_number as $m_list){
                $list[$m_list["number"]] = $m_list["sei"];
            }
            return $list;
        }

        /**
         * 勤務状況詳細取得
         * @param   $key    -> 勤務情報取得の為のキーNo.
         * @return  $result -> 情報出力
         */
        function staff_time_key($key){
            $sql="  SELECT * FROM `working_hours` 
            LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
            LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey 
            LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
            WHERE working_hours.keey=:keey";
            $stmt=connect()->prepare($sql);
            $stmt->bindParam(':keey',$key);
            $stmt->execute();
            $result=$stmt->fetch();
            return $result;
        }

        /**
         * フォームログ出力
         * @param   $key    -> 勤務情報取得の為のキーNo.
         * @return  $result -> 情報出力
         */
        function attendance_log_output($key){
            $sql="SELECT * FROM `attendance_log` WHERE keey=:keey";
            $stmt=connect()->prepare($sql);
            $stmt->bindParam(':keey',$key);
            $stmt->execute();
            $result=$stmt->fetchAll();
            return $result;
        }
        
    }