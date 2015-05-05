# flight_tracker
ECS 193 Lowest Flight Fare Tracker

High Priority To-Do's:
-fix Book It bugs
-session logout
-algorithm
    -cycle through keys
    -only email when price decreases
    -link search period button to background search
-comment code
-test

Low Priority To-Do's:
-graph update dynamically 
-put MySQL queries into transactions
-polish UI 
-finalize images and logo 


below is our database schema
(the name of each table is listed above the table)
database name: flight_tracker

Table name: searches
+--------------+-------------+------+-----+---------------------+-----------------------------+
| Field        | Type        | Null | Key | Default             | Extra                       |
+--------------+-------------+------+-----+---------------------+-----------------------------+
| ID           | int(11)     | NO   | PRI | NULL                | auto_increment              |
| email        | varchar(50) | NO   | PRI | NULL                |                             |
| origin       | varchar(60) | NO   |     | NULL                |                             |
| destination  | varchar(60) | NO   |     | NULL                |                             |
| depart_date  | date        | NO   |     | NULL                |                             |
| return_date  | date        | YES  |     | NULL                |                             |
| adults       | int(11)     | YES  |     | NULL                |                             |
| children     | int(11)     | YES  |     | NULL                |                             |
| seniors      | int(11)     | YES  |     | NULL                |                             |
| seat_infant  | int(11)     | YES  |     | NULL                |                             |
| lap_infant   | int(11)     | YES  |     | NULL                |                             |
| price        | int(11)     | YES  |     | NULL                |                             |
| current      | timestamp   | NO   |     | CURRENT_TIMESTAMP   | on update CURRENT_TIMESTAMP |
| end          | timestamp   | NO   |     | 0000-00-00 00:00:00 |                             |
| lowest_price | float       | YES  |     | 5000                |                             |
| one_way      | tinyint(1)  | NO   |     | NULL                |                             |
+--------------+-------------+------+-----+---------------------+-----------------------------+

Table name: airlines
+-----------+-------------+------+-----+---------+-------+
| Field     | Type        | Null | Key | Default | Extra |
+-----------+-------------+------+-----+---------+-------+
| search_id | int(11)     | NO   | PRI | NULL    |       |
| email     | varchar(50) | NO   | PRI | NULL    |       |
| airline   | varchar(25) | NO   | PRI | NULL    |       |
+-----------+-------------+------+-----+---------+-------+

Table name: <search request ID number>
+----------------------------------+-------------+------+-----+---------------------+-----------------------------+
| Field                            | Type        | Null | Key | Default             | Extra                       |
+----------------------------------+-------------+------+-----+---------------------+-----------------------------+
| opt_id                           | varchar(60) | NO   | PRI | NULL                |                             |
| opt_saletotal                    | float       | NO   | PRI | NULL                |                             |
| opt_slice_num                    | tinyint(4)  | NO   |     | NULL                |                             |
| opt_slice_seg_id                 | varchar(60) | NO   | PRI | NULL                |                             |
| opt_slice_seg_duration           | int(11)     | NO   |     | NULL                |                             |
| opt_slice_seg_flight_carrier     | varchar(40) | NO   |     | NULL                |                             |
| opt_slice_seg_flight_num         | varchar(10) | NO   |     | NULL                |                             |
| opt_slice_seg_cabin              | varchar(20) | NO   |     | NULL                |                             |
| opt_slice_seg_leg_id             | varchar(60) | NO   | PRI | NULL                |                             |
| opt_slice_seg_leg_aircraft       | varchar(20) | NO   |     | NULL                |                             |
| opt_slice_seg_leg_arrival_time   | timestamp   | NO   |     | CURRENT_TIMESTAMP   | on update CURRENT_TIMESTAMP |
| opt_slice_seg_leg_departure_time | timestamp   | NO   |     | 0000-00-00 00:00:00 |                             |
| opt_slice_seg_leg_origin         | varchar(10) | NO   |     | NULL                |                             |
| opt_slice_seg_leg_destination    | varchar(10) | NO   |     | NULL                |                             |
| opt_slice_seg_leg_duration       | int(11)     | NO   |     | NULL                |                             |
| opt_slice_seg_leg_mileage        | int(11)     | NO   |     | NULL                |                             |
| opt_slice_seg_leg_meal           | varchar(20) | NO   |     | NULL                |                             |
| query_time                       | timestamp   | NO   |     | 0000-00-00 00:00:00 |                             |
+----------------------------------+-------------+------+-----+---------------------+-----------------------------+
