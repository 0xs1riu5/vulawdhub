/****************************************************
 * 												    *		
 * 			Sociax HTML 标签关联模型  			*
 *                                                  *
 ****************************************************/

/**
 * HTML 标签关联模型
 * @model-node 模型节点的标签属性标记
 * @event-node 模型下事件节点的标签属性标记
 * @author _慢节奏
 */
(function(window) {

var document = window.document;

/**
 * 激活模型
 * 
 * @param node 元素节点
 * @param node 父模型节点，若为空则将node 作为父模型节点
 * @param fns  挂载到标签上的事件方法，格式说明如下：
 * {
 *     model: {
 *         method1 : {
 *         	   click     : function(){},
 *         	   mouseover : function(){},
 *        	   mouseout  : function(){},
 *         	   load      : function(){}
 *     	   },
 *     	   method2 : {
 *             blur   : function(){},
 *             focus  : function(){},
 *             submit : function(){}
 *     	   }
 *     },
 *     event: {
 *         method1 : {
 *         	   click     : function(){},
 *         	   mouseover : function(){},
 *        	   mouseout  : function(){},
 *         	   load      : function(){}
 *     	   },
 *     	   method2 : {
 *             blur   : function(){},
 *             focus  : function(){},
 *             submit : function(){}
 *     	   }
 *     }
 * }
 */
var module = function( node, fns ) {
    module.addFns(fns);
	if ( node ) {
		// 预清除，防止重复模型化引起的双重缓存
		module.nodes.init( node );
	}
};

/**
 * 保存事件的方法
 * 
 * @param fns  挂载到标签上的事件方法，格式说明同module 函数的fns 参数
 */
module.addFns = function( fns ) {
	if ( !fns ) return module;
	if ( fns.model ) {
		module.addModelFns( fns.model );
	}
	if ( fns.event ) {
		module.addEventFns( fns.event );
	}
	return module;
};

/**
 * 保存模型事件的方法
 * 
 * @param fns  挂载到模型上的事件方法，格式说明如下：
 * {
 *     method1 : {
 *         click     : function(){},
 *         mouseover : function(){},
 *         mouseout  : function(){},
 *         load      : function(){}
 *     },
 *     method2 : {
 *         blur   : function(){},
 *         focus  : function(){},
 *         submit : function(){}
 *     }
 * } 
 */
module.addModelFns = function( fns ) {
	if ( "object" != typeof fns ) return module;
	var name;
	for ( name in fns ) {
		module.nodes.models.fns[name] = fns[name];
	}
	return module;
};

/**
 * 保存模型下事件节点的方法
 * 
 * @param fns  挂载到模型上的事件方法，属性说明同module.addModelFns 的fns 参数
 */
module.addEventFns = function( fns ) {
	if ( "object" != typeof fns ) return module;
	var name;
	for ( name in fns ) {
		module.nodes.events.fns[name] = fns[name];
	}
	return module;
};

/**
 * 获取节点的参数
 *
 * @param node 模型/事件节点
 */
module.getArgs = function( node ) {
	return node.getAttribute( "model-node" ) ? module.getModelArgs( node ) : module.getEventArgs( node );
};

/**
 * 设置节点的参数
 *
 * @param node 模型/事件节点
 * @param uri URI 格式的参数
 */
module.setArgs = function( node, uri ) {
	return node.getAttribute( "model-node" ) ? module.setModelArgs( node, uri ) : module.setEventArgs( node, uri );
};

/**
 * 获取模型节点的参数
 *
 * @param node 模型节点
 */
module.getModelArgs = function( node, uri ) {
    node.args || ( node.args = module.URI2Obj( node.getAttribute( "model-args" ) ) );
    return node.args;
};

/**
 * 设置模型节点的参数
 *
 * @param node 模型节点
 */
module.setModelArgs = function( node, uri ) {
    node.args = undefined;
    node.setAttribute( "model-args", uri );
    return module;
};

/**
 * 获取事件节点的参数
 *
 * @param node 事件节点
 */
module.getEventArgs = function( node ) {
    node.args || ( node.args = module.URI2Obj( node.getAttribute( "event-args" ) ) );
    return node.args;
};

/**
 * 设置事件节点的参数
 *
 * @param node 事件节点
 */
module.setEventArgs = function( node, uri ) {
    node.args = undefined;
    node.setAttribute( "event-args", uri );
    return module;
};

/**
 * 将uri转换为对象格式
 *
 * @param uri URI 格式的数据
 */
module.URI2Obj = function( uri ) {
	if ( ! uri ) return {};
    var obj = {},
    	args = uri.split( "&" ),
    	l, arg;
    l = args.length;
    while ( l -- > 0 ) {
        arg = args[l];
        if ( ! arg ) {
            continue;
        }
        arg = arg.split( "=" );
        obj[arg[0]] = arg[1];
    }
    return obj;
};

/**
 * 获取全局内指定的模型节点
 *
 * @param name 模型节点的命名
 */
module.getModels = function( name ) {
	return module.nodes.models[name];
};

/**
 * 获取全局内指定的事件节点
 *
 * @param name 事件节点的命名
 */
module.getEvents = function( name ) {
	return module.nodes.events[name];
}

/**
 * 删除节点上的监听
 *
 * @param object node 节点对象
 */
module.removeListener = function( node ) {
	module.nodes.removeListener( node );
	return module;
};

/**
 * 为节点添加监听
 *
 * @param object node 节点对象
 * @param object events 监听的事件
 * {
 *     click     : function(){},
 *     mouseover : function(){},
 *     mouseout  : function(){}
 * }
 */
module.addListener = function( node, events ) {
	module.nodes.addListener( node, events );
	return module;
};

module.getPreviousModel = function( node, siblingName ) {
	return module.nodes.getPreviousModel( node, siblingName );
};

module.getNextModel = function( node, siblingName ) {
	return module.nodes.getNextModel( node, siblingName );
};

module.getPreviousEvent = function( node, siblingName ) {
	return module.nodes.getPreviousEvent( node, siblingName );
};

module.getNextEvent = function( node, siblingName ) {
	return module.nodes.getNextEvent( node, siblingName );
};

/**
 * 模型化节点对象
 * 
 * @property function init 初始化模型
 * @property function _init 逐级扫描指定节点下的各级子元素的模型结构，并缓存模型和事件的DOM对象
 * @property function clear 清楚元素节点的子模型节点和子事件节点合集对象
 * @property function getParentModel
 * @property function addListener 为模型和事件节点附加事件方法
 * @property object _onload 自定义onload 事件
 * @property object _onload.execute 执行onload 事件队列
 * @property object _onload.queue onload 事件队列
 * @property object models 罗列并缓存模型节点
 * @property object events 罗列并缓存事件节点
 * @property object models.fns 存放模型节点的事件方法
 * @property object events.fns 存放事件节点的事件方法
 */
module.nodes = {
	init: function( node ) {
		// 初始化模型
		this._init( node );
		// 执行onload 事件
		this._onload.execute();
		return this;
	},
	_init: function( node, parentModel ) {
		var childNode = node.firstChild,
			childParentModel,
		    model_name,
		    event_name;

		! parentModel && ( parentModel = this.getParentModel( node ) );

		switch ( node.nodeName ) {
			case "DIV": case "UL":case "DL": 
			case "FORM":case "LI":case "DD": 
				model_name = node.getAttribute( "model-node" );
				if ( model_name ) {
					this._clearModel( node );

					node.modelName = model_name;

					this.addListener( node, this.models.fns[model_name] );

					node.parentModel = parentModel;

					( parentModel.childModels[model_name] = parentModel.childModels[model_name] || [] ).push( node );

					( this.models[model_name] = this.models[model_name] || [] ).push( node );

					childParentModel = node;
				}
				break;
			case "A": case "SPAN": case "LABEL":
			case "STRONG": case "INPUT": case "SELECT":
			case "BUTTON": case "IMG": case "TEXTAREA": 
			case "H1": case "H2": case "H3": case "H4":case "I":			
				event_name = node.getAttribute( "event-node" );
				if ( event_name ) {
					this._clearEvent(node);

					node.eventName = event_name;

					this.addListener( node, this.events.fns[event_name] );

					node.parentModel = parentModel;
					( parentModel.childEvents[event_name] = parentModel.childEvents[event_name] || [] ).push( node );

					( this.events[event_name] = this.events[event_name] || [] ).push( node );
				}
				break;
			case "HEAD": case "BODY":
				this[node.nodeName.toLowerCase()] = node;
				break;
			case "#document":
				this._clearModel(node);
				break;
		}

		! childParentModel && ( childParentModel = parentModel );
		while ( childNode ) {
			(1 == childNode.nodeType ) && this._init( childNode, childParentModel );
			childNode = childNode.nextSibling;
		}
	},
	_clearModel: function( node ) {
		node.modelName   = undefined;
		node.parentModel = undefined;
		node.childModels = {};
		node.childEvents = {};
		node.args = undefined;
		return this;
	},
	_clearEvent: function( node ) {
		node.eventName   = undefined;
		node.parentModel = undefined;
		node.args = undefined;
		return this;
	},
	getParentModel: function( node ) {
		var parentNode = node.parentNode,
			parentModel;
		if ( parentNode && 1 === parentNode.nodeType ) {
			parentModel = parentNode.getAttribute('model-node') ? parentNode : this.getParentModel( parentNode );
		}
		return parentModel || document;
	},
	getPreviousModel: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "model", siblingName: siblingName }, "previous" );
	},
	getNextModel: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "model", siblingName: siblingName }, "next" );
	},
	getPreviousEvent: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "event", siblingName: siblingName }, "previous" );
	},
	getNextEvent: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "event", siblingName: siblingName }, "next" );
	},
	_getSiblingNode: function( node, siblingArgs, direction ) {
		var sibling;
		if ( !node ) return null;
		sibling = node[ [ direction, "Sibling" ].join("") ];
		return ( sibling && ( siblingArgs.siblingName === sibling[ [ siblingArgs.siblingType, "Name" ].join("") ] ) ) ? sibling : this._getSiblingNode( sibling, siblingArgs, direction );
	},
	addListener: function( node, events ) {
        if ( "object" == typeof events ) {
        	var event;
            for ( event in events ) {
            	switch ( event ) {
            		case "load":
            			node[event] = events[event];
                        // 添加到队列
                        this._onload.queue.push( node );
           			    break;
            		case "callback":
            			node[event] = events[event];
            			break;
           			case "mouseenter": case "mouseleave":
           				// 兼容非IE
           				if ( document.addEventListener ) {
           					var refer = {mouseenter: "mouseover", mouseleave: "mouseout"};
           					node["on" + refer[event]] = (function( event, fn ){
           						return function( e ) {
	           						// 上一响应mouseover/mouseout 事件的元素
	           						var parent = e.relatedTarget;
	           						// 假如存在这个元素并且这个元素不等于目标元素
									while( parent && parent != this ){
										try {
											//上一响应的元素开始往上寻找目标元素
											parent = parent.parentNode;
										} catch( e ) {
											break;
										}
							 
									}
									// 假如找不到，表明当前事件触发点不在目标元素内
									if ( parent != this ) {
										//运行目标方法，否则不运行
										node[event] = fn;
										node[event]();
									}
								};
           					})( event, events[event] );
           				} else {
                        	node["on" + event] = events[event];
           				}
           				break;
            		default :
                        node["on" + event] = events[event];
            	}
            }
        }
	},
	removeListener: function( node ) {
		node.onclick = node.onfocus = node.onblur = node.onmouseout
		= node.onmouseover = node.onmouseenter = node.onmouserleave
		= node.onchange = null;
		return this;
	},
	_onload: {
		execute: function() {
			var l = this.queue.length,
				i;
			for ( i = 0; i < l; i ++ ) {
				
				this.queue[i]["load"]();
				this.queue[i]["load"] = undefined;	
				
			}
			// 重置队列
			this.queue = [];
		},
		queue: []
	},
	models: {
		fns: {

		}
	},
	events: {
		fns: {
		}
	},
	getHead: function() {
		this.head || (this.head = document.getElementsByTagName("head")[0]);
		return this.head;
	},
	getBody: function() {
		this.body || (this.body = document.getElementsByTagName("body")[0]);
		return this.body;
	}
};

/**
 * 加载CSS 文件
 *
 * @param string url CSS 文件URL
 */
module.getCSS = (function() {
	var temp = [];
	//返回内部包函数,供外部调用并可以更改temp的值
	return function( url ){
		var	head = module.nodes.getHead(),
			flag = 0,
			link,
			i = temp.length - 1;
		// 第二次调用的时候就不=0了
		for ( ; i >= 0; i -- ) {
			flag = ( temp[i] == url ) ? 1 : 0;
		}

		if ( flag == 0 ) {
			// 未载入过
		    link  = document.createElement( "link" );
			link.setAttribute( "rel", "stylesheet" );
			link.setAttribute( "type", "text/css" );
			link.setAttribute( "href", url );
			head.appendChild( link );
			temp.push( url );
		}
	};
})();

/**
 * 加载js 文件
 *
 * @param string url js 文件URL
 * @param function fn 执行函数
 */
module.getJS = (function() {
	var temp = [];
	//返回内部包函数,供外部调用并可以更改temp的值
	return function( url, fn ){
		// 第二次调用的时候就不=0了
		var	head,
			script,
			onload,
			flag = 0,
			i = temp.length - 1;
		// 第二次调用的时候就不=0了
		for ( ; i >= 0; i -- ) {
			flag = ( temp[i] == url ) ? 1 : 0;
		}

		if ( flag == 0 ) {
			// 未载入过
			// 记录url
			temp.push( url );
			// 载入
			head = module.nodes.getHead();
			script = document.createElement( "script" );
			script.setAttribute( "src", url );

			if ( "function" == typeof fn ) {
				script.onload = script.onreadystatechange = function() {
					// FF 下没有readyState 值，IE 有readyState 值，需加以判断
					if( ! this.readyState || "loaded" == this.readyState || "complete" == this.readyState ) {
						this.onload = this.onreadystatechange = null;

						fn();

						fn = undefined;

						script = undefined;
					}
				};
			}

			head.appendChild( script );
		} else {
			if("function" == typeof fn){
				fn();
				fn = undefined;
			}	
		}
	};
})();

/**
 * Execute functions when the DOM is ready 
 *
 * @param function fn 格式的数据
 */
module.ready = function( fn ) {
	if ( "function" !== typeof fn ) {
		return;
	}

	if ( document.addEventListener ) {
		// Use the handy event callback
		document.addEventListener( "DOMContentLoaded", fn, false );
	// If IE event model is used
	} else if ( document.attachEvent ) {
		// maybe late but safe also for iframes
		document.attachEvent( "onreadystatechange", fn );
	}
};

window.M = module;

M.ready(function() {
	M(document);
});

})(window);