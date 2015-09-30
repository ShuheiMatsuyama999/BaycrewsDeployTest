<?php
if(isset($_GET['sid'])) $sid = htmlspecialchars($_GET['sid']);
if(isset($_GET['num'])) $num = htmlspecialchars($_GET['num']);
if(isset($_GET['action'])) $action = htmlspecialchars($_GET['action']);

if($sid != ""){
	$discofile = $_SERVER["DOCUMENT_ROOT"]."/disco/".$sid.".dat";
	$discodata = array();
	if(file_exists($discofile)) $discodata = @file($discofile);
	$search_conditions = explode(",",$discodata[0]);
	array_shift($discodata);
	$search_count = count($discodata);
}

$getcookie = $_COOKIE['viewedpost'];
$getcookie_arr = explode("|",$getcookie);
$now_count = 0;

if($action == "next"){
	$start = $num;
	$end = $num + 20;
}else if($action == "back"){
	$start = ($num - 20);
	$end = $num;
}

foreach($discodata as $dd){
	if($now_count >= $start && $now_count < $end){
		$dd = rtrim($dd);
		$getcsv = explode(",",$dd);
		$brandpath = explode("/",$getcsv[6]);
		$visited = "";
		if(in_array($getcsv[8],$getcookie_arr)) $visited = "visited";
?>
		<li class="<?php if($visited){ echo $visited; } ?> loading_disco">
			<span class="img_loading"><img src="/common/images/loading_disco.gif" alt="loading" width="270" height="100" /></span>
			<a href="<?php echo $getcsv[0]."&sid=".$sid; ?>"><img src="<?php echo $getcsv[1]; ?>" alt="" width="84" height="100" /></a>
			<span class="date"><?php echo $getcsv[2]; ?></span>
			<span class="title"><a href="<?php echo $getcsv[0]."&sid=".$sid; ?>"><?php echo $getcsv[3]; ?></a></span>
			<span class="info"><span class="bcolor_<?php echo $brandpath[1]; ?>"><a href="<?php echo $getcsv[6]; ?>"><?php echo $getcsv[4]; ?></a></span><a href="<?php echo $getcsv[6]."?st=shop&getkey=".$getcsv[7]; ?>"><?php if($getcsv[5]) echo " / ".$getcsv[5]; ?></a></span>
		</li>
<?php
	}
	$now_count += 1;
	if($now_count >= $end) break;
}
?>