# flight_tracker
ECS 193 Lowest Flight Fare Tracker

High Priority To-Do's:
-results page
    -graph
-algorithm

Lower Priority To-Do's:
-link to booking sites once user is satisfied with results (nina)

Lowest Priority To-Do's:
-put MySQL queries into transactions
-polish UI (especially index.php and search.php) (everyone)
-finalize images and logo (nina)

Quick guide to testing the background search:
    -first have SQL database running and ready at the comand line
    -also, have an idea for what the name of the soon-to-be created table
     is going to be so that you can quickly use it in the query that
     you're about to perform
	-an example would be like if I was going to type 
	 "example@ucdavis.edu" into search.php, then 
	 I would know that the first part of the table
	 name is going to be "exampleatucdavisdotedu", and the number
	 that directly follows the email can be determined by executing
	 'SELECT MAX(id) FROM SEARCHES;' and then adding 1 to the result
	 of that query.
	-let's say the max of mine was 246, then the table name would
	 become 'exampleatucdavisdotedu247'
	-the '@' and the '.' symbols were changed in the email
	 to "at" and "dot" because SQL was complaining about those symbols
    -perform a search by entering info on index.php and search.php
    -repeatedly enter the query "SELECT COUNT(opt_saletotal) FROM <tablename>;"
     into the SQL database and see if the number of entries increments over 
     time

below is the database schema
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
    NOTE: Replace the '@' symbol with 'AT'
	  and Replace the '.' with 'DOT'
	  when creating the table nam
	  (see background_search.php
	   for details)

table attributes:
    option id,
    option saletotal,
    option segment id
    option segment duration
    option segment flight carrier,
    option segment flight number,
    option segment cabin,
    option segment leg aircraft,
    option segment leg arrival time,
    option segment leg departure time,
    option segment leg origin,
    option segment leg destination,
    option segment leg duration,
    option segment leg mileage,
    option segment leg meal,
