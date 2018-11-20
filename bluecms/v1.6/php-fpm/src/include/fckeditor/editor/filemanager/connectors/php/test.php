<?php
define('BLUE_ROOT', preg_replace('/includes(.*)/i', '', str_replace('\\', '/', __FILE__)));

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

$relative_blue_root = preg_replace('/includes(.*)/i', '', PHP_SELF);


echo BLUE_ROOT.'<br/>';

echo $relative_blue_root;








?>