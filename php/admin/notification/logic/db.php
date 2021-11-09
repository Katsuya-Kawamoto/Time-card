<?php
/**
 * お知らせ用データベース
 */
    /**
     * お知らせ情報書き込み
     *
     * @param   $title      件名
     * @param   $contents   内容
     * @param   $name       投稿者
     * @param   $created_at 投稿日時
     */
    function notification_insert($input,$created_at){
        $sql="  INSERT INTO `notification` (`title`,`contents`,`name`,`created_at`) 
                VALUES (:title,:contents,:name,:created_at)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':title',$input["title"]);
        $stmt->bindParam(':contents',$input["contents"]);
        $stmt->bindParam(':name',$input["name"]);
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
    function notification_update($id,$input,$created_at){
        $sql="UPDATE `notification` SET `title`=:title,`contents`=:contents,`name`=:name,`created_at`=:created_at WHERE `id`=:id";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':title',$input["title"]);
        $stmt->bindParam(':contents',$input["contents"]);
        $stmt->bindParam(':name',$input["name"]);
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