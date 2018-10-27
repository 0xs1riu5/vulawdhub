/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function m(i) { try { Dd(i).className = 'tab_on'; } catch(e) {} }
function s(i) { try { Dd(i).className = 'side_b'; } catch(e) {} }
function oh(o) {
	if(o.className == 'side_h') {
		Dh('side');o.className = 'side_s';
		set_local('m_side', 'Y');
	} else {
		Ds('side');o.className = 'side_h';
		set_local('m_side', 'N');
	}
}