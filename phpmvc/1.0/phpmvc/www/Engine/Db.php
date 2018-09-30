<?php
/**
 * @author           Pierre-Henry Soria <phy@hizup.uk>
 * @copyright        (c) 2015, Pierre-Henry Soria. All Rights Reserved.
 * @license          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
 * @link             http://hizup.uk
 */

namespace TestProject\Engine;

class Db extends \PDO
{

    public function __construct()
    {
        $aDriverOptions[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES UTF8';
        parent::__construct('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';', Config::DB_USR, Config::DB_PWD, $aDriverOptions);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

}
