<?php
require_once "../vendor/autoload.php";

function out($var, $var_name = ''): void {
	echo '<pre style="outline: 1px dashed red;padding:5px;margin:10px;color:white;background:black;">';
	if (!empty($var_name)) {
		echo '<h3>' . $var_name . '</h3>';
	}

	if (is_string($var)) {
		$var = htmlspecialchars($var);
	}
	print_r($var);
	echo '</pre>';
}

use Domos\CRest;
use Domos\Config;
use Domos\B24\Contact;
use Domos\B24\Deal;
use Domos\B24\Invoice;


$contactObj = new Contact();
out($contactObj->getContactFields());
exit;
$dealsToAdd = [];
for ($i = 0; $i < 150; $i++) {
	$dealsToAdd[] = [
		"NAME" => "LOH_$i"
	];
}
//out($deal->addAll($dealsToAdd));
//out($deal->add([
//	"TITLE" => "test"
//]));
