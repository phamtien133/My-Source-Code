if(typeof(tinyOnLoad) !== 'function') function tinyOnLoad(ed){}
if(typeof(cap_nhat_tinymce) !== 'function') function cap_nhat_tinymce(ed){}
function setuptinymce(obj, vmin){
	var arr = {}, tmp = {};
	if(typeof(mobileversion) == 'undefined') mobileversion = 0;
	if(typeof(obj) == 'undefined') obj = "textarea.noi_dung";
	if(typeof(vmin) == 'undefined') vmin = 0;

	if(mobileversion == 1 || vmin == 1)
		tmp = {
			theme: "modern",
			plugins: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"table contextmenu directionality template paste textcolor responsivefilemanager"
			],
			toolbar_items_size: 'small',
			toolbar1: "newdocument | bold italic underline strikethrough | undo redo | link unlink media | forecolor backcolor | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect",
			toolbar2: "quote gmap | flash music video image | bullist numlist | removeformat fullscreen | outdent indent blockquote | table | hr removeformat | subscript superscript | charmap | responsivefilemanager",
			menubar: false,
			statusbar          : false,
			image_advtab: true,
			height : 340 
		}
	else
		tmp = {
			theme: "modern",
			plugins: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"table contextmenu directionality template paste textcolor responsivefilemanager"
			],
			toolbar_items_size: 'small',
			toolbar1: "newdocument | bold italic underline strikethrough | undo redo | link unlink media | forecolor backcolor | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect",
			toolbar2: "quote gmap | flash music video image | bullist numlist | removeformat fullscreen | outdent indent blockquote | table | hr removeformat | subscript superscript | charmap | responsivefilemanager",
			toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
			toolbar3: "table | hr removeformat | subscript superscript | charmap | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",
			toolbar4 : "quote bb_code php html pdf pdfshare gmap | flash music video image | bullist numlist | removeformat fullscreen | responsivefilemanager",
			menubar: false,
			statusbar          : false,
			image_advtab: true,
			height : 240 
		}
	
	arr = {
		file_browser_callback: function(field_name, url, type, win) {
        	if(type=='image') upHinhMoRong({id: field_name, obj: '' + field_name, type: 1});
        	else if(type=='media')  upHinhMoRong({id: field_name, obj: '' + field_name, type: 3});
			else upHinhMoRong({id: field_name, obj: '' + field_name, type: 2});
    	},
                        // General options
						/*
						theme_advanced_fonts :  
												'Aclonica=Aclonica, sans-serif;' + 
												'Michroma=Michroma, sans-serif;' + 
												'Paytone One=Paytone One, sans-serif;' +
												'Andale Mono=andale mono,times;'+
												'Arial=arial,helvetica,sans-serif;'+
												'Arial Black=arial black,avant garde;'+
												'Book Antiqua=book antiqua,palatino;'+
												'Comic Sans MS=comic sans ms,sans-serif;'+
												'Courier New=courier new,courier;'+
												'Georgia=georgia,palatino;'+
												'Helvetica=helvetica;'+
												'Impact=impact,chicago;'+
												'Symbol=symbol;'+
												'Tahoma=tahoma,arial,helvetica,sans-serif;'+
												'Terminal=terminal,monaco;'+
												'Times New Roman=times new roman,times;'+
												'Trebuchet MS=trebuchet ms,geneva;'+
												'Verdana=verdana,geneva;'+
												'Webdings=webdings;'+
												'Wingdings=wingdings,zapf dingbats;',
												*/
						document_base_url: 'img.' + global['domain'],
						baseURL: 'img.' + global['domain'],
						external_filemanager_path: document.location.protocol + '//img.' + global['domain'] + ':8080/',
						filemanager_crossdomain: true,
					   filemanager_title:"File Manager" ,
					   external_plugins: { "filemanager" : document.location.protocol + '//img.' + global['domain'] + "/styles/web/global/js/tiny_mce/plugins/responsivefilemanager/plugin.min.js"},
						language : "vi",
						tab_focus : ':prev,:next',
						relative_urls : false,
						convert_urls : false,
						// moi them
						forced_root_block: false, // deny add p at first
						force_br_newlines: true,
						force_p_newlines: false,
						convert_newlines_to_brs : false,
						remove_linebreaks : true,
						// end
						remove_script_host : false,
						height : "400",
						table_default_border: 1, // set default table border is 1
						selector : obj,
						fontsize_formats: "10px 11px 12px 13px 14px 15px 16px 18px 20px 24px 28px 32px 36px",
                        entity_encoding : "raw",
						content_css : "/styles/web/global/js/tinymce_tuy_chinh.css",
                        theme_advanced_statusbar_location : "bottom",
                        theme_advanced_resizing : "true",
                        theme_advanced_resize_horizontal : false,
                        theme_advanced_path : false,
						valid_children : "+body[style]",
						verify_html : false,
						paste_preprocess : function(pl, o) 
						{
							if(/<img.*\ssrc\s*=\s*"data:/i.test(o.content)) {
								o.content = "<div/>";
								alert("Pasting images is prohibited! Upload/attach the image instead.");
								return true;
							};
							if(o.content.indexOf("\n") > -1 || o.content.indexOf("<") > -1 || o.content.length > 100) return true;
							// bat su kien dan
							var content = o.content, content_tmp = '', i = 0, pos = -1, list = ['youtu', 'youtube','vimeo','google', 'nhaccuatui', 'clip','dailymotion', 'tamtay'];
							pos = content.indexOf('://');
							if(pos == -1)
							{
								if('www.' == content.substr(0, 4))
								{
									pos = 4;
								}
							}
							else
							{
								pos += 3; // 3 ky tu ://
							}
							if(pos > -1)
							{
								if(content.indexOf(' ') == -1)
								{
									content_tmp = content;
									content = content.substr(pos);
									// bỏ www
									if('www.' == content.substr(0, 4))
									{
										content = content.substr(4);
									}
									pos = 0;
									for(i = 0; i < list.length; i++)
									{
										if(list[i] == content.substr(0, list[i].length))
										{
											pos = 1;
											break;
										}
									}
									if(pos == 1)
									{
										o.content = '[video]' + content_tmp + '[/video]';
									}
									else
									{
										content = content_tmp;
										content_tmp = '';
									}
								}
								
								// khong tien hanh thay the
								if(content_tmp == '')
								{
									o.content = '<a href="' + content + '">' + content + '</a>';
								}
							}
						},
                        setup : function(ed) {
							ed.on("init", function() {
								 tinyOnLoad(ed);
							});
							ed.on("onBeforeSetContent", function() {
								ed.change();
							});
							ed.on("change", function() {
								ed.save();
								cap_nhat_tinymce(ed);
							});
							
                            ed.addButton('quote', {
                                title : 'QUOTE',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/quote.png',
                                onclick : function() {
                                    ed.selection.setContent('[QUOTE]'+ed.selection.getContent()+'[/QUOTE]');
                                }
                                
                            });
                            
                            ed.addButton('php', {
                                title : 'PHP',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/php.png',
                                onclick : function() {
                                    ed.selection.setContent('[PHP]'+ed.selection.getContent()+'[/PHP]');
                                }
                            });
                            
                            ed.addButton('html', {
                                title : 'HTML',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/html.png',
                                onclick : function() {
                                    ed.selection.setContent('[HTML]'+ed.selection.getContent()+'[/HTML]');
                                }
                            });
                            
                            ed.addButton('bb_code', {
                                title : 'CODE',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/code.png',
                                onclick : function() {
                                    ed.selection.setContent('[CODE]'+ed.selection.getContent()+'[/CODE]');
                                }
                            });
                            
                            ed.addButton('pdf', {
                                title : 'PDF',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/pdf.gif',
                                onclick : function() {
									ed.windowManager.open({
		                                title : 'PDF',
										file : document.location.protocol + '//admin.' + global['domain'] + ':' + window.location.port + '/tools/acp/bbcode.php?id=4',
										width : 350,
										height : 160,
										inline : 1,										
									   resizable : 0,
									   close_previous : 1
									});
		
									function receiveMessage(e) {
										if (e.origin !== 'http://admin.' + global['domain'] + ':8080') return;
										window.removeEventListener("message", receiveMessage, false);
										
										noi_dung = e.data;
										ed.insertContent(noi_dung);
										
										ed.windowManager.close();
									}
									window.addEventListener('message', receiveMessage);
                                }
                            });
							
                            ed.addButton('pdfshare', {
                                title : 'PDF Share',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/pdf.gif',
                                onclick : function() {
									ed.windowManager.open({
										title : 'PDF Share',
										file : document.location.protocol + '//admin.' + global['domain'] + ':' + window.location.port + '/tools/acp/bbcode.php?id=5',
										width : 350,
										height : 160,
										inline : 1,										
									   resizable : 0,
									   close_previous : 1
									});
		
									function receiveMessage(e) {
										if (e.origin !== 'http://admin.' + global['domain'] + ':8080') return;
										window.removeEventListener("message", receiveMessage, false);
										
										noi_dung = e.data;
										ed.insertContent(noi_dung);
										
										ed.windowManager.close();
									}
									window.addEventListener('message', receiveMessage);
                                }
                            });
							
                            ed.addButton('likecontent', {
                                title : 'Like FB to view content',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/likecontent.png',
                                onclick : function() {
                                    ed.selection.setContent('[likecontent]'+ed.selection.getContent()+'[/likecontent]');
                                }
                            });
                            
                            ed.addButton('gmap', {
                                title : 'Add Google Maps',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/gmap.png',
                                onclick : function() {
                                    ed.windowManager.open({
		                                title : 'Add Google Maps',
										file : document.location.protocol + '//admin.' + global['domain'] + ':' + window.location.port + '/tools/gmap.php?acp=1',
										width : 834,
										height : 514,
										inline : 1,										
									   resizable : 0,
									   close_previous : 1
									});
		
									function receiveMessage(e) {
										if (e.origin !== 'http://admin.' + global['domain'] + ':8080') return;
										window.removeEventListener("message", receiveMessage, false);
										
										noi_dung = e.data;
										ed.insertContent(noi_dung);
										
										ed.windowManager.close();
									}
									window.addEventListener('message', receiveMessage);
                                }
                            });
							
                            ed.addButton('flash', {
                                title : 'FLASH',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/flash.gif',
                                onclick : function() {
									ed.windowManager.open({
										title : 'FLASH',
										file : document.location.protocol + '//admin.' + global['domain'] + ':' + window.location.port + '/tools/acp/bbcode.php?id=3',
										width : 350,
										height : 160,
										inline : 1,										
									   resizable : 0,
									   close_previous : 1
									});
		
									function receiveMessage(e) {
										if (e.origin !== 'http://admin.' + global['domain'] + ':8080') return;
										window.removeEventListener("message", receiveMessage, false);
										
										noi_dung = e.data;
										ed.insertContent(noi_dung);
										
										ed.windowManager.close();
									}
									window.addEventListener('message', receiveMessage);
                                }
                            });
                            
                            ed.addButton('music', {
                                title : 'MUSIC',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/music.gif',
                                onclick : function() {
									ed.windowManager.open({
										title : 'MUSIC',
										file : document.location.protocol + '//admin.' + global['domain'] + ':' + window.location.port + '/tools/acp/bbcode.php?id=2',
										width : 350,
										height : 160,
										inline : 1,										
									   resizable : 0,
									   close_previous : 1
									});
									
									function receiveMessage(e) {
										if (e.origin !== 'http://admin.' + global['domain'] + ':8080') return;
										window.removeEventListener("message", receiveMessage, false);
										
										noi_dung = e.data;
										ed.insertContent(noi_dung);
										
										ed.windowManager.close();
									}
									window.addEventListener('message', receiveMessage);
                                }
                            });
                            
                            ed.addButton('video', {
                                title : 'VIDEO',
                                image : document.location.protocol + '//img.' + global['domain'] + '/styles/web/global/images/bbcode/video.gif',
                                onclick : function() {
									ed.windowManager.open({
										title : 'VIDEO',
										file : document.location.protocol + '//admin.' + global['domain'] + ':' + window.location.port + '/tools/acp/bbcode.php?id=1',
										width : 350,
										height : 200,
										inline : 1,										
									   resizable : 0,
									   close_previous : 1
									});
		
									function receiveMessage(e) {
										if (e.origin !== 'http://admin.' + global['domain'] + ':8080') return;
										window.removeEventListener("message", receiveMessage, false);
										
										noi_dung = e.data;
										ed.insertContent(noi_dung);
										
										ed.windowManager.close();
									}
									window.addEventListener('message', receiveMessage);
                                }
                            });
                        }
	};
	function merge_options(obj1,obj2){
		var obj3 = {};
		for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
		for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
		return obj3;
	}
	tinymce.init(
		merge_options(arr, tmp)
	);
}
(function(){setuptinymce()})();

if(typeof(tinymce.getInstanceById) != 'function')
{
	tinymce.getInstanceById = function(id)
	{
		id = tinymce.editors[id];
		if(typeof(id) == 'undefined') id = null;
		return id;
	}
}
function addRemoveEditor(add, id, hide, vmin)
{
	if(id.indexOf('#') == -1)
		id = '#' + id;
	id_thang = id.replace('#', '');
    id = 'textarea'+id;
	// vmin : version min toolbar
	if(hide == undefined) hide = 0;
	if(vmin == undefined) vmin = 0;
	if(add)
	{
		setuptinymce(id, vmin);
		if(hide) tinymce.get(id).show();
		else
		{
			try{
				tinymce.execCommand('mceAddEditor', false, id);
			}
			catch(e){};
		}
	}
	else
	{
		if(hide) tinymce.get(id).hide();
		else
		{
			try{
				tinymce.execCommand('mceRemoveEditor', false, id);
			}
			catch(e){};
		}
		
		$(id).change(function(e) {
			cap_nhat_tinymce({id: id})
		});
	}
}