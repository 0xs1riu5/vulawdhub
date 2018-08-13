<?php
global $app;
helper::cd($app->getBasePath());
helper::import('module/misc/model.php');
helper::cd();
class extmiscModel extends miscModel 
{
public function hello2()
{
echo 'start from hello2.start.php<br>';
    echo $this->loadExtension('test')->hello();    // Load testMisc class from test.class.php in ext/model/class.
    return $this->testMisc->hello();               // After loading, can use $this->testMisc to call it.
}public function foo()
{
    return 'foo';
}
    public function hello()
    {
echo "start from hello.test.php <br>";
echo 'start from hellp.test2.php<br>';
        return 'hello world from hello()<br />';
    }

//**//
}