#!/bin/bash

year_month_regex="tripdata_([0-9]{4})-([0-9]{2})"

yellow_schema_pre_2015="(vendor_id,tpep_pickup_datetime,tpep_dropoff_datetime,passenger_count,trip_distance,pickup_longitude,pickup_latitude,rate_code_id,store_and_fwd_flag,dropoff_longitude,dropoff_latitude,payment_type,fare_amount,extra,mta_tax,tip_amount,tolls_amount,total_amount)"

#yellow_schema_2015_2016_h1="(`vendor_id`, `tpep_pickup_datetime`, `tpep_dropoff_datetime`, `passenger_count`, `trip_distance`, `pickup_longitude`, `pickup_latitude`, `rate_code_id`, `store_and_fwd_flag`, `dropoff_longitude`, `dropoff_latitude`, `payment_type`, `fare_amount`, `extra`, `mta_tax`, `tip_amount`, `tolls_amount`, `improvement_surcharge`, `total_amount`)"
yellow_schema_2015_2016_h1="(vendor_id, tpep_pickup_datetime, tpep_dropoff_datetime, passenger_count, trip_distance, pickup_longitude, pickup_latitude, rate_code_id, store_and_fwd_flag, dropoff_longitude, dropoff_latitude, payment_type, fare_amount, extra, mta_tax, tip_amount, tolls_amount, improvement_surcharge, total_amount)"

yellow_schema_2016_h2="(vendor_id,tpep_pickup_datetime,tpep_dropoff_datetime,passenger_count,trip_distance,rate_code_id,store_and_fwd_flag,pickup_location_id,dropoff_location_id,payment_type,fare_amount,extra,mta_tax,tip_amount,tolls_amount,improvement_surcharge,total_amount,junk1,junk2)"

yellow_schema_2017_h1="(vendor_id,tpep_pickup_datetime,tpep_dropoff_datetime,passenger_count,trip_distance,rate_code_id,store_and_fwd_flag,pickup_location_id,dropoff_location_id,payment_type,fare_amount,extra,mta_tax,tip_amount,tolls_amount,improvement_surcharge,total_amount)"

yellow_schema_2019_h1="(vendor_id,tpep_pickup_datetime,tpep_dropoff_datetime,passenger_count,trip_distance,rate_code_id,store_and_fwd_flag,pickup_location_id,dropoff_location_id,payment_type,fare_amount,extra,mta_tax,tip_amount,tolls_amount,improvement_surcharge,total_amount,congestion_surcharge)"

for filename in $1/yellow_tripdata*.csv; do
  [[ $filename =~ $year_month_regex ]]
  year=${BASH_REMATCH[1]}
  month=$((10#${BASH_REMATCH[2]}))

  if [ $year -lt 2015 ]; then
    schema=$yellow_schema_pre_2015
  elif [ $year -eq 2015 ] || ([ $year -eq 2016 ] && [ $month -lt 7 ]); then
    schema=$yellow_schema_2015_2016_h1
  elif [ $year -eq 2016 ] && [ $month -gt 6 ]; then
    schema=$yellow_schema_2016_h2
  elif [ $year -lt 2019 ]; then
    schema=$yellow_schema_2017_h1
  else
    schema=$yellow_schema_2019_h1
  fi

  echo "MySQL"
  let date_start=`date +%s%3N`
  echo "`date`: beginning load for ${filename}"
  mysql -uroot -proot test -e  "LOAD DATA LOCAL INFILE '${filename}' INTO TABLE yellow_tripdata_staging FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES ${schema}"
  let date_end=`date +%s%3N`
  let duration=$date_end-$date_start
  echo "Duration: ${duration} ms"

  echo "MemSQL columnstore"
  let date_start=`date +%s%3N`
  echo "`date`: beginning load for ${filename}"
  memsql -uroot -proot columnstore --local-infile=1 -e  "LOAD DATA LOCAL INFILE '${filename}' INTO TABLE yellow_tripdata_staging FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES ${schema}"
  let date_end=`date +%s%3N`
  let duration=$date_end-$date_start
  echo "Duration: ${duration} ms"

  echo "MemSQL rowstore"
  let date_start=`date +%s%3N`
  echo "`date`: beginning load for ${filename}"
  memsql -uroot -proot rowstore --local-infile=1 -e  "LOAD DATA LOCAL INFILE '${filename}' INTO TABLE yellow_tripdata_staging FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES ${schema}"
  let date_end=`date +%s%3N`
  let duration=$date_end-$date_start
  echo "Duration: ${duration} ms"

done;
