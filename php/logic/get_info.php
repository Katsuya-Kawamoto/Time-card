<?php
require "../connect.php";

//sqlに接続して、同じアドレスがあるかチェック
$sql="SELECT `id`,`title` FROM `notification` ORDER BY `id` DESC";
$stmt= connect()->prepare($sql);
$stmt->execute();
$result=$stmt->fetchAll();

var_dump($result);
?>
                    <ul>
<?php if(!isset($result)):?>
                        <li>現在、新しい情報はありません。</li>
<?php else: ?>  
<?php foreach($result as $key => $value) :?>
                        <li><a href="<?php echo $value["id"];?>"><?php echo $value["title"];?></a></li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>