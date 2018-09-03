// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mishoo@infoiasi.ro>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("星期日","星期一","星期二","星期三","星期四","星期五","星期六","星期日");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
Calendar._SDN_len = 6; // short day name length
Calendar._SMN_len = 9; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("周日","周一","周二","周三","周四","周五","周六","周日");

// full month names
Calendar._MN = new Array
("一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月");

// short month names
Calendar._SMN = new Array
("一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "关于";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"For latest version visit: http://dynarch.com/mishoo/calendar.epl\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "上一年";
Calendar._TT["PREV_MONTH"] = "上一月";
Calendar._TT["GO_TODAY"] = "今天";
Calendar._TT["NEXT_MONTH"] = "下一月";
Calendar._TT["NEXT_YEAR"] = "下一年";
Calendar._TT["SEL_DATE"] = "请选择日期";
Calendar._TT["DRAG_TO_MOVE"] = "拖动";
Calendar._TT["PART_TODAY"] = "(今天)";
Calendar._TT["MON_FIRST"] = "周一在前";
Calendar._TT["SUN_FIRST"] = "周日在前";
Calendar._TT["CLOSE"] = "关闭";
Calendar._TT["TODAY"] = "今天";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
