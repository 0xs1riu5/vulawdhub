<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */
?>
            <footer>
                <p class="italic"><strong><a href="<?=ROOT_URL?>" title="Homeage">Simple Blog</a></strong> created for a PHP Technical Test &nbsp; | &nbsp;
                <?php if (!empty($_SESSION['is_logged'])): ?>
                    You are connected as Admin - <a href="<?=ROOT_URL?>?p=admin&amp;a=logout">Logout</a> &nbsp; | &nbsp;
                    <a href="<?=ROOT_URL?>?p=blog&amp;a=all">View All Blog Posts</a>
                <?php else: ?>
                    <a href="<?=ROOT_URL?>?p=admin&amp;a=login">Backend Login</a>
                <?php endif ?>
                </p>
            </footer>
        </div>
    </body>
</html>
