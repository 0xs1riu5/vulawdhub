<div id="logos">
  <div id="logos-inside"> <img src="../admin/images/ecshop_logo.gif" alt="ECSHOP" /> </div>
<div id="lang-menu">
  <div id="lang-menu-inside">
    <?php echo $lang['select_installer_lang'];?>简
    <input type="radio" name="js-lang" id="js-zh_cn" class="p" />
    <label for="js-zh_cn"><?php echo $lang['simplified_chinese'];?></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if (EC_CHARSET == 'utf-8'){ ?>
    繁
    <input type="radio" name="js-lang" id="js-zh_tw" class="p" />
    <label for="js-zh_tw"><?php echo $lang['traditional_chinese'];?></label>&nbsp;&nbsp;&nbsp;&nbsp;
    EN
    <input type="radio" name="js-lang" id="js-en_us" class="p" />
    <label for="js-en_us"><?php echo $lang['americanese'];?></label><?php } ?>
  </div>
</div>
</div>
