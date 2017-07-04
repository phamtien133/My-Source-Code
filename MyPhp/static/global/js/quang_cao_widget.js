function cai_dat_widget(so_bai, xem_nhieu)
{
	document.getElementById('widget_tut').style.padding = "5px";
	document.getElementById('widget_tut').innerHTML = '<img src="http://img.' + window.location.hostname + '/styles/web/global/images/ajax-loader.gif" title="Đang tải dữ liệu" />';
	var url = '';
	so_bai *= 1;
	if(so_bai>0 && so_bai<30) url = 'n='+so_bai;
	if(xem_nhieu == 1) url += '&xem_nhieu=1';
	if(url != '') url = '?' + url;
	
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = 'http://' + window.location.hostname + '/tools/widget.php' + url;
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
}
function xu_ly_widget(dong)
{
	function cut_str_tut(str, len) {
		if(str.length>len) {
			for (var i=len;i>0;i--) {
				if (str.substr(i,1)==' ') {
					break;
				}
			}
			str=str.substr(0,i) + '...';
		}
		return str;
	}
	var n = 0, cot ='';
	data = '';
	for(var i=1;i<dong.length;i++)
	{
		cot = dong[i];
		data += '<div style="width:33%;float:left;"><a target="_blank" href="' + cot['duong_dan'] + '" title="' + cot['tieu_de'].replace(/"/g, '&quot;') + '">' + cut_str_tut(cot['tieu_de'], 40) + '</a></div>';
	}
	document.getElementById('widget_tut').innerHTML = data;
}