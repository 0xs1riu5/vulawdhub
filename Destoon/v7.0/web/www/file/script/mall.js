/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function Mshow(i) {
	if(i == 'detail') {
		Dd('t_detail').className = 'mall_tab_2';
		Dd('t_comment').className = 'mall_tab_1';
		Dd('t_order').className = 'mall_tab_1';
		Ds('c_detail');
		Dh('c_comment');
		Dh('c_order');
	} else if(i == 'comment') {
		Dd('t_detail').className = 'mall_tab_1';
		Dd('t_comment').className = 'mall_tab_2';
		Dd('t_order').className = 'mall_tab_1';
		Dh('c_detail');
		Ds('c_comment');
		Dh('c_order');
		load_comment(0);
	} else if(i == 'order') {
		Dd('t_detail').className = 'mall_tab_1';
		Dd('t_comment').className = 'mall_tab_1';
		Dd('t_order').className = 'mall_tab_2';
		Dh('c_detail');
		Dh('c_comment');
		Ds('c_order');
		load_order(0);
	}
	$("html, body").animate({scrollTop:$('.mall_tab').offset().top-40}, 200);
}
function load_comment(p) {
	if(n_c == 0) {
		Dd('c_comment').innerHTML = '<div class="comment_no">'+m_l.no_comment+'</div>';
		return;
	}
	if(p == 0 && Dd('c_comment').innerHTML != c_c) return;
	$('#c_comment').load(AJPath+'?action=mall&job=comment&moduleid='+mallmid+'&sum='+n_c+'&itemid='+mallid+'&page='+p);
}
function load_order(p) {
	if(n_o == 0) {
		Dd('c_order').innerHTML = '<div class="order_no">'+m_l.no_order+'</div>';
		return;
	}
	if(p == 0 && Dd('c_order').innerHTML != c_o) return;
	$('#c_order').load(AJPath+'?action=mall&job=order&moduleid='+mallmid+'&sum='+n_o+'&itemid='+mallid+'&page='+p);

}
function addE(i) {
	$('#p'+i+' li').mouseover(function() {
		if(this.className == 'nv_1') this.className = 'nv_3';
	});
	$('#p'+i+' li').mouseout(function() {
		if(this.className == 'nv_3') this.className = 'nv_1';
	});
	$('#p'+i+' li').click(function() {
		$('#p'+i+' li')[s_s[i]].className = 'nv_1';
		this.className = 'nv_2';
		s_s[i] = $(this).index();
	});
}
function BuyNow() {
	Go(MEPath+'buy.php?mid='+mallmid+'&itemid='+mallid+'&s1='+s_s[1]+'&s2='+s_s[2]+'&s3='+s_s[3]+'&a='+Dd('amount').value);
}
function AddCart() {
	Go(MEPath+'cart.php?mid='+mallmid+'&itemid='+mallid+'&s1='+s_s[1]+'&s2='+s_s[2]+'&s3='+s_s[3]+'&a='+Dd('amount').value);
}
function Malter(t, min, max) {
	if(t == '+') {
		if(Dd('amount').value >= max) {
			Dd('amount').value = max;
		} else {
			Dd('amount').value = parseInt(Dd('amount').value) + 1;
		}
	} else if(t == '-') {
		if(Dd('amount').value <= min) {
			Dd('amount').value = min;
		} else {
			Dd('amount').value = parseInt(Dd('amount').value) - 1;
		}
	} else {
		if(Dd('amount').value > max) Dd('amount').value = max;
		if(Dd('amount').value < min) Dd('amount').value = min;
	}
}