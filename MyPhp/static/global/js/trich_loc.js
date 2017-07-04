function getQueryUrl(query) 
{
	var tmp = query.split('?');
	query = tmp[1];
	if(tmp[1] == undefined) return null;
	tmp = query.split('#');
	query = tmp[0];
	var arr = [];
	var pairs = [];
    //query = query.substr(1);
    var params = query.split("&"); 
    for (var i = 0; i < params.length; i++) 
    {
        pairs = params[i].split("="); 
		if(pairs[1] == '' || pairs[1] == undefined) continue;
		arr[pairs[0]] = pairs[1];
    }
	return arr;
}

function clk_query(ma_trich_loc, ma_ten)
{
	var url = window.location.href;
	var arr = getQueryUrl(url);
	if(arr == null) arr = [];
	
	if(ma_ten != '')
	{
		arr[ma_trich_loc] = ma_ten;
	}
	else if(ma_trich_loc == 'page')
	{
		arr[ma_trich_loc] = '';
	}

	ma_ten = '';
	for(i in arr)
	{
		if(arr[i] == '') continue;
		ma_ten += '&' + i + '=' + arr[i];
	}
	ma_ten = ma_ten.substr(1);
	if(ma_ten != '') ma_ten = '?' + ma_ten;
	window.location = window.location.pathname + ma_ten;
	return false;
}

function clk_trich_loc(ma_ten, ma_trich_loc)
{
	var pricerangeexists = $('#priceRangeForm').length;
	var url = window.location.href;
	var arr = getQueryUrl(url);
	if(arr == null) arr = [];
	
	if(ma_ten != '') chon_trich_loc(ma_trich_loc, ma_ten);
	
	url = '';
	var id = '', id_gt = '', tmp = '', i = 0;
	
	// check exists of price range
	if(pricerangeexists > 0)
	{
		// check change
		for(i in arr)
		{
			if(i == 'price')
			{
				i = '#';
				break;
			}
		}
		 var sliderFrom = parseFloat($('input[name="priceFrom"]').val());
		 var sliderTill = parseFloat($('input[name="priceTo"]').val());
		 if(!isNaN(sliderFrom) && !isNaN(sliderTill))
		 {
			// if don't exists filter price, check value exists
			if(minRange != undefined && maxRange != undefined && (minRange != sliderFrom || maxRange != sliderTill))
			{
				arr['price'] = sliderFrom + '-' + sliderTill;
			}
			else delete arr['price'];
		 }
		 else
		 {
			 delete arr['price'];
		}
	}
	// remove
	$('.trich_loc').each(function(index, element) {
		// get id
		id = $(this).attr('id');
		id = id.replace('trich_loc_', '');
		
		for(i in arr)
		{
			if(id != i) continue;
			if(pricerangeexists > 0 && id == 'price') continue;
			delete arr[id];
			break;
		}
	});
	$('.trich_loc').each(function(index, element) {
		// get id
		id = $(this).attr('id');
		id = id.replace('trich_loc_', '');
		// remove trich_loc
		tmp = '';
		$(this).find('.trich_loc_gt').each(function(index, element) {
			if($(this).hasClass('trich_loc_da_chon'))
			{
				id_gt = $(this).attr('id');
				id_gt = id_gt.replace('trich_loc_gt_', '');
				tmp += ',' + id_gt;
			}
		});
		if(tmp != '')
		{
			tmp = tmp.substr(1);
			arr[id] = tmp;
		}
	});
	
	ma_ten = '';
	for(i in arr)
	{
		if(arr[i] == '') continue;
		ma_ten += '&' + i + '=' + arr[i];
	}
	ma_ten = ma_ten.substr(1);
	if(ma_ten != '') ma_ten = '?' + ma_ten;
	window.location = window.location.pathname + ma_ten;
	return false;
}
function chon_trich_loc(stt, stt_gt)
{
	var id = '', id_gt = '';
	$('.trich_loc').each(function(index, element) {
		// get id
		id = $(this).attr('id');
		id = id.replace('trich_loc_', '');
		if(id == stt)
		{
			$(this).find('.trich_loc_gt').each(function(index, element) {
				id_gt = $(this).attr('id');
				id_gt = id_gt.replace('trich_loc_gt_', '');
				if(id_gt == stt_gt)
				{
					if($(this).hasClass('trich_loc_da_chon'))
					{
						$(this).removeClass('trich_loc_da_chon');
						$(this).find('a img').attr('src', 'http://img.' + window.location.hostname + '/styles/web/global/images/trich_loc.png');
					}
					else
					{
						$(this).addClass('trich_loc_da_chon');
						$(this).find('a img').attr('src', 'http://img.' + window.location.hostname + '/styles/web/global/images/trich_loc_da_chon.gif');
					}
				}
			});
		}
	});
}