$(function(){
$('div.moreLink').click(function() {
var thisPage = "";
var loaderFunc = function(page){
$.ajax({
type: 'GET',
url: '/?pnum=' + page,
dataType: 'php',
success: function(data) {
$('#articleBoxContents').append(data);
},
error:function() {
alert('読み込みに失敗しました。');
}
});
}

if($("body").attr("id")){
//bodyタグにidがあれば数字を取得して次の番号へ
thisPage = $("body").attr("id").split("page")[1];
thisPage += thisPage;
loaderFunc(thisPage);
}else{
//bodyタグにidがなければidを追加して2ページ目を取得
$("body").attr("id", "page2");
thisPage = 2;
loaderFunc(thisPage);
}
return false;
});
});