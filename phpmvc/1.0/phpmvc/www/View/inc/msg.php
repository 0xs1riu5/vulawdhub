<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */
?>
<?php if (!empty($this->sErrMsg)): ?>
    <p class="error"><?=$this->sErrMsg?></p>
<?php endif ?>

<?php if (!empty($this->sSuccMsg)): ?>
    <p class="success"><?=$this->sSuccMsg?></p>
<?php endif ?>
