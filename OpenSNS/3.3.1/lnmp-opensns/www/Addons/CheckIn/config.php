<?php

return   array_merge( array(
    'action'=>array(
        'title'=>'签到绑定行为：',
        'type'=>'checkbox',
        'options'=>get_option(),
    ),
),
    get_option2()
);


function get_option(){
    $opt = D('Action')->getActionOpt();
    $return = array('no_action'=>'不绑定');
    foreach($opt as $v){
        $return[$v['name']] = $v['title'];
    }
    return $return;

}

function get_option2(){
    $type= M('ucenter_score_type');
    $opt=$type->select();
    foreach($opt as $v)
    {
        $arr[ 'score'.$v['id']] =
            array(
                'title'=>$v['title'],
                'type'=>'text',
                'value'=>0

            );


    }
    return $arr;
}