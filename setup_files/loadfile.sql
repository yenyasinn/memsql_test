LOAD DATA LOCAL INFILE '/var/www/drupal88/memsql_data/yellow_sample.csv'
  INTO TABLE `yellow_tripdata_staging`
  FIELDS TERMINATED BY ','
  LINES TERMINATED BY '\n'
  IGNORE 1 LINES
  (`vendor_id`, `tpep_pickup_datetime`, `tpep_dropoff_datetime`, `passenger_count`, `trip_distance`, `pickup_longitude`, `pickup_latitude`, `rate_code_id`, `store_and_fwd_flag`, `dropoff_longitude`, `dropoff_latitude`, `payment_type`, `fare_amount`, `extra`, `mta_tax`, `tip_amount`, `tolls_amount`, `improvement_surcharge`, `total_amount`);
