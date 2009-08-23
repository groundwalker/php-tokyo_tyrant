--TEST--
Test a large out query
--SKIPIF--
<?php
include dirname(__FILE__) . "/skipif.inc.php";
?>
--FILE--
<?php
include 'config.inc.php';

$tt = new TokyoTyrantTable(TT_TABLE_HOST, TT_TABLE_PORT);
$tt->vanish();

function tt_populate($start, $amount) 
{
	global $tt;

	for ($i = $start; $i < $amount; $i++) {
		$tt->put('cherry_' . $i,     array('color' => 'red'));
		$tt->put('strawberry_' . $i, array('color' => 'red'));
	}
}

tt_populate(0, 10000);

$query = $tt->getQuery();
$query->addCond('color', TokyoTyrant::RDBQC_STREQ, 'red');

echo "# removing " . $query->count() . " #\n";
$query->out();

tt_populate(0, 100000);

$query = $tt->getQuery();
$query->addCond('color', TokyoTyrant::RDBQC_STREQ, 'red');

echo "# removing " . $query->count() . " #\n";
$query->out();

?>
--EXPECTF--
# removing 20000 #
# removing 200000 #