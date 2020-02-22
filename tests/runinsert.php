<?php
/**
 * Usage: "php runtest.php -d [mysql|memsql_rowstore|memsql_columnstore]"
 */

include '_connect_db.php';

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$query = "INSERT INTO yellow_tripdata_staging VALUES(NULL, %d, '%s', '%s', %d, %f, %f, %f, %d, '%s', %f, %f, %d, %f, %f, %f, %f, %f, %f, %f, '%s', '%s', '%s', '%s', '%s')";

function prepare_query($query, $now) {
  return sprintf($query,
    rand(1, 2),
    date('Y-m-d H:i:s', rand(0, $now)),
    date('Y-m-d H:i:s', rand(0, $now)),
    rand(1, 4),
    rand(0, 50),
    0,
    0,
    rand(1, 6),
    'Y',
    0,
    0,
    rand(1, 6),
    rand(0, 50),
    rand(0, 50),
    rand(0, 50),
    rand(0, 50),
    rand(0, 50),
    rand(0, 50),
    rand(0, 50),
    '',
    '',
    '',
    '',
    ''
  );
}

echo "Test query: $query \n";

try {
  $start_time = get_current_time();

  $now = time();
  // Insert 10 000 items.
  for ($i = 1; $i <= 10000; $i++) {

    $q = prepare_query($query, $now);
    $result = $mysqli->query($q);
    if ($result === FALSE) {
      throw new Exception('Failed');
    }
  }

  $end_time = get_current_time();
  $duration = $end_time - $start_time;

  echo "Duration: $duration ms. \n";
}
catch (Exception $e) {
  echo "Execution failed \n";
}

// Bulk insert.
$query = "INSERT INTO yellow_tripdata_staging VALUES ";
$val = "(NULL, %d, '%s', '%s', %d, %f, %f, %f, %d, '%s', %f, %f, %d, %f, %f, %f, %f, %f, %f, %f, '%s', '%s', '%s', '%s', '%s')";
echo "Test query: $query \n";
try {
  $start_time = get_current_time();

  $now = time();
  // Insert 10 000 items.
  for ($i = 1; $i <= 10; $i++) {
    $values = [];
    for ($i = 1; $i <= 1000; $i++) {
      $values[] = prepare_query($val, $now);
    }

    $q = $query . implode(',', $values);
    $result = $mysqli->query($q);
    if ($result === FALSE) {
      throw new Exception('Failed');
    }
  }

  $end_time = get_current_time();
  $duration = $end_time - $start_time;

  echo "Duration: $duration ms. \n";
}
catch (Exception $e) {
  echo "Execution failed \n";
}
