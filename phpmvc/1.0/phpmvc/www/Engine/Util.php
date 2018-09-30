<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */

namespace TestProject\Engine;

class Util
{
    public function getView($sViewName)
    {
        $this->_get($sViewName, 'View');
    }

    public function getModel($sModelName)
    {
        $this->_get($sModelName, 'Model');
    }

    /**
     * This method is useful in order to avoid the duplication of code (create almost the same method for "getView" and "getModel"
     */
    private function _get($sFileName, $sType)
    {
        $sFullPath = ROOT_PATH . $sType . '/' . $sFileName . '.php';
        if (is_file($sFullPath))
            require $sFullPath;
        else
            exit('The "' . $sFullPath . '" file doesn\'t exist');
    }

    /**
     * Set variables for the template views.
     *
     * @return void
     */
    public function __set($sKey, $mVal)
    {
        $this->$sKey = $mVal;
    }

}
