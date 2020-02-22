#!/bin/bash

memsql -uroot -proot columnstore < setup_files/create_nyc_taxi_schema_columnstore.sql

memsql -uroot -proot rowstore < setup_files/create_nyc_taxi_schema_rowstore.sql

memsql -uroot -proot test < setup_files/create_nyc_taxi_schema_rowstore.sql
