<extend name="Base/common"/>

<block name="body">
    <style>
        .font{
            font-size: 25px;;
        }
    </style>

    <?php
    $img_id = modC('JUMP_BACKGROUND','','config');
    if($img_id){
      $background =get_cover($img_id,'path');
    }else{
    $background = '__PUBLIC__/images/jump_background.jpg';
    }

     ?>

    <div class="" style="padding:300px 100px 0 100px;height: 650px; background: url(<?php echo($background); ?>)">

<div class="text-center " style="margin: 0 auto; ">

<?php if(isset($success_message)) {?>

<div class="alert alert-success with-icon">
        <i class="icon-ok-sign"></i>
        <div class="content">

<p class="font"><?php echo($success_message); ?></p>


</div>

</div>

<?php }else{?>

<div class="alert alert-danger with-icon">
    <i class="icon-remove-sign"></i>
    <div class="content">

        <p class="font"> <?php echo($error_message); ?></p>


</div>
</div>

<?php }?>


    <p class="jump">
        页面自动 <a id="href" style="color: green" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>。

        或 <a href="http://{$_SERVER['HTTP_HOST']}__ROOT__" style="color: green">返回首页</a>
    </p>


    </div>

    </div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>

</block>

