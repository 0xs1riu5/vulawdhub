<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php if(isset($advlist) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
    <?php foreach((array)$advlist as $key=>$adv) {?>
        <?php if($key!==5 && $key!==6) { ?>
            <?php if(isset($adv['title'])) { ?>
                <div style="display:none;" id="hide_<?php echo $key?>"><?php echo $adv['code']?></div>
                <script type="text/javascript">
                $("#advlist_<?php echo $key?>")[0].innerHTML = $("#hide_<?php echo $key?>")[0].innerHTML;
				$("#hide_<?php echo $key?>").remove();
               </script>
            <?php } else { ?> 
                <?php foreach((array)$adv as $post) {?>
                    <div style="display:none;" id="hide_<?php echo $key?>_<?php echo $post['position']?>"><?php echo $post['code']?></div>
                    <script type="text/javascript">
                    $("#advlist_<?php echo $key?>_<?php echo $post['position']?>")[0].innerHTML = $("#hide_<?php echo $key?>_<?php echo $post['position']?>")[0].innerHTML;
					$("#hide_<?php echo $key?>_<?php echo $post['position']?>").remove(); 
                    </script>
                <?php }?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if(!empty($advlist[5]) || !empty($advlist[6])) { ?>
	<div style=" width:100%; clear: both;">
    <script src="js/floatadv.js"></script>
	<script type="text/javascript">
		<?php echo $advlist[5][code]?>
		<?php echo $advlist[6][code]?>
		theFloaters.play();
	</script>
	</div>
<?php } ?>