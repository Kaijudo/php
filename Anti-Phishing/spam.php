<?php
require_once('inc/class.AntiPhishing.php');
set_time_limit(0);

if($argc < 2) {
    echo "Usage: php vote.php <Proxy 1/0> <Amount>\n";
}
elseif ($argc[1] == 0) {
    $amount = $argv[2];
	$sp = new AntiPhishing();
	$times = $sp->spam($amount);
	
}
else {
	
    $amount = $argv[2];
	$sp = new AntiPhishing();
    $times = $sp->spamp($amount, 'proxies.txt');
    echo 'Successfully spammed ' . $times['times'] . '/' . $times['total'] . ' time(s)' . "\n";

}

?>
