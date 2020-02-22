<?php
/**
 * Usage: "php runtest.php -d [mysql|memsql_rowstore|memsql_columnstore]"
 */

include '_connect_db.php';

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


$queries = [
  "SELECT count(*) FROM yellow_tripdata_staging;",
  "SELECT * FROM yellow_tripdata_staging as t INNER JOIN rate_code as r ON t.rate_code_id = r.rate_code_id",
  "SELECT * FROM yellow_tripdata_staging as t INNER JOIN rate_code as r ON t.rate_code_id = r.rate_code_id INNER JOIN payment_type as p ON t.payment_type = p.payment_type",
];

foreach ($queries as $query) {
  echo "Test query: $query \n";

  for ($i = 1; $i <= 3; $i++ ) {
    $start_time = get_current_time();

    $result = $mysqli->query($query);
    //var_dump($result);
    if ($result !== FALSE) {
      $end_time = get_current_time();

      $duraion = $end_time - $start_time;
      echo "Duration $i: $duraion ms. \n";
    }
    else {
      echo "Execution failed \n";
    }
  }
}


$mysqli->close();
