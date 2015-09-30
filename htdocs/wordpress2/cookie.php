<?php
$getinterest = implode(',',$_POST['interest']);
$getinterest = htmlspecialchars($getinterest);
$getpref_id = htmlspecialchars($_POST['pref_id']);
$value = $getpref_id.",".$getinterest;
$timeout = time() + 30 * 86400;
setcookie("lifecruise",$value,$timeout,'/');

header("Location: ./");
?>