<?php   if(!defined('DEDEINC')) exit('Request Error!');

 
function lib_bshare(&$ctag,&$refObj)
{
    global $dsql,$envs;

    $attlist='type|0';
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    $bscodeFile = DEDEDATA.'/cache/bshare.code.inc';
    if (!file_exists($bscodeFile))  return '';
    
    $reval = stripslashes(file_get_contents($bscodeFile));
    return $reval;
}