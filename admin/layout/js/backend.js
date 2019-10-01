$("[placeholder]").focus(function(){
	$(this).attr('data-text', $(this).attr('placeholder'));
	$(this).attr('placeholder','');
}).blur(function(){
	$(this).attr('placeholder',$(this).attr('data-text'));
})


$("input").each(function(){
	if($(this).attr('required')){
		$(this).after('<span class="asstrick">*</span>');
	}
})


// $('.show_password').on('hover',function(){
// 	$('.password').attr('type','text');

// },function(){
// 	$('.password').attr('type','password');
// })

$('.show_password').hover(function(){
	$('.password').attr('type','text');
},function(){
	$('.password').attr('type','password');
})

$('.delete').on('click',function(){
	return confirm("are you sure that you want to Delete ?");
})


//
// $('.categories p').on('click',function () {
// 	$(this).next('.hideAndShow').fadeOut();
// })

$(' p').click(function () {
	$(this).next('.hideAndShow').fadeToggle();
})
