<?php
// Get an option.
$cli_options = getopt('d:');

if (empty($cli_options['d'])) {
  echo 'Please provide an option';
  exit();
}

// Connect to database;
switch ($cli_options['d']) {
  case 'mysql':
    $mysqli = new mysqli('localhost', 'root', 'root', 'test', 3308);
    break;

  case 'memsql_rowstore':
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'rowstore', 3306);
    break;

  case 'memsql_columnstore':
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'columnstore', 3306);
    break;

  default:
    echo "Please provide valid option \n";
    exit();
}

function get_current_time() {
  return round(microtime(true) * 1000);
}
