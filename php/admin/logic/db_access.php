<?php
/*
    MySQL指定格納
*/

class db{

    /**
     * お知らせ件名取得
     * @param  無し
     * @return $result ->   お知らせの件名とid
     */
    function info_title($offset=0,$count=5){
    //内容表示
    $sql="SELECT * FROM `notification` ORDER BY `id` DESC LIMIT ".$offset.", ".$count;
    $stmt= connect()->prepare($sql);
    $stmt->execute();
    $result=$stmt->fetchAll();
    return $result;
    }

    /**
     * 選択したお知らせの詳細を表示
     *
     * @param  $id      ->お知らせid
     * @return $return3 ->お知らせ内容詳細
     */
    function info_input($id){
        $sql="SELECT * FROM `notification` WHERE `id`=:id";
        $stmt= connect()->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $result3=$stmt->fetch();
        return $result3;
    }

    /**
     * お知らせ件数取得
     *
     * @return $info_count ->お知らせ件数
     */
    function info_count(){
        $sql="SELECT COUNT(`id`) FROM `notification`";
        $stmt= connect()->prepare($sql);
        $stmt->execute();
        $info_count=$stmt->fetch();//件数
        return $info_count;
    }
    /**
     * 従業員情報登録
     *
     * @param   $number ->  社員番号
     * @param   $sei    ->  $姓
     * @param   $mei    ->  $名
     * @return 無し
     */
    function member_insert($number,$sei,$mei){
        $pass_hash=password_hash($number, PASSWORD_DEFAULT);//パスワードをハッシュ値に変換
        $sql="INSERT INTO `staff` (`number`,`sei`,`mei`,`pass`) VALUES (:number,:sei,:mei,:pass)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':number',$number);
        $stmt->bindParam(':sei',$sei);
        $stmt->bindParam(':mei',$mei);
        $stmt->bindParam(':pass',$pass_hash);
        $stmt->execute();
    }

    /**
     * 従業員情報出力
     * @param  無し
     * @return $result 従業員情報
     */
    function member_info_input(){
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT `number`,`sei`,`mei` FROM `staff` WHERE `number`=:number";
        $stmt= connect()->prepare($sql);
        $stmt->bindParam(':number',$_GET["id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        return $result;
    }

    /**
     * 従業員情報更新
     * @param $number -> 社員番号
     * @param $sei    -> 姓
     * @param $mei    -> 名
     * @return 無し
     */
    function menber_info_update($number,$sei,$mei){

        $sql="UPDATE `staff` SET `sei`=:sei,`mei`=:mei WHERE `number`=:number";
        //$sql="INSERT INTO `staff` (`number`,`sei`,`mei`,`pass`) VALUES (:number,:sei,:mei,:pass)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':number',$number);
        $stmt->bindParam(':sei',$sei);
        $stmt->bindParam(':mei',$mei);
        $stmt->execute();
    }

    /**メンバーリストの表示
     * $_GET["member]に値がある場合はWHEREの式に変換。
     * @param    無し   
     * @return   $stmt メンバーの一覧取得結果
     */
    function member_list(){
        $sql="  SELECT `number`,`sei`,`mei` 
                FROM `staff`";
        //sqlに接続して、同じアドレスがあるかチェック
        if(isset($_GET["number"])&&strlen($_GET["number"])>0){
            $number=$_GET["number"];
            $sql .= " WHERE `number`=:number";
        }
        $sql .= " ORDER BY `number` ASC";
        $stmt=connect()->prepare($sql);
        if(isset($_GET["number"])&&strlen($_GET["number"])>0)$stmt->bindParam(':number',$number);
        $stmt->execute();
        $result=$stmt->fetchAll();//結果があるか取得
        return $result;
        
    }

    /**社員番号の最大値取得
     * 
     * @param    無し   
     * @return   社員番号の最大値
     */
    function member_no_max(){
        $sql="  SELECT MAX(`number`) 
                FROM `staff`";
        $stmt=connect()->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetch();//結果があるか取得
        return (int)$result["MAX(`number`)"];
    }
    
    /**
     * 社員情報削除
     *
     * @param   無し
     * @return  無し
     */
    function member_delete($value){
        //削除実行
        $sql="DELETE FROM `staff` WHERE `number`=:number";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':number',$value);
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
    function notification_insert($title,$contents,$name,$created_at){
        $sql="  INSERT INTO `notification` (`title`,`contents`,`name`,`created_at`) 
                VALUES (:title,:contents,:name,:created_at)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':contents',$contents);
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':created_at',$created_at);
        $stmt->execute();
    }
    /**
     * お知らせ情報更新
     *
     * @param $id           ->投稿ID
     * @param $title        ->件名
     * @param $contents     ->内容
     * @param $name         ->投稿者
     * @param $created_at   ->投稿日時
     * 
     */
    function notification_update($id,$title,$contents,$name,$created_at){
        $sql="UPDATE `notification` SET `title`=:title,`contents`=:contents,`name`=:name,`created_at`=:created_at WHERE `id`=:id";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':contents',$contents);
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':created_at',$created_at);
        $stmt->execute();
    }

    /**
     * お知らせ情報削除
     *
     * @param   $id -> お知らせID
     * @return  無し
     */
    function notification_delete($id){
        $sql="DELETE FROM `notification` WHERE `id`=:id";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
    }
}
?>