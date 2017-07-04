function chat_tat_thong_bao()
{
	var d = document.getElementById('div_chat_noi_dung');
	if(!d.length) return ;
	var olddiv = document.getElementById('chat_thong_bao');
	d.removeChild(olddiv);
}
$(function() {
	if(ajaxSupport == undefined) return ;
	clearTimeout(ajaxSupport['t_timeUpdate']);
	document.getElementById('div_chat_noi_dung').style.display = 'block';
	window.onfocus = function()
	{
		if(ajaxSupport['t_refresh'] > 500)
		{
			var objDiv = document.getElementById("div_chat_noi_dung");
			objDiv.innerHTML += '<div id="chat_thong_bao">Thông báo: Đã chạy lại</div>';
			// cuon noi dung chat
			objDiv.scrollTop = objDiv.scrollHeight;
		}
		ajaxSupport['so_lan'] = 0;
		ajaxSupport['t_refresh'] = 500;
		ajaxSupport['t_timeUpdate'] = setTimeout('chat_cap_nhat()', ajaxSupport['t_refresh']);
		setTimeout('chat_tat_thong_bao()', 2000);
	}
	window.onblur = function()
	{
		clearTimeout(ajaxSupport['t_timeUpdate']);
		var objDiv = document.getElementById("div_chat_noi_dung");
		objDiv.innerHTML += '<div id="chat_thong_bao">Thông báo: Đang tạm dừng</div>';
		// cuon noi dung chat
		objDiv.scrollTop = objDiv.scrollHeight;
		setTimeout('chat_tat_thong_bao()', 2000);
	}
});