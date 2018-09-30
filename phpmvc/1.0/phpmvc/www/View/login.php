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

<form action="" method="post">

    <p><label for="email">Email:</label><br />
        <input type="email" name="email" id="email" required="required" />
    </p>

    <p><label for="password">Password:</label><br />
        <input type="password" name="password" id="password" required="required" />
    </p>

    <p><input type="submit" value="Log In" /></p>
</form>

<?php require 'inc/footer.php' ?>
