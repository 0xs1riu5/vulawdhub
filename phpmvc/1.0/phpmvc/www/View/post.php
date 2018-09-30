<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */
?>
<?php require 'inc/header.php' ?>

<?php if (empty($this->oPost)): ?>
    <p class="error">The post can't be be found!</p>
<?php else: ?>

    <article>
        <time datetime="<?=$this->oPost->createdDate?>" pubdate="pubdate"></time>

        <h1><?=htmlspecialchars($this->oPost->title)?></h1>
        <p><?=nl2br(htmlspecialchars($this->oPost->body))?></p>
        <p class="left small italic">Posted on <?=$this->oPost->createdDate?></p>

        <?php
            $oPost = $this->oPost;
            require 'inc/control_buttons.php';
        ?>
    </article>

<?php endif ?>

<?php require 'inc/footer.php' ?>
