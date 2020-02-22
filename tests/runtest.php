<?php
/**
 * Usage: "php runtest.php -d [mysql|memsql_rowstore|memsql_columnstore]"
 */

include '_connect_db.php';

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


$queries = [
  "SELECT * FROM yellow_tripdata_staging WHERE id = 50",
  "SELECT * FROM yellow_tripdata_staging WHERE payment_type = 2",
  "SELECT * FROM yellow_tripdata_staging WHERE fare_amount >= 10 AND fare_amount <= 20",
  "SELECT avg(fare_amount) FROM yellow_tripdata_staging WHERE payment_type = 2",
  "SELECT count(*) FROM yellow_tripdata_staging WHERE payment_type = 2",
  "SELECT * FROM yellow_tripdata_staging WHERE store_and_fwd_flag LIKE 'Y%'",
  "SELECT payment_type, pickup_longitude, pickup_latitude FROM yellow_tripdata_staging WHERE payment_type > 3 LIMIT 100",
  "SELECT tpep_pickup_datetime, tpep_dropoff_datetime FROM yellow_tripdata_staging WHERE tpep_pickup_datetime >= '2016-01-01 00:00:00' AND tpep_pickup_datetime <= '2016-01-01 01:00:00' ORDER BY tpep_pickup_datetime DESC",
  "SELECT COUNT(tip_amount), payment_type FROM yellow_tripdata_staging GROUP BY payment_type;",
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
