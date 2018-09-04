 
<?php 
$k = array (
  'name' => 'ads',
  'id' => '15',
  'num' => '1',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>