<?php
	function make_assoc($array, $key, $value, $pre = null){
    $res = array();
    if ($pre){
      $res[$pre[0]] = $pre[1];
    }
    foreach($array as $row){
      $res[$row[$key]] = $row[$value];
    }
    return $res;
	}
?>
