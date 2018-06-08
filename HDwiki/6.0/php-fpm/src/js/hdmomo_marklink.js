/********************************************************************************/
/*  Copyright (c) 2005-2013 Baike.com                                         */
/********************************************************************************/
var hdmomo_iLinkCount = 100;
var hdmomo_iStepLen = 0;
var hudong_gNodesList = new Array();
var hudong_gNodesDataList = new Array();
var hudong_LinkCount = 0;

hudong_RunMain();

function hudong_RunMain()
{
		hudong_gNodesList = hudong__getAllTextNodes(document.body);
		var len = hudong_gNodesList.length;
		var NodesDataList = new Array;
		for (var j = 0; j < hudong_gNodesList.length; j++)
		{
			NodesDataList[j] = hudong_gNodesList[j].data;
		}
		hudong_gNodesDataList = NodesDataList;
		for (var j = 0; j < hudong_gWebKeyWords.length; j++)
		{
			if (typeof hudong_gWebKeyWords[j] == "undefined" || hudong_gWebKeyWords[j] == null) {
				continue;
			}
			v_keyword = hudong_gWebKeyWords[j];
			if (v_keyword == null) {
				continue;
			}

			for (var i = 0; i < hudong_gNodesList.length; i++)
			{
				v_node = hudong_gNodesList[i];
				if (v_node == null)	continue;
				var nodeData = hudong_gNodesDataList[i];
				var words = new Array;
				var index = nodeData.indexOf(v_keyword);
				if (-1 != index)
				{
					if (hdmomo_iStepLen/2 > index || nodeData.length-hdmomo_iStepLen/2 < index+v_keyword.length)continue;
					words[0] = nodeData.substring(0, index);
					words[1] = nodeData.substring(index+v_keyword.length);
					var newNode = document.createElement('SPAN');
					for (var k = 0; k < 2; k++)
					{
						if (0 != words[k].length)
						{
							var tempTextNode = newNode.appendChild(document.createTextNode(words[k]));
							hudong_gNodesList[hudong_gNodesList.length] = tempTextNode;
							hudong_gNodesDataList[hudong_gNodesDataList.length] = tempTextNode.data;
						}
						if (k < words.length -1)
						{
							var temp = document.createTextNode(v_keyword);
							var new_Node = document.createElement('A');
							new_Node.href=hdmomo_siteurl+"/index.php?doc-innerlink-"+encodeURI(hudong_gWebKeyWords[j]);
							new_Node.title = hudong_gWebKeyWords[j];
							if(document.all){
								new_Node.className = "innerlink";
							}else{
								new_Node.setAttribute("class", "innerlink");
							}
							if (typeof hdmomo_sLinkColor != "undefined"){new_Node.style.color = hdmomo_sLinkColor;}
							new_Node.appendChild(temp);
							newNode.appendChild(new_Node);
							hudong_LinkCount++;
						}
					}
					var fatherNode = v_node.parentNode;
					fatherNode.replaceChild(newNode, v_node);
					hudong_gNodesList[i] =	null;
					hudong_gWebKeyWords[j] = null;
					break;
				}
			}
			if (hudong_LinkCount >= hdmomo_iLinkCount) break;
		}
}

function hudong_Trim(v_str)
{
	if (typeof v_str == "undefined") return "";
	return v_str.replace(/^\s*|\s*$/g,"");
}

function hudong__getAllTextNodes(v_fatherNode)
{
	var NodesList = new Array;
	var layer = v_fatherNode.childNodes;
	var childNum = layer.length;
	for (var i = 0; i < childNum; i++)
	{
		var childNode = layer[i];
		var noteType = childNode.nodeType;
		if (3 == noteType && 0 != hudong_Trim(childNode.data).length)
		{
			NodesList[NodesList.length] = childNode;
		}
		if (1 == noteType && "A" != childNode.nodeName.toUpperCase())
		{
			NodesList = NodesList.concat(hudong__getAllTextNodes(childNode));
		}
	}
	return NodesList;
}