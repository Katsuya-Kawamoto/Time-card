<?php

/**
 * メッセージフォーム用関数
 */

    class Message{

        /**
         * メッセージタイトル取得
         *
         * @param integer $number   ->  送信先の社員番号
         * @param integer $offset   ->  取得開始件数        初期値=0件
         * @param integer $count    ->  取得する件数        初期値=5件
         * @param integer $admin    ->  管理社からの送信    初期値=1(管理者)    
         * @return void
         */
        function title_in($number,$admin=1,$offset=0,$count=5){
            //内容表示
            $sql="  SELECT `title`,`in_n`,`created_at`,`id`,`admin` FROM `message` 
                    WHERE (`admin`=:admin OR `in_n`=:in_n) 
                    ORDER BY `id` 
                    DESC LIMIT ".$offset.", ".$count ;
            $stmt= connect()->prepare($sql);
            $stmt->bindParam(':admin',$admin);
            $stmt->bindParam(':in_n',$number);
            $stmt->execute();
            $result=$stmt->fetchAll();
            return $result;
        }

        /**
         * message件数取得
         *
         * @return $count ->お知らせ件数
         */
        function m_count($number,$admin=1){
            $sql="SELECT COUNT(`title`) FROM `message` WHERE (`admin`=:admin OR `in_n`=:in_n) ";
            $stmt= connect()->prepare($sql);
            $stmt->bindParam(':admin',$admin);
            $stmt->bindParam(':in_n',$number);
            $stmt->execute();
            $count=$stmt->fetch();//件数
            return $count;
        }

        /**
         * 返信件数取得
         *
         * @return $count ->お知らせ件数
         */
        function r_count($id){
            $sql="SELECT COUNT(`title`) FROM `response` WHERE `main_id`=:id";
            $stmt= connect()->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $count=$stmt->fetch();//件数
            return $count;
        }

        /**
         * メッセージ内容取得
         * @param  $id     ->   メッセージのid
         * @return $result ->   メッセージの詳細
         */
        function main_contents($id){
            //内容表示
            $sql="  SELECT * FROM `message` WHERE `id`=:id";
            $stmt= connect()->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $result=$stmt->fetch();
            return $result;
        }

        /**
         * メッセージ内容取得
         * @param  $id     ->   メッセージのid
         * @return $result ->   メッセージの詳細
         */
        function res_contents($id,$offset=0,$count=5){
            //内容表示
            $sql="  SELECT * FROM `response` WHERE `main_id`=:id
                    ORDER BY `id` 
                    DESC LIMIT ".$offset.", ".$count ;
            $stmt= connect()->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $result=$stmt->fetchAll();
            return $result;
        }

        /**
         * スタッフ一覧取得
         *
         * @return $list    ->  従業員の情報（番号と姓）
         */
        function staff_list(){
            //従業員情報取得
            $sql="SELECT `number`,`sei` FROM `staff` ORDER BY `number` ASC";
            $stmt=connect()->prepare($sql);
            $stmt->execute();
            $m_number=$stmt->fetchAll();
            return $m_number;
        }

        /**
         * スタッフ一覧を取得した後、整理の実施。
         *
         * @return array $list  ->   [社員番号]=>[社員名]
         */
        function list_input($m_number){
            $list=[];
            foreach($m_number as $m_list){
                $list[$m_list["number"]] = $m_list["sei"];
            }
            return $list;
        }

        /**
         * お知らせ情報書き込み
         *
         * @param   $title      件名
         * @param   $contents   内容
         * @param   $name       投稿者
         * @param   $created_at 投稿日時
         */
        function message_insert($title,$contents,$in_n,$out_n,$created_at,$admin=0){
            $sql="  INSERT INTO `message` (`title`,`contents`,`in_n`,`out_n`,`created_at`,`admin`) 
                    VALUES (:title,:contents,:in_n,:out_n,:created_at,:admin)";
            $stmt = connect() -> prepare($sql);
            $stmt->bindParam(':title',$title);
            $stmt->bindParam(':contents',$contents);
            $stmt->bindParam(':in_n',$in_n);
            $stmt->bindParam(':out_n',$out_n);
            $stmt->bindParam(':created_at',$created_at);
            $stmt->bindParam(':admin',$admin);
            $stmt->execute();
        }
        
        /**
         * お知らせ情報書き込み
         *
         * @param   $title      件名
         * @param   $contents   内容
         * @param   $name       投稿者
         * @param   $created_at 投稿日時
         */
        function response_insert($main_id,$title,$contents,$in_n,$out_n,$created_at,$admin=0){
            $sql="  INSERT INTO `response` (`title`,`contents`,`in_n`,`out_n`,`created_at`,`admin`,`main_id`) 
                    VALUES (:title,:contents,:in_n,:out_n,:created_at,:admin,:main_id)";
            $stmt = connect() -> prepare($sql);
            $stmt->bindParam(':title',$title);
            $stmt->bindParam(':contents',$contents);
            $stmt->bindParam(':in_n',$in_n);
            $stmt->bindParam(':out_n',$out_n);
            $stmt->bindParam(':created_at',$created_at);
            $stmt->bindParam(':main_id',$main_id);
            $stmt->bindParam(':admin',$admin);  
            $stmt->execute();
        }
    }
    
?>