<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */

namespace TestProject\Engine\Pattern;

trait Singleton
{

    use Base;

    protected static $_oInstance = null;

    /**
     * Get instance of class.
     *
     * @access public
     * @static
     * @return object Return the instance class or create first instance of the class.
     */
    public static function getInstance()
    {
        return (null === static::$_oInstance) ? static::$_oInstance = new static : static::$_oInstance;
    }

}

