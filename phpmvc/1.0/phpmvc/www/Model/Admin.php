<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */

namespace TestProject\Model;

class Admin extends Blog
{

    public function login($sEmail, $sPassword)
    {
        $oStmt = $this->oDb->prepare("SELECT email, password FROM Admins WHERE email = '{$sEmail}' LIMIT 1");
        $oStmt->execute();
         //fix by tinyfisher
        //$oStmt = $this->oDb->prepare("SELECT email, password FROM Admins WHERE email = ? LIMIT 1");
        //$oStmt->execute($sEmail);
        $oRow = $oStmt->fetch(\PDO::FETCH_OBJ);

        return password_verify($sPassword, @$oRow->password); // Use the PHP 5.5 password function
    }

}
