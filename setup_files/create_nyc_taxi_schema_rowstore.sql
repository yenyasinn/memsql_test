/* MemSQL and MySQL compatible */
/*
N.B. junk columns are there because some tripdata file headers are
inconsistent with the actual data, e.g. header says 20 or 21 columns per row,
but data actually has 22 or 23 columns per row, which COPY doesn't like.
junk1 and junk2 should always be null
*/

DROP TABLE IF EXISTS yellow_tripdata_staging;

CREATE TABLE yellow_tripdata_staging (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  vendor_id tinyint,
  tpep_pickup_datetime datetime,
  tpep_dropoff_datetime datetime,
  passenger_count smallint,
  trip_distance float,
  pickup_longitude double(15,15),
  pickup_latitude double(15,15),
  rate_code_id tinyint,
  store_and_fwd_flag varchar(10),
  dropoff_longitude double(15,15),
  dropoff_latitude double(15,15),
  payment_type tinyint,
  fare_amount float,
  extra float,
  mta_tax float,
  tip_amount float,
  tolls_amount float,
  improvement_surcharge float,
  total_amount float,
  pickup_location_id varchar(255),
  dropoff_location_id varchar(255),
  congestion_surcharge varchar(255),
  junk1 varchar(255),
  junk2 varchar(255),
  PRIMARY KEY (`id`)
  /* INDEX (`rate_code_id`), */
  /* INDEX (`payment_type`) */
);

/* Rate code */
DROP TABLE IF EXISTS rate_code;

CREATE TABLE rate_code (
  rate_code_id tinyint,
  name varchar(255),
  PRIMARY KEY (`rate_code_id`)
);

INSERT INTO rate_code VALUES (1, 'Standard rate'), (2, 'JFK'), (3, 'Newark'), (4, 'Nassau or Westchester'), (5, 'Negotiated fare'), (6, 'Group ride');

/* Payment type */
DROP TABLE IF EXISTS payment_type;

CREATE TABLE payment_type (
  payment_type tinyint,
  name varchar(255),
  PRIMARY KEY (`payment_type`)
);

INSERT INTO payment_type VALUES (1, 'Credit card'), (2, 'Cash'), (3, 'No charge'), (4, 'Dispute'), (5, 'Unknown'), (6, 'Voided trip');
