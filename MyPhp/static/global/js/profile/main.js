$(function(){
	khoi_tao_tab();
	init_fade_slide();
	init_pop_up_thong_bao();
	init_edit_avatar();
	init_edit_cover();
	init_auto_size();
	init_upload_image_status();
})
function init_auto_size(){
	$('.autosize').autosize();
}
function khoi_tao_tab() {
	$('div.tabs').hide();
	$('div.tabs:first').show();
	$('#tabs ul li:first').addClass('active');
	$('#tabs ul li a').click(function () {
		$('#tabs ul li').removeClass('active');
		$(this).parent().addClass('active');

		var currentTab = $(this).attr('href');
		$('div.tabs').hide();
		$(currentTab).fadeIn('fast');
		return false;
	});
}
function init_fade_slide(){
	/* Khởi tạo slide fade_slide.installed*/
	$('.fade_slide.install').imagesLoaded(function(){
		$('.fade_slide.install').each(function(index, element) {
			var obj_fade_slide = $(this);
			obj_fade_slide.find('.opacity_0').removeClass('opacity_0');
			obj_fade_slide.find('.height_50').removeClass('height_50');
			
			obj_fade_slide.removeClass('install');
			
			var h_fade_slide = obj_fade_slide.find('.fade_slider .item_fade_slide:first-child img').height();
			var count_item = obj_fade_slide.find('.fade_slider .item_fade_slide').size();
			obj_fade_slide.find('.fade_slider .item_fade_slide').each(function(index, element) {
				$(this).css('z-index', count_item - index);
				obj_fade_slide.find('.nav_fade_slide').append('<div class="item_nave_fade_slide"></div>');
			});
			obj_fade_slide.find('.nav_fade_slide').attr('align','center');
			
			obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide:first-child').addClass('active');
			obj_fade_slide.find('.fade_slider .item_fade_slide:first-child').addClass('active');
			obj_fade_slide.height(h_fade_slide);
			obj_fade_slide.find('.item_fade_slide').height(h_fade_slide);
			obj_fade_slide.find('.nav_fade_slide').css('top',h_fade_slide - 30 + 'px');
			
			var thoi_gian = obj_fade_slide.data('thoi_gian');
			if(!obj_fade_slide.hasClass('re_init')){
				if(typeof(thoi_gian) == 'undefined')
					thoi_gian = 3000;
				var var_timer_fade_slide = setInterval(function () { timer_fade_slide(obj_fade_slide)}, thoi_gian);

					
				obj_fade_slide.hover(function(e) {
					$(this).data('trang_thai','tam_dung');
				}, function(){
					$(this).data('trang_thai','');
				});
			}
			navi_fade_slide(obj_fade_slide);
		});
		function timer_fade_slide(obj_fade_slide) {
			var trang_thai = obj_fade_slide.data('trang_thai');
			if(typeof(trang_thai) == 'undefined')
				trang_thai = '';
			if(trang_thai == 'tam_dung')
				return;
				
			obj_fade_slide.find('.item_fade_slide.active').animate({
				opacity: 0}, 750,
				function(){
					if( index_slide_item == obj_fade_slide.find('.item_fade_slide').size() - 1){
						obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide.active').removeClass('active');
						obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide:first-child').addClass('active');
					}else{
						obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide.active').removeClass('active');
						obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide').eq(index_slide_item + 1).addClass('active');
					}
				});
			var index_slide_item = obj_fade_slide.find('.item_fade_slide.active').index();
			if( index_slide_item == obj_fade_slide.find('.item_fade_slide').size() - 1){
				obj_fade_slide.find('.item_fade_slide:first-child').animate({
					opacity: 0}, 750, function(){
						obj_fade_slide.find('.item_fade_slide').css('opacity','1');
						obj_fade_slide.find('.item_fade_slide.active').removeClass('active');
						obj_fade_slide.find('.item_fade_slide:first-child').addClass('active');
					});
			}else{
				obj_fade_slide.find('.item_fade_slide.active').removeClass('active');
				obj_fade_slide.find('.item_fade_slide').eq(index_slide_item + 1).addClass('active');
			}
		}
		function navi_fade_slide(obj_fade_slide) {
			obj_fade_slide.find('.item_nave_fade_slide').click(function(e) {
				if($(this).hasClass('active'))
					return;
				obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide.active').removeClass('active')
				obj_fade_slide.find('.fade_slider .item_fade_slide.active').removeClass('active');
				
				var index = $(this).index();
				for(var i=0;i<index; i++){
					obj_fade_slide.find('.fade_slider .item_fade_slide').eq(i).css('opacity','0');
				}
				obj_fade_slide.find('.fade_slider .item_fade_slide').eq(index).css('opacity','1');
				obj_fade_slide.find('.nav_fade_slide .item_nave_fade_slide').eq(index).addClass('active');
				obj_fade_slide.find('.fade_slider .item_fade_slide').eq(index).addClass('active');
			});
		}
	})
}
function init_pop_up_thong_bao(){
	$('.popup_thong_bao').parent().hover(function(e) {
		var obj = $(this).find('.popup_thong_bao');
		if($(obj).css('display') != 'none') return ;
        obj.show();
		obj.animate({'opacity':'1','margin-top':'-10px'}, 300);
    }, function(){
		if($(obj).css('display') == 'none') return;
		var obj = $(this).find('.popup_thong_bao');
		obj.animate({'opacity':'0','margin-top':'20px'}, 300, function(){obj.hide()});
	});
}
function init_edit_avatar(){
	$('.avatar_profile .link_edit_image').click(function(e) {
		$('.fade_slide').data('trang_thai','tam_dung');
		
        $('.resize_avatar').data('upload-new',0);
		$('.avatar_profile').addClass('no_box');
		$('.avatar_profile .upload_avatar').fadeIn(300,function(){
			var src = $('.avatar_image').attr('src');
			$('.avatar_chinh_sua img').attr('src',src);
			$('.upload_avatar .close_upload_avatar').click(function(e) {
				$('.fade_slide').data('trang_thai','');
				
				$('.avatar_profile .upload_avatar').fadeOut(300);
				$('.avatar_profile').removeClass('no_box');
			});
			$('.resize_avatar').draggable();
			upload_avatar_html5();
			zoom_avatar();
			luu_avatar();
		});	
		return false;
    });
	
}
function upload_avatar_html5(){
	
	/* Hiện pop up upload file*/
	$('.upload_new_avatar').click(function(e){
		$('.resize_avatar').data('upload-new',-1);
		$('.input_upload_new_avatar').show().focus().click().hide();
		e.preventDefault();
	})
	/* Bắt sự kiện khi file trong input upload thay đổi*/
	
	$('.input_upload_new_avatar').change(function(e) {
		$('.progress_upload_avatar').show();
		
        var files = this.files;
		$('.fileupload_avatar').files = this.files;
		var file = files[0];
    	var imageType;
    	if(!file.type.match(imageType)){
      		console.log("Not an Image");
      		return;
    	}
		
    	var image = $('.avatar_chinh_sua .resize_avatar img');
    
    	image.file = file;
    	var reader = new FileReader();
    	reader.onload = (function(){
      		return function(e){
				/* Lấy src từ base 64*/
				$('.avatar_chinh_sua .resize_avatar img').attr('src',e.target.result);
      		};
    	}(image))
    	var ret = reader.readAsDataURL(file);	
    }).fileupload({
		url: 'http://img.f.fi.ai:8080/tools/api.php?type=upload&sid=7hv81bdhqlgkp0h00e2pa9sgk4',
		/*http://img.<?= $_SESSION['session-ten_mien']['ten']?>:8080/tools/api.php?type=upload&sid=<?= session_id()?>*/
		dataType: 'json',
		done: function (e, data) {
			data = JSON.parse(data['jqXHR']['responseText']);
			$('.resize_avatar').data('upload-new',1);
			$('.resize_avatar').data('url-new',data.url);
			$('.resize_avatar').css({'transform': 'scale(1)','-webkit-transform':'scale(1)','-ms-transform': 'scale(1)','top':'0px','left':'0px'});
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('.progress_upload_avatar .value_progress_upload_avatar').width(progress + '%');
		}
	});
}
function zoom_avatar(){
	$('.zoom_in_avatar').unbind('click').click(function(e) {
        var data_zoom = $('.resize_avatar').data('zoom');
		if(typeof(data_zoom) == 'undefined')
			data_zoom = 1;
		data_zoom += 0.1;
		$('.resize_avatar').css({'transform': 'scale('+data_zoom+')','-webkit-transform':'scale('+data_zoom+')','-ms-transform': 'scale('+data_zoom+')'});
		$('.resize_avatar').data('zoom',data_zoom)
    });
	$('.zoom_out_avatar').unbind('click').click(function(e) {
        var data_zoom = $('.resize_avatar').data('zoom');
		if(typeof(data_zoom) == 'undefined')
			data_zoom = 1;
		data_zoom -= 0.1;
		$('.resize_avatar').css({'transform': 'scale('+data_zoom+')','-webkit-transform':'scale('+data_zoom+')','-ms-transform': 'scale('+data_zoom+')'});
		$('.resize_avatar').data('zoom',data_zoom)
    });
}
function luu_avatar(){
	$('.luu_avatar').click(function(e) {
		var state = $('.resize_avatar').data('upload-new');
		if(state == -1){
			alert('Không có hình mới được tải lên!');
		}
		var top, left, scale, url = $('.avatar_link .avatar_image').attr('src');
		if(state == 1){
			$('.avatar_link .avatar_image').attr('src', $('.resize_avatar').data('url-new'));
			url = $('.resize_avatar').data('url-new');
		}
		$('.avatar_profile .upload_avatar').fadeOut(300);
		$('.avatar_profile').removeClass('no_box');
		
		var top, left, scale, url;
		top = $('.resize_avatar').css('top');
		left = $('.resize_avatar').css('left');
		scale = getScale($('.resize_avatar'));
		$('.avatar_profile .avatar_image').css({'transform': 'scale('+scale+')','-webkit-transform':'scale('+scale+')','-ms-transform': 'scale('+scale+')','top':top,'left':left});
		
		var data_submit_avatar = {};
		data_submit_avatar.top = top;
		data_submit_avatar.left = left;
		data_submit_avatar.scale = scale;
		
		data_submit_avatar.url = url;
		
		$('.fade_slide').data('trang_thai','');
		return false;
    });
}
function getScale(obj) {
    var matrix = obj.css("-webkit-transform") ||
    obj.css("-moz-transform")    ||
    obj.css("-ms-transform")     ||
    obj.css("-o-transform")      ||
    obj.css("transform");
    if(matrix !== 'none') {
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
		return a;
    };
    return 1;
}
function init_edit_cover(){
	var data_submit_cover = {};
	
	data_submit_cover.url = [];
	data_submit_cover.top = [];
	data_submit_cover.left = [];
	data_submit_cover.scale = [];
	
	load_image_from_cover(data_submit_cover);
	select_in_list_cover(data_submit_cover);
	$('.cover .link_edit_image').click(function(e) {
		
		$('.fade_slide').data('trang_thai','tam_dung');
		
		$('.cover .upload_cover').fadeIn(300,function(){
			$('.upload_cover .close_upload_cover').click(function(e) {
				$('.fade_slide').data('trang_thai','');
				$('.cover .upload_cover').fadeOut(300);
			});
			$('.resize_cover').draggable({
				drag: function( event, ui ) {
					data_submit_cover.top[$('.list_image_cover .item_image_cover.selected').index()] = $('.resize_cover').css('top');
					data_submit_cover.left[$('.list_image_cover .item_image_cover.selected').index()] = $('.resize_cover').css('left');
				}
			});
			delete_item_cover(data_submit_cover);
			upload_cover_html5(data_submit_cover);
			zoom_cover(data_submit_cover);
			luu_cover(data_submit_cover);
		});	
		return false;
    });
}
function load_image_from_cover(data_submit_cover){
	$('.cover .fade_slider .item_fade_slide').each(function(index, element) {
        var src = '';
		src = $(this).find('img').attr('src');
		data_submit_cover.top[index] = '0px';
		data_submit_cover.left[index] = '0px';
		data_submit_cover.scale[index] = 1;
		if(src != ''){
			$('.list_image_cover .item_image_cover').eq(index).find('img').attr('src',src);
			$('.list_image_cover .item_image_cover').eq(index).addClass('has_image');
			data_submit_cover.url[index] = $('.list_image_cover .item_image_cover').eq(index).find('img').attr('src',src);
		}else{
			$('.list_image_cover .item_image_cover').eq(index).removeClass('has_image');
			data_submit_cover.url[index] = '';
		}
    });
	$('.list_image_cover .item_image_cover:first-child').addClass('selected');
	$('.cover_chinh_sua .resize_cover img').attr('src',$('.list_image_cover .item_image_cover:first-child').find('img').attr('src'));
}
function select_in_list_cover(data_submit_cover){
	$('.list_image_cover .item_image_cover').click(function(e) {
        $('.list_image_cover .item_image_cover').removeClass('selected');
		$(this).addClass('selected');
		$('.cover_chinh_sua .resize_cover img').attr('src',$(this).find('img').attr('src'));
		var scale, top, left;
		var index = $(this).index();
		scale = data_submit_cover.scale[index];
		top = data_submit_cover.top[index];
		left = data_submit_cover.left[index];
		
		$('.cover_chinh_sua .resize_cover').css({'transform': 'scale('+scale+')','-webkit-transform':'scale('+scale+')','-ms-transform': 'scale('+scale+')','top':top,'left':left});
    });
}
function upload_cover_html5(data_submit_cover){
	var index_cover_select = -1;
	$('.item_image_cover').dblclick(function(e){
		index_cover_select = $(this).index();
		$('.input_upload_new_cover').show().focus().click().hide();
		e.preventDefault();
	})
	$('.input_upload_new_cover').change(function(e) {
		$('.list_image_cover .item_image_cover').eq(index_cover_select).find('.progress_upload_cover').show();
		
        var files = this.files;
		$('.fileupload_avatar').files = this.files;
		var file = files[0];
    	var imageType;
    	if(!file.type.match(imageType)){
      		console.log("Not an Image");
      		return;
    	}
		
    	var image = $('.list_image_cover .item_image_cover').eq(index_cover_select).find('img');
    
    	image.file = file;
    	var reader = new FileReader();
    	reader.onload = (function(){
      		return function(e){
				/* Lấy src từ base 64*/
				$('.list_image_cover .item_image_cover').eq(index_cover_select).find('img').attr('src',e.target.result);
				$('.cover_chinh_sua .resize_cover img').attr('src',e.target.result);
				$('.list_image_cover .item_image_cover').eq(index_cover_select).addClass('has_image');
      		};
    	}(image))
    	var ret = reader.readAsDataURL(file);
		
    }).fileupload({
		url: 'http://img.f.fi.ai:8080/tools/api.php?type=upload&sid=7hv81bdhqlgkp0h00e2pa9sgk4',
		/*http://img.<?= $_SESSION['session-ten_mien']['ten']?>:8080/tools/api.php?type=upload&sid=<?= session_id()?>*/
		dataType: 'json',
		done: function (e, data) {
			/*
			data = JSON.parse(data['jqXHR']['responseText']);
			$('.resize_avatar').data('upload-new',1);
			$('.resize_avatar').data('url-new',data.url);
			*/
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('.list_image_cover .item_image_cover').eq(index_cover_select).find('.value_progress_upload_cover').width(progress + '%');
		}
	});
}
function delete_item_cover(data_submit_cover){
	$('.delete_image_cover').click(function(e) {
        $(this).parent().removeClass('has_image');
		$(this).parent().find('img').removeAttr('src');
		return false;
		data_submit_cover.url[$(this).parent().index()] = '';
		data_submit_cover.top[$(this).parent().index()] = '0px';
		data_submit_cover.left[$(this).parent().index()] = '0px';
		data_submit_cover.scale[$(this).parent().index()] = 1;
    });
}
function zoom_cover(data_submit_cover){
	$('.zoom_in_cover').unbind('click').click(function(e) {
        var data_zoom = getScale($('.resize_cover')) * 1;
		data_zoom += (0.1);
		$('.resize_cover').css({'transform': 'scale('+data_zoom+')','-webkit-transform':'scale('+data_zoom+')','-ms-transform': 'scale('+data_zoom+')'});
		data_submit_cover.scale[$('.list_image_cover .item_image_cover.selected').index()] = data_zoom;
    });
	$('.zoom_out_cover').unbind('click').click(function(e) {
        var data_zoom = getScale($('.resize_cover')) * 1;
		data_zoom -= (0.1);
		$('.resize_cover').css({'transform': 'scale('+data_zoom+')','-webkit-transform':'scale('+data_zoom+')','-ms-transform': 'scale('+data_zoom+')'});
		data_submit_cover.scale[$('.list_image_cover .item_image_cover.selected').index()] = data_zoom;
    });
}
function luu_cover(data_submit_cover){
	$('.luu_cover').click(function(e) {
		var content_cover = '';
		$('.list_image_cover .item_image_cover.has_image').each(function(index, element) {
			var src = $(this).find('img').attr('src');
			content_cover += '<div class="item_fade_slide"> <a href=""> <img src="'+src+'"> </a> </div>';
		});
		$('.cover .nav_fade_slide').html('');
		$('.cover .fade_slide').addClass('install').addClass('re_init');
		$('.cover .fade_slider').html(content_cover);
		
		init_fade_slide();
		
		console.log(data_submit_cover);
		for(var i=0; i<5; i++){
			var scale = data_submit_cover.scale[i];
			var top = data_submit_cover.top[i];
			var left = data_submit_cover.left[i];
			$('.cover .fade_slider .item_fade_slide').eq(i).find('img').css({'transform': 'scale('+scale+')','-webkit-transform':'scale('+scale+')','-ms-transform': 'scale('+scale+')', 'top':top,'left':left});
		}
		$('.cover .upload_cover').fadeOut(300);
		$('.fade_slide').data('trang_thai','');
		return false;
	})
}
function init_upload_image_status(){
	$('.form_add_status .add_hinh_anh').click(function(e) {
    	$('.input_upload_image_status').show().focus().click().hide();
    });
	
	$('.input_upload_image_status').change(function(e) {
        var files = this.files;
		$('.input_upload_image_status').files = this.files;
		var file = files[0];
    	var imageType;
    	if(!file.type.match(imageType)){
      		console.log("Not an Image");
      		return;
    	}
		
    	var image = document.createElement("img");
		var list_image = '';    
    	image.file = file;
    	var reader = new FileReader();
    	reader.onload = (function(){
      		return function(e){
				/* Lấy src từ base 64*/
				$('.list_image_video_status').prepend('<div class="item_image_video_status"><img src="' + e.target.result + '"/><div class="status_up_image"></div></div>');
      		};
    	}(image));
    	var ret = reader.readAsDataURL(file);
    }).fileupload({
		url: 'http://img.f.fi.ai:8080/tools/api.php?type=upload&sid=63cj0a3lg5bquupqvnudrifnf0',
		/*http://img.<?= $_SESSION['session-ten_mien']['ten']?>:8080/tools/api.php?type=upload&sid=<?= session_id()?>*/
		dataType: 'json',
		done: function (e, data) {
			data = JSON.parse(data['jqXHR']['responseText']);
			$('.resize_avatar').data('url-new',data.url);
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('.list_image_video_status .item_image_video_status:first-child .status_up_image').width(100 - progress + '%');
		}
	});
}