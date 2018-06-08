<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class googlemapmodel {

    var $db;
    var $base;

    function googlemapmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function install() {
    	//创建数据表
    	$this->db->query("DROP TABLE IF EXISTS ".DB_TABLEPRE."googlemap;");
		$this->db->query("CREATE TABLE ".DB_TABLEPRE."googlemap (
`did` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0',
`title` VARCHAR( 200 ) NOT NULL DEFAULT  '',
`description` VARCHAR( 255 ) NOT NULL DEFAULT  '',
`lat` DOUBLE NOT NULL DEFAULT  '30',
`lng` DOUBLE NOT NULL DEFAULT  '116',
`zoom` TINYINT NOT NULL DEFAULT  '6',
`created` INT NOT NULL DEFAULT  '0',
PRIMARY KEY (  `did` ) 
) ENGINE = MYISAM ;");
    	//自动添加权限
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`type`) VALUES ('Google Map','googlemap-default','2')");
		$this->db->query("UPDATE `".DB_TABLEPRE."usergroup` SET regulars =  CONCAT(regulars,'|googlemap-default') where groupid!=4");
    	
        $plugin=array(
                'name'=>'Google Map',
                'identifier'=>'googlemap',
                'description'=>'让用户在地图上添加词条相关标注的插件（使用Google Maps API V3）',
                'datatables'=>'',
                'type'=>'0',
                'copyright'=>'hudong.com',
                'homepage'=>'http://kaiyuan.hudong.com',
                'version'=>'2.1',
                'suit'=>'5.0',
                'modules'=>''
        );
        $plugin['vars']=array();
        $plugin['hooks']=array(
                array(
                        'available'=>"1",
                        'title'=>'maphtml',
                        'description'=>'在hdwiki根目录下control下的doc.php中，查找 “$_ENV[\\\'block\\\']->view(\\\'viewdoc\\\');”，将“调用代码”栏的钩子代码完整复制到这行代码<strong>上面</strong>。',
                        'code'=>'$this->loadplugin("googlemap");
	    $mapviewstr=$_ENV["googlemap"]->get_map_view_str($doc["did"]);
	    $this->view->assign("mapviewstr",$mapviewstr);'
                )
        );
        return $plugin;
    }
    
    function edit_marker($did, $marker) {
		if ('gbk' == strtolower(WIKI_CHARSET)){
			$marker['title'] = string::hiconv($marker['title'], 'gbk', 'utf-8');
			$marker['description'] = string::hiconv($marker['description'], 'gbk', 'utf-8');
		}
    	$this->db->query("REPLACE INTO ".DB_TABLEPRE."googlemap (`did`,`title`,`description`,`lat`,`lng`,`zoom`,`created`) values 
    		({$did}, '{$marker['title']}', '{$marker['description']}', '{$marker['lat']}', '{$marker['lng']}', '{$marker['zoom']}', {$this->base->time});");    	
    }
    
    function get_map_view_str($did) {
    	$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."googlemap WHERE did = {$did}");
    	$map = $this->db->fetch_array($query);
   	
        if(!$map) {
    		$map = array(
    			'title'=>'',
    			'description'=>'',
    			'lat'=>'39.90874867307',
    			'lng'=>'116.39748191833',
    			'zoom'=>'6'
    		);
    		$linkAddCSS = 'inline';
    		$linkEditCSS= 'none';
    		$initJs = <<<JS
	var map;
	var initialLocation = new google.maps.LatLng({$map['lat']}, {$map['lng']}); //默認位置
	var infowindow;
	var marker;
	
	var myOptions = {
		zoom: 6,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControlOptions: {style:[google.maps.MapTypeControlStyle.DROPDOWN_MENU]}
	};
	
	map = new google.maps.Map(document.getElementById("mapObj"), myOptions);
	
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			map.setCenter(initialLocation);
		}, function() {
			map.setCenter(initialLocation);
		} );
	} else {
		map.setCenter(initialLocation);
	}

JS;
    		
    	} else {
    		$map['title'] = addslashes($map['title']); 
    		$map['description'] = addslashes($map['description']); 
    		$linkAddCSS = 'none';
    		$linkEditCSS= 'inline';
    		$initJs = <<<JS
	var map;
	var initialLocation = new google.maps.LatLng({$map['lat']}, {$map['lng']});
	var infowindow;
	var marker;
	
	var myOptions = {
		zoom: {$map['zoom']},
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControlOptions: {style:[google.maps.MapTypeControlStyle.DROPDOWN_MENU]}
	};
	
	map = new google.maps.Map(document.getElementById("mapObj"), myOptions);
	
	marker = new google.maps.Marker({
		draggable: false,
		position: initialLocation, 
		map: map,
		title:"{$map['title']}"
	});

	infowindow = new google.maps.InfoWindow({
		content: '<strong>{$map['title']}</strong><br />{$map['description']}'
	});
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, marker);
    });
	
	map.setCenter(initialLocation);
JS;
    	}
    	
    	$map_html = <<<HTML
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> 
<style type="text/css">
#mapForm { padding: 0 5px 5px; }
#markerTitle, #markerText { width: 136px; }
#mapObj div { line-height: normal; }
</style>
<div class="columns">
	<h2 class="col-h2">Map</h2>
	<a href="javascript:void(0)" onclick="addMarker()" style="display:{$linkAddCSS}" id="linkAdd" class="more">添加</a>
	<a href="javascript:void(0)" onclick="editMap()" style="display:{$linkEditCSS}" id="linkEdit" class="more">修改</a>
	<div id="mapArea">
		<div id="mapForm" style="display: none;">
		<form action="" onsubmit="return submitMap();">
		<table cellspacing="0" cellpadding="0" border="0"><tr>
			<td><label for="markerTitle">标注点标题:</label></td>
			<td><input name="title" id="markerTitle" type="text" value="{$map['title']}" /></td>
		</tr><tr>
			<td valign="top"><label for="markerText">标注点描述:<br />(60字以内)</label></td> 
			<td><textarea name="description" id="markerText" rows="4">{$map['description']}</textarea></td>
		</tr><tr>
			<td colspan="2" align="center"><input type="hidden" name="did" id="markerDid" value="{$did}" />
			<button type="submit">保存</button> <button type="button" onclick="cancelMap()">取消</button>
			</td>
		</tr></table>
		</form>
		</div>
		<div id="mapObj" style="height: 300px; "></div>
	</div>
		
	<script type="text/javascript"> 
	{$initJs}
	
	function editMap() {
		$('#mapForm').slideDown();
		marker.setDraggable(true);
	}
	
	function addMarker() {
		$('#mapForm').slideDown();
		
		var info = '请把我拖到正确的位置^_^';
		
		marker = new google.maps.Marker({
			draggable: true,
			position: map.getCenter(), 
			map: map,
			title:info
		});
		
	
		if(infowindow) {
			infowindow.setContent(info);
		} else {
			infowindow = new google.maps.InfoWindow({
				content: info
			});
		}
		
		infowindow.open(map, marker);
		$('#linkAdd').hide();
		$('#linkEdit').show();
	}
	
	function submitMap() {
		$.post("index.php?plugin-googlemap-googlemap-default",{
				title:		$('#markerTitle').val(),
				description:$('#markerText').val(),
				did:		$('#markerDid').val(),
				lat:  		marker.getPosition().lat(),
				lng:  		marker.getPosition().lng(),
				zoom:		map.getZoom()
			},
			function(data){
				if(data.did) {
					var newLocation = new google.maps.LatLng(data.lat, data.lng);
					var info = '<strong>'+data.title+'</strong><br />'+data.description;
	
					if(infowindow) {
						infowindow.setContent(info);
					} else {
						infowindow = new google.maps.InfoWindow({
							content: info
						});
					}
					
					infowindow.open(map, marker);
					map.setCenter(newLocation);
					marker.setTitle(data.title);
					marker.setDraggable(false);
					$('#mapForm').slideUp();
				}
			}, 
			"json"
		);
		return false;
	}
	
	function cancelMap() {
		$('#mapForm').slideUp();
		marker.setDraggable(false);
	}
	
	</script>		
</div>    	
HTML;
    	
		return $map_html;
    }

    function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS ".DB_TABLEPRE."googlemap;");
		$this->db->query("DELETE from ".DB_TABLEPRE."regular where regular='googlemap-default' and type=2");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|googlemap-default','')");
    }


}
