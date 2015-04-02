# flight_tracker
ECS 193 Lowest Flight Fare Tracker


below is our database schema
(the name of each table is listed above the table)
database name: flight_tracker

search_id
+---------+---------+------+-----+---------+-------+
| last_id | int(11) | YES  |     | NULL    |       |
+---------+---------+------+-----+---------+-------+

searches
+-------------+-------------+------+-----+---------------------+-------+
| search_id   | int(11)     | NO   | PRI | NULL                |       |
| email       | varchar(50) | NO   | PRI | NULL                |       |
| origin      | varchar(60) | NO   |     | NULL                |       |
| destination | varchar(60) | NO   |     | NULL                |       |
| depart_date | date        | NO   |     | NULL                |       |
| return_date | date        | YES  |     | NULL                |       |
| adults      | int(11)     | YES  |     | NULL                |       |
| children    | int(11)     | YES  |     | NULL                |       |
| seniors     | int(11)     | YES  |     | NULL                |       |
| seat_infant | int(11)     | YES  |     | NULL                |       |
| lap_infant  | int(11)     | YES  |     | NULL                |       |
| price       | int(11)     | YES  |     | NULL                |       |
| end         | timestamp   | NO   |     | 0000-00-00 00:00:00 |       |
| current     | timestamp   | NO   |     | 0000-00-00 00:00:00 |       |
+-------------+-------------+------+-----+---------------------+-------

airlines
+-----------+-------------+------+-----+---------+-------+
| Field     | Type        | Null | Key | Default | Extra |
+-----------+-------------+------+-----+---------+-------+
| search_id | int(11)     | NO   | PRI | NULL    |       |
| email     | varchar(50) | NO   | PRI | NULL    |       |
| airline   | varchar(25) | NO   |     | NULL    |       |
+-----------+-------------+------+-----+---------+-------+





























