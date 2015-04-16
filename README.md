# flight_tracker
ECS 193 Lowest Flight Fare Tracker

Herei our list of To-Do's:
-insert automated mail code into our search engine
-perform background search for single user
-perform background search for multiple users
-validate email and search window time in countdown.php
    - NOTE: (current time + search time) cannot be
	    greater than or equal to depart_date
-link to booking sites once user is satisfied with results
-polish UI (especially index.php)
-finalize images and logo


below is our database schema
(the name of each table is listed above the table)
database name: flight_tracker

searches
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
| lowest_price | float       | YES  |     | NULL                |                             |
+--------------+-------------+------+-----+---------------------+-----------------------------+

airlines
+-----------+-------------+------+-----+---------+-------+
| search_id | int(11)     | NO   | PRI | NULL    |       |
| email     | varchar(50) | NO   | PRI | NULL    |       |
| airline   | varchar(25) | NO   | PRI | NULL    |       |
+-----------+-------------+------+-----+---------+-------+


table name: 
    <email><requestID>

table attributes:
    option id
    option saletotal
    option segment flight carrier
    option segment flight number
    option segment cabin 
    option segment leg aircraft
    option segment leg arrival time
    option segment leg departure time
    option segment leg origin
    option segment leg destination
    option segment leg duration
    option segment leg mileage
    option segment leg meal
    option segment refundable
