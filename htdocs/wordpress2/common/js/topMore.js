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
alert('�ǂݍ��݂Ɏ��s���܂����B');
}
});
}

if($("body").attr("id")){
//body�^�O��id������ΐ������擾���Ď��̔ԍ���
thisPage = $("body").attr("id").split("page")[1];
thisPage += thisPage;
loaderFunc(thisPage);
}else{
//body�^�O��id���Ȃ����id��ǉ�����2�y�[�W�ڂ��擾
$("body").attr("id", "page2");
thisPage = 2;
loaderFunc(thisPage);
}
return false;
});
});