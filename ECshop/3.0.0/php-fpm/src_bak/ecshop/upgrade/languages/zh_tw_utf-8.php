<?php

/**
 * ECSHOP 升級程序語言文件
 * ============================================================================
 * 網站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 這是一個免費開源的軟件；這意味著您可以在不用於商業目的的前提下對程序代碼
 * 進行修改、使用和再發佈。
 * ============================================================================
 * $Author: dolphin $
 * $Date: 2008-07-03 13:41:01 +0800 (四, 03  7 2008) $
 * $Id: zh_tw_utf-8.php 14696 2008-07-03 05:41:01Z dolphin $
*/

$_LANG['prev_step']         = '上一步：';
$_LANG['next_step']         = '下一步：';
$_LANG['readme_title']                =  'ECSHOP升級程序 第1步/共2步 說明頁';
$_LANG['checking_title']                =  'ECShop升級程序 第2步/共2步 環境檢測';
$_LANG['check_system_environment']          = '檢測系統環境';

$_LANG['copyright']                     = '&copy; 2005-2018 <a href="http://www.ecshop.com" target="_blank">上海商派雲起雲計算技術有限公司</a>。保留所有權利。';
$_LANG['is_last_version']             = '您的ECSHOP已是最新版本，無需升級。';

$_LANG['readme_page']                =  '說明頁';
$_LANG['notice'] = '本程序用於將 ECSHOP 升級到 <strong>%s</strong>。請勿必按照以下的升級方法進行升級，否則可能產生無法恢復的後果。如果你已經整合了論壇軟件，本次升級將取消整合，以後會員整合請到ucenrer中進行整合。';
$_LANG['usage1'] = '請確認已經安裝了 UCenter，否則，請到 <a href="http://www.discuz.com" target="_blank">Comsenz 產品中心</a> 下載並且安裝，然後再繼續。<br />';
$_LANG['usage2']  = '<a href="../admin">登錄後台</a>，<span style="color:red;font-weight:bold;font-size:18px;">備份</span>數據庫資料；';
$_LANG['usage3']  = '關閉現有的 ECSHOP %s 系統；';
$_LANG['usage4']  = '覆蓋性上傳 ECSHOP %s 的全部文件到服務器；';
$_LANG['usage5']  = '上傳本程序到 ECSHOP 所在的目錄中；';
$_LANG['usage6']  = '運行本程序，直到出現升級完成的提示。';
$_LANG['method']  = '升級方法';
$_LANG['charset']  = '編碼確認';
$_LANG['readme_charset']  = '<span style="color:red"><strong>重要：</strong>MySQL的編碼必須與ECShop的編碼一致。如果兩者有不一致性，以您選擇的為準！</span>';
$_LANG['mysql_charset']  = 'MySQL數據庫的編碼：';
$_LANG['ecshop_charset']  = 'ECShop的編碼：';
$_LANG['unknow_charset']  = '未知編碼';
$_LANG['sel_charset_0']  = 'MySQL與ECShop的編碼一致';
$_LANG['sel_charset_1']  = '請確認MySQL與ECShop的編碼：%s ';
$_LANG['sel_charset_2']  = '請確認MySQL的編碼：%s ';
$_LANG['sel_charset_3']  = '請確認ECShop的編碼：%s ';
$_LANG['opt_charset']  = '<select name="select_charset" id="select_charset">
    <option value="">請選擇編碼類型</option>
    <option value="utf-8">utf-8</option>
    <option value="gbk">gbk</option>
</select>';
$_LANG['faq']  = '常見問題';

$_LANG['basic_config']                           = '基本配置信息';
$_LANG['config_path']                           = '配置文件路徑';
$_LANG['db_host']                           = '數據庫主機';
$_LANG['db_name']                           = '數據庫名';
$_LANG['db_user']                           = '用戶名';
$_LANG['db_pass']                           = '密碼';
$_LANG['db_prefix']                         = '表前綴';
$_LANG['timezone']                         = '時區設置';
$_LANG['cookie_path']                      = 'COOKIE路徑';
$_LANG['admin_dir']                        = '管理中心根路徑';

$_LANG['dir_priv_checking']                 = '目錄權限檢測';
$_LANG['template_writable_checking']        = '模板可寫性檢查';
$_LANG['rename_priv_checking']              = '特定目錄修改權限檢查';
$_LANG['cannt_write']                     =  '不可寫';
$_LANG['can_write']                       = '可寫';
$_LANG['cannt_modify']                    = '不可修改';
$_LANG['not_exists']                      = '不存在';
$_LANG['recheck']                         = '重新檢查';
$_LANG['all_are_writable']                = '所有模板，全部可寫';

$_LANG['update_now']                    = '立即升級';
$_LANG['done'] = '恭喜，您已經成功升級到ECSHOP <strong>%s</strong>';
$_LANG['upgrade_error_title']                    = 'ECShop升級程序 升級失敗';
$_LANG['upgrade_done_title'] = 'ECShop升級程序 升級成功';
$_LANG['go_to_view_my_ecshop'] = '前往 ECSHOP 首頁';
$_LANG['go_to_view_control_panel'] = '前往 ECSHOP 後台管理中心 ';
$_LANG['dir_readonly']          = '%s 文件不可寫，請檢查您的服務器設置。';
$_LANG['monitor_title']          = '升級程序監視器';
$_LANG['wait_please']          = '正在升級中，請稍候…………';
$_LANG['js_error']          = '客戶端JavaScript腳本發生錯誤。';
$_LANG['create_ver_failed']          = '創建版本對像失敗';

/* 客戶端JS語言項 */
$_LANG['js_languages']['display_detail']                   = '顯示細節';
$_LANG['js_languages']['exception']                   = '發生異常';
$_LANG['js_languages']['hide_detail']                   = '隱藏細節';
$_LANG['js_languages']['suspension_points']                   = '…………';
$_LANG['js_languages']['initialize']                   = '初始化';
$_LANG['js_languages']['wait_please']               = '正在升級中，請稍候…………';
$_LANG['js_languages']['has_been_stopped']                    = '升級進程已中止';
$_LANG['js_languages']['is_last_version']                   = '您的ECSHOP已是最新版本，無需升級。';
$_LANG['js_languages']['from']                   = '正在從';
$_LANG['js_languages']['to']                   = '升級到';
$_LANG['js_languages']['update_files']                   = '升級文件';
$_LANG['js_languages']['update_structure']                   = '升級數據結構';
$_LANG['js_languages']['update_others']                   = '升級其它';
$_LANG['js_languages']['success']                   = '完成';
$_LANG['js_languages']['fail']                      = '失敗';
$_LANG['js_languages']['notice']                      = '出錯';
$_LANG['js_languages']['dump_database'] = '備份數據';
$_LANG['js_languages']['rollback'] = '恢復數據';

/* UCenter 安裝配置 */
$_LANG['configure_uc'] = '配置UCenter';
$_LANG['check_ucenter'] = '填寫完畢，進行下一步';
$_LANG['ucapi'] = 'UCenter 的 URL';
$_LANG['ucenter'] = '請填寫 UCenter 相關信息：';
$_LANG['ucfounderpw'] = 'UCenter 創始人密碼：';
$_LANG['uc_intro'] = 'UCenter 是 Comsenz 公司產品的核心服務程序，Discuz! Board 的安裝和運行依賴此程序。如果您已經安裝了 UCenter，請填寫以下信息。否則，請到 <a href="http://www.discuz.com" target="_blank">Comsenz 產品中心</a> 下載並且安裝，然後再繼續。<br /><br />';

$_LANG['users_importto_ucenter'] = '會員數據導入到 UCenter';
$_LANG['user_startid'] = '會員 ID 起始值：';
$_LANG['user_startid_intro'] = '<p>此起始會員ID為%s。如原 ID 為 888 的會員將變為 %s+888 的值。</p><br /><p><span style="color:#F00;font-size:1.2em;font-weight:bold;">提醒：導入會員數據前請暫停各個應用(如Discuz!, SupeSite等)</span></p><br />';
$_LANG['maxuid_err'] = '起始會員 ID 必須大於等於';
$_LANG['ucenter_import_members'] = '導入會員數據到UCenter';
$_LANG['ucenter_no_database'] = '<span style="color:#F00;font-size:1.5em;"><b>不能連接到UCenter的數據庫，升級不能完成，請聯繫管理員！</b></span>';
$_LANG['user_merge_method'] = '會員合併方式：';
$_LANG['user_merge_method_1'] = '將與UC用戶名和密碼相同的用戶強制為同一用戶';
$_LANG['user_merge_method_2'] = '將與UC用戶名和密碼相同的用戶不導入UC用戶';
$_LANG['ucenter_not_match'] = '<span style="color:#F00;font-size:1.2em;"><b>UCenter與ECShop字符編碼不匹配，升級不能完成，請聯繫管理員！</b></span>';

?>
