--TEST--
Table put variations
--SKIPIF--
<?php
include dirname(__FILE__) . "/skipif.inc";
?>
--FILE--
<?php
include dirname(__FILE__) . '/config.inc';

$tt = new TokyoTyrantTable(TT_TABLE_HOST, TT_TABLE_PORT);
$tt->vanish();

$rec = $tt->put(null, array('test' => 'data', 'something' => time()));
var_dump($tt->get($rec));

$rec = $tt->put($rec, array('test' => 'changed data', 'something' => '1234'));
var_dump($tt->get($rec));

$rec = $tt->putcat($rec, array('col' => 'new item'));
var_dump($tt->get($rec));

$rec = $tt->put(null, array("test\0data" => "data\0test", 'something' => time()));
$ret = $tt->get($rec);
var_dump(strlen($ret["test\0data"]));

try {
	$tt->putkeep($rec, array());
	echo "no exception\n";
} catch (TokyoTyrantException $e) {
	echo "got exception\n";
}	

$rec = $tt->put('foo', array('bar' => 'baz'));
var_dump($rec);
var_dump($tt->get($rec));
	
?>
--EXPECTF--
array(2) {
  ["test"]=>
  string(4) "data"
  ["something"]=>
  string(10) "%d"
}
array(2) {
  ["test"]=>
  string(12) "changed data"
  ["something"]=>
  string(4) "1234"
}
array(3) {
  ["test"]=>
  string(12) "changed data"
  ["something"]=>
  string(4) "1234"
  ["col"]=>
  string(8) "new item"
}
int(9)
got exception
string(3) "foo"
array(1) {
  ["bar"]=>
  string(3) "baz"
}
