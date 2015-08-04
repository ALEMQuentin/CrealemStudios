<?php
try {
/*Connect to the database*/
include("connexion.php")

/*Get the posted values from the form*/
$title=&$_POST['title'];
$body=&$_POST['content'];
$gtmsg=&$_POST['gtmsg'];
/*Get user id*/
$user_id=1;

$stmt = $dc->query("SELECT * FROM t_pages WHERE user='$user_id'");
$return_count = $stmt->rowCount();
if($return_count > 0){

    if(isset($title)){
    /*Update autosave*/
        $update_qry = $dc->prepare("UPDATE t_pages SET title='$title', content='$body' WHERE user='$user_id'");
        $update_qry -> execute();
    } else {
    /*Get saved data from database*/
        $get_autosave = $dc->prepare("SELECT * FROM t_pages WHERE user='$user_id'");
        $get_autosave->execute();
        while ($gt_v = $get_autosave->fetch(PDO::FETCH_ASSOC)) {
            $title=$gt_v['title'];
            $body=$gt_v['content'];
            echo json_encode(array('title' => $title, 'content' => $body));
        }
    }
} else {
/*Insert the variables into the database*/
    $insert_qry = $dc->prepare("INSERT INTO t_pages (user, title, content) VALUES (?, ?, ?)");
    $insert_qry->execute(array($user_id, $title, $body));
}
} catch(PDOException $e) {
    echo $e->getMessage();
    }
?>
