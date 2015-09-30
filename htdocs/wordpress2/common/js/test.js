// JavaScript Document
$(function(){
	$(document).ready(function(){
		$(".block").hide();
		
		var bNum = 0;
		var int=0;
		
		$(window).bind("load",function(){
			var int=setInterval(function doFade(){
			var list = $(".block").length;
			if(bNum >= list){
				clearInterval(int);
			}
			$(".block").each(function(){
				$(this).eq(bNum).fadeIn(1000);
			})
			i++;
		},100);
		});
	
	})
})
