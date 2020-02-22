<?php
/**
 * Usage: "php runupdate.php -d [mysql|memsql_rowstore|memsql_columnstore]"
 */

include '_connect_db.php';

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// Update single items.
$query = "UPDATE yellow_tripdata_staging SET tip_amount = 0, tolls_amount = 0 WHERE id = ";
echo "Test query: $query \n";

try {
  $start_time = get_current_time();
  // Repeat 10 000 times.
  for ($i = 1; $i <= 10000000; $i+=10000) {

    $result = $mysqli->query($query . $i);
    if ($result === FALSE) {
      throw new Exception('Failed');
    }
  }

  $end_time = get_current_time();
  $duraion = $end_time - $start_time;

  echo "Duration $i: $duraion ms. \n";
}
catch (Exception $e) {
  echo "Execution failed \n";
}

// Update range of items.
$query = "UPDATE yellow_tripdata_staging SET tip_amount = 0, tolls_amount = 0 WHERE id >= 1 AND id <= 10000";
echo "Test query: $query \n";

$start_time = get_current_time();

$result = $mysqli->query($query);

if ($result !== FALSE) {
  $end_time = get_current_time();

  $duraion = $end_time - $start_time;
  echo "Duration $i: $duraion ms. \n";
}
else {
  echo "Execution failed \n";
}

$mysqli->close();
