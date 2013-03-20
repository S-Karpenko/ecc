<?php
// Get Autocompleter class
$library = dirname(dirname(__FILE__));
require_once($library.'/autocompleter.php');

// Get rows
$rows = MijosefAutocompleter::loadResultArray("SELECT title FROM", "mijosef_tags", "WHERE published = '1' ORDER BY title");

if (!empty($rows)) {
	$q = $_REQUEST['q'];
	foreach($rows as $index => $row) {
		if(strpos(strtolower($row), $q) === 0) {
			echo $row . "\n";
		}
	}
}
?>
