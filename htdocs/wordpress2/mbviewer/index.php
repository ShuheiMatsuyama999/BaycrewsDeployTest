<?php
if(isset($_POST['string'])){
	$stats = $_POST['string'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="ja" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="content-script-type" content="javascript" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>  
<script type="text/javascript">google.load("jquery", "1.4");</script>  
<title>ベイクルーズグループ モバイルサイトビューワー</title>
<style type="text/css">
form{
display:block;
margin:0 0 4px 0;
}

input{
display:block;
width:250px;
}

p{
margin:24px 0 12px 0;
font-size:12px;
font-weight:bold;
}
</style>

<script type="text/javascript">
<!--
$(document).ready(function(){

$('#area a[href]').click(function(){
return false;
})

var stats = "<?php echo $stats; ?>";
stats = stats.replace('http:\/\/blog\.baycrews\.co\.jp\/','');
stats = stats.replace(/\?.*/,'');

$("a").click(function(){
var vlink = $(this).attr('href');
if(vlink == "http://m.baycrews.co.jp/catalog/" ){
alert('カタログはサポートしていません。');
}
else if(vlink.indexOf("shoplist") != -1 ){
vlink = "http:\/\/blog\.baycrews\.co\.jp\/" + stats +vlink;
alert('ショップリストはサポートしていません。');
}
else if(vlink == ("/" + stats) ){
alert('ブログはサポートしていません。');
}
else if(vlink=="http://m.baycrews.co.jp/" || vlink == "http://www.baycrews.co.jp/members/" || vlink == "http://www.facebook.com/baycrews" || vlink == "http://twtr.jp/user/BAYCREWS_JP" || vlink == "http://m.baycrews.co.jp/contact/" || vlink == "http://m.baycrews.co.jp/privacypolicy/"){
alert('外部サイトはサポートしていません。');
}
else if( vlink.indexOf("pagetop") != -1){
alert('ページトップに戻るアクションが実行されました。');
}
else if(vlink == "https://mailsys.baycrews.co.jp/mail/menu" ){
}
else if(vlink == "http://www.google.co.jp/chrome/" ){
}
else{

vlink = "http:\/\/blog\.baycrews\.co\.jp\/" + stats +vlink;

var data = {string : vlink};

$('<form/>', {action: 'index.php', method: 'POST'})
.append($('<input/>', {type: 'hidden', name: 'string', value: vlink}))
.appendTo(document.body)
.submit();
}

});

$(function(){
timerID = setInterval(function(){
urlHenkan();
clearInterval(timerID);
},100);
});

});
-->
</script>
</head>

<body style="background:#f9f9f9; padding:24px;">

<?php 
ini_set('user_agent','DoCoMo/2.0 F900i(c100;TB;W22H12)');
?>

<div style="width:240px; padding:0; background:#fff;">
<div style="width:240px; padding:0 background:#fff;" id="area">
&nbsp;
<?php
function attak($string) {
if(isset($string)) {
	$fileget = @file_get_contents($string);
	$buf = mb_convert_encoding($fileget, 'UTF-8', 'Shift_JIS');
}
echo $buf;
}
if(isset($_POST['string'])){
	attak($_POST['string']);
}
?>
</div>
</div>

<div style="position:absolute; top:0; left:312px; width:550px; height:100%; background:#f0f0f0; padding:30px; 24px; 24px; 24px;">

<input type="text" id="URL" style="width:550px; height:40px; font-size:16px; font-weight:bold;" value="<?php echo $stats; ?>" /><br />
<p style="font-size:12px; font-weight:normal; margin:-6px 0 12px 0; background:url(img/ico_mail.gif)no-repeat left center; padding:3px 0 0 28px;">現在表示中のページを<span style="color:#cc6666;">メルマガで送る</span>には、上記URLを貼付けます。</p>
<p style="font-size:12px; font-weight:normal; margin:-6px 0 12px 0; padding:3px 0 0 28px; text-align:right;">
<a href="https://mailsys.baycrews.co.jp/mail/menu" target="_blank">&gt;&gt;メルマガシステムへのログインはこちら</a></p>

<div style="width:250px; float:left;">
<p>ジャーナル レディースブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/journal-standard-l/?mode=1" />
<input type="submit" value="LADY's ジャーナルスタンダードレディース" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-luxe/?mode=1" />
<input type="submit" value="luxe ジャーナルスタンダードラックス" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-lessage/?mode=1" />
<input type="submit" value="L'essage ジャーナルスタンダードレサージュ" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-relume-l/?mode=1" />
<input type="submit" value="relume LADY's ジャーナルスタンダードレリューム レディース" />
</form>
<p>ジャーナル メンズブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/journal-standard-m/?mode=1" />
<input type="submit" value="MEN's ジャーナルスタンダードメンズ" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-homestead/?mode=1" />
<input type="submit" value="J.S. Homestead ホームステッド" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-relume-m/?mode=1" />
<input type="submit" value="relume MEN's ジャーナルスタンダードレリューム メンズ" />
</form>

<p>フレームワークス ブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/spickandspan/?mode=1" />
<input type="submit" value="Spick and Span スピックアンドスパン" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/framework/?mode=1" />
<input type="submit" value="FRAMeWORK フレームワーク" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/noble/?mode=1" />
<input type="submit" value="Spick and Span Noble ノーブル" />
</form>

<p>ルドーム ブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/edifice/?mode=1" />
<input type="submit" value="EDIFICE エディフィス" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/417/?mode=1" />
<input type="submit" value="417 by EDIFICE 417 バイ エディフィス" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/iena/?mode=1" />
<input type="submit" value="IENA イエナ" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/slobe/?mode=1" />
<input type="submit" value="IENA SLOBE イエナ スローブ" />
</form>
<p>ラクラス ブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/deuxieme-classe/?mode=1" />
<input type="submit" value="2e ドゥージィーエムクラス" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/lappartement/?mode=1" />
<input type="submit" value="L'Appartement アパルトモン" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/editforlulu/?mode=1" />
<input type="submit" value="EDIT. FOR LULU エディット フォー ルル" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/figaro-paris/?mode=1" />
<input type="submit" value="FIGARO フィガロパリ" />
</form>
</div>

<div style="width:250px; float:right;">


<p>ジョイントワークス ブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/la-totalite/?mode=1" />
<input type="submit" value="La TOTALITÉ ラ・トータリテ" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/limitless-luxury/?mode=1" />
<input type="submit" value="LIMITLESS LUXURY リミットレス" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/bcstock/?mode=1" />
<input type="submit" value="B.C STOCK ベーセーストック" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/ivory-court/?mode=1" />
<input type="submit" value="ivory court アイボリーコート" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/hirob/?mode=1" />
<input type="submit" value="HIROB ヒロブ" />
</form>

<p>ファニチャー ブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/acme/?mode=1" />
<input type="submit" value="ACME Furniture アクメファニチャー" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-furniture/?mode=1" />
<input type="submit" value="JS furniture  JSファニチャー" />
</form>

<p>フード ブランド</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-burgers/?mode=1" />
<input type="submit" value="J.S. BURGERS CAFE バーガーズ" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/js-pancake/?mode=1" />
<input type="submit" value="J.S. Pancake パンケーキ" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/jinnan-curry/?mode=1" />
<input type="submit" value="神南カリー" />
</form>

<p>その他</p>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/bodybyvital/?mode=1" />
<input type="submit" value="BBV" />
</form>
<form method="POST" action="index.php">
<input type="hidden" name="string" value="http://blog.baycrews.co.jp/reform/?mode=1" />
<input type="submit" value="リフォームワークス" />
</form>

<p>便利なリンク</p>
<div style="background:#fff; padding:8px;">
<a href="http://www.google.co.jp/chrome/" target="_blank" style="font-size:12px;"><img src="img/chrome_logo.gif" alt="" /></a>
</div>
</div>

<br style="clear:both;" />

</div>

</body>
</html>