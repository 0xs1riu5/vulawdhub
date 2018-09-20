<?php
return array(
    'howToRecommend'=>array(               //配置在表单中的键名 ,这个会是config[howToRecommend]
        'title'=>'选择推荐方法:',           //表单的文字
        'type'=>'checkbox',                   //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(                  //select 和radion、checkbox的子选项
            'rand'=>'随机推荐',                //值=>文字
            'city'=>'同城推荐',
            'admin'=>'管理员推荐',
            'data'=>'同资料推荐',
            'followfollow'=>'我关注的人关注推荐'
        ),
        'value'=>'1',                      //表单的默认值
    ),

    'howManyRecommend'=>array(               //配置在表单中的键名 ,这个会是config[howManyRecommend]
        'title'=>'选择推荐数量:',           //表单的文字
        'type'=>'select',                   //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(                  //select 和radion、checkbox的子选项
            '1'=>'1',                //值=>文字
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
        ),
        'value'=>'1',                      //表单的默认值
    ),

    'recommendUser'=>array(               //配置在表单中的键名 ,这个会是config[recommendUser]
        'title'=>'选择推荐用户:',           //表单的文字
        'type'=>'text',                   //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(                  //select 和radion、checkbox的子选项
        'value'=>'',

        ),
        'value'=>'1',
        'tip'=>'管理员推荐（输入ID时请用空格空开）',                     //表单的默认值
    ),
);

					