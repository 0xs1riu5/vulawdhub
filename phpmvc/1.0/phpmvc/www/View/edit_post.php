<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */
?>
<?php require 'inc/header.php' ?>
<?php require 'inc/msg.php' ?>

<?php if (empty($this->oPost)): ?>
    <p class="error">Post Data Not Found!</p>
<?php else: ?>

    <form action="" method="post">
        <p><label for="title">Title:</label><br />
            <input type="text" name="title" id="title" value="<?=htmlspecialchars($this->oPost->title)?>" required="required" />
        </p>

        <p><label for="body">Body:</label><br />
            <textarea name="body" id="body" rows="5" cols="35" required="required"><?=htmlspecialchars($this->oPost->body)?></textarea>
        </p>

        <p><input type="submit" name="edit_submit" value="Update" /></p>
    </form>
<?php endif ?>

<?php require 'inc/footer.php' ?>
