<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */

namespace TestProject\Engine;

// First, include necessary Pattern classes
require_once __DIR__ . '/Pattern/Base.trait.php';
require_once __DIR__ . '/Pattern/Singleton.trait.php';

class Loader
{
    use \TestProject\Engine\Pattern\Singleton; // Thanks Trait feature of PHP 5.4, I don't duplicate pattern code

    public function init()
    {
        // Register the loader method
        spl_autoload_register(array(__CLASS__, '_loadClasses'));
    }

    private function _loadClasses($sClass)
    {
        // Remove namespace and backslash
        $sClass = str_replace(array(__NAMESPACE__, 'TestProject', '\\'), '/', $sClass);

        if (is_file(__DIR__ . '/' . $sClass . '.php'))
            require_once __DIR__ . '/' . $sClass . '.php';

        if (is_file(ROOT_PATH . $sClass . '.php'))
            require_once ROOT_PATH . $sClass . '.php';
    }

}
