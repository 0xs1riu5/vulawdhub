//var _icm = iCM == 0 ? 0 : iCM;

function getFirstDay(Y, M, D) {
    var y, m, d;
    y = parseInt(Y);
    m = parseInt(M);
    d = parseInt(D);
    var fy, fa, fm;
    if (y == 0) {
        return false;
    }
    if (y == 1582 && m == 10 && d > 4 && d < 15) {
        return false;
    }
    if (y < 0) {
        y++;
    }
    if (m > 2) {
        fy = y;
        fm = m + 1;
    } else {
        fy = y - 1;
        fm = m + 13;
    }
    var returnValue = Math.floor(Math.floor(365.25 * fy) + Math.floor(30.6001 * fm) + d + 1720995);
    var gregorianStart = 15 + 31 * (18994);
    if (d + 31 * (m + 12 * y) >= gregorianStart) {
        fa = Math.floor(0.01 * fy);
        returnValue += 2 - fa + Math.floor(0.25 * fa);
    }
    return returnValue + 1;
}


function checkInArray(aDay, iDay, aSort) {
    for (var i in aDay) {
        if (aDay[i] == iDay) {
            if (aSort == false) {
                return true;
            }
            for (var j in aSort) {
                if (aSort[j] == iDay) {
                    return true;
                }
            }
        }
    }
}


function getMonthURL(iM) {
    var aM = iM.split("-");
    if (aM.length == 2) {
		if (aM[0]==iCY1 && aM[1]==iCM1) iCS=false; else iCS=true;
		return "javascript:\" onclick=\"gCalendar("+aM[0]+", "+aM[1]+","+iCD+", "+iCS+")";
    } else {
        return "";
    }
}


function gCalendar(iCY, iCM, iCD, iCS) {
	if (iCS) iCD='';

	if (iCM==0){
		iCM = 12;
		iCY--;
	}

	var prev_date = new Date(iCY, iCM-1, 1);
	var next_date = new Date(iCY, iCM+1, 1);

	var sCP = prev_date.getFullYear() + '-' + prev_date.getMonth();
	var sCN = next_date.getFullYear() + '-' + next_date.getMonth();


    var HasLog = new Array();
    if (LDWD["y" + iCY]) {
        if (LDWD["y" + iCY]["m" + iCM]) {
            HasLog = LDWD["y" + iCY]["m" + iCM].split(",");

        }
    }
	var HasSort = false;
	var Day = new Array("日", "一", "二", "三", "四", "五", "六");
    var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    var daysInAWeek = 7;
    var OStr;
    var today = new Date();
    if (iCY == "" || isNaN(iCY)) {
        thisYear = today.getFullYear();
    } else {
        thisYear = parseInt(iCY);
    }
    if (iCM == "" || isNaN(iCM)) {
        thisMonth = today.getMonth() + 1;
    } else {
        thisMonth = parseInt(iCM);
        if (thisMonth < 1) {
            thisMonth = 1;
        }
        if (thisMonth > 12) {
            thisMonth = 12;
        }
    }
    if (iCD == "" || isNaN(iCD)) {
        //thisDay = today.getDate();
		thisDay = 0;
    } else {
        thisDay = parseInt(iCD);
        if (thisDay < 0) {
            thisDay = 1;
        }
        if (thisDay > 31) {
            thisDay = 31;
        }
    }
    if ((thisYear % 4) == 0) {
        daysInMonth[1] = 29;
        if ((thisYear % 100) == 0 && (thisYear % 400) != 0) {
            daysInMonth[1] = 28;
        }
    }
    OStr = "<table width=\"100%\" cellpadding=2 cellspacing=1 border=0><tr><td class=calendar_h align=center colspan=7>";
    if (getMonthURL(sCP) == "") {
		OStr += "&laquo;";
    } else {
		OStr += "<a href=\"" + getMonthURL(sCP) + "\">&laquo;</a>";
    }
    OStr += "&nbsp;" + thisYear + "\u5E74&nbsp;" + thisMonth + "\u6708&nbsp;";
    if (getMonthURL(sCN) == "") {
		OStr += "&raquo;";
    } else {
		OStr += "<a href=\"" + getMonthURL(sCN) + "\">&raquo;</a>";
    }
    OStr += "</td></tr><tr>";
    for (i = 0; i < daysInAWeek; i++) {
        OStr += "<td class=\"week\">" + Day[i] + "</td>";
    }
    OStr += "</tr><tr>";
    var firstDay = (getFirstDay(thisYear, thisMonth, 1)) % 7;
    for (i = 0; i < firstDay; i++) {
        OStr += "<td class=day>&nbsp;</td>";
    }
    for (d = 1; i < daysInAWeek; i++, d++) {
        if (d == 5 && thisMonth == 10 && thisYear == 1582) {
            d += 10;
        }
        OStr += "<td";
        if (d == thisDay) {
            OStr += " class=today";
        } else {
            OStr += " class=day";
        }
        if (checkInArray(HasLog, d, HasSort)) {
            OStr += "><a href=\"news.php?ym=" + thisYear ;
			if (thisMonth < 10) {
                OStr += "0";
            }
			OStr += thisMonth + "&d=";
            if (d < 10) {
                OStr += "0";
            }
            OStr += d +"\"><b>" + d + "</b></a></td>";
        } else {
            OStr += ">" + d + "</td>";
        }
    }
    var lastDayOfMonth = daysInMonth[thisMonth - 1];
    for (j = 1; j < 6 && d <= lastDayOfMonth; j++) {
        OStr += "</tr><tr>";
        for (i = 0; i < daysInAWeek && d <= lastDayOfMonth; i++, d++) {
            OStr += "<td";
            if (d == thisDay) {
                OStr += " class=today";
            } else {
                OStr += " class=day";
            }
            if (checkInArray(HasLog, d, HasSort)) {
				OStr += "><a href=\"news.php?ym=" + thisYear ;
                if (thisMonth < 10) {
                    OStr += "0";
                }
                OStr += thisMonth + "&d=";
	                if (d < 10) {
                    OStr += "0";
                }
                OStr += d +"\"><b>" + d + "</b></a></td>";
            } else {
                OStr += ">" + d + "</td>";
            }
        }
        for (; i < daysInAWeek; i++) {
            OStr += "<td class=day>&nbsp;</td>";
        }
    }
    OStr += "</tr></table>";
    document.getElementById('calendar').innerHTML=OStr;


}
document.write("<div id=calendar></div>");
gCalendar(iCY1, iCM1, iCD, iCS);