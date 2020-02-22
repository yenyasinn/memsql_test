Scripts to perform performance testing for memsql rowstore and columnstore and comparison with mysql.

Idea of https://github.com/toddwschneider/nyc-taxi-data/ has been taken as a base.

List of files to download - https://github.com/toddwschneider/nyc-taxi-data/blob/master/setup_files/raw_data_urls.txt

**Load data from files to database:**
```
./import_trip_data.sh /var/www/memsql_test/data
```
