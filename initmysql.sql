drop database if exists tj91;
create  database tj91;
use tj91;

drop table if exists mrbs_room;



CREATE TABLE mrbs_room
(
  id               int NOT NULL auto_increment,   
  disabled         tinyint(1) DEFAULT 0 NOT NULL, 
  type		       varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,  
  room_name        varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,  
  equipment		      varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci,	 
  description      varchar(200) CHARACTER  SET utf8 COLLATE utf8_general_ci,
  capacity         int DEFAULT '0' NOT NULL,
  usedtime		   int DEFAULT '0' NOT NULL,
  PRIMARY KEY (id)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO mrbs_room (room_name,type,  capacity ,equipment,description) VALUES ( 'ª·“È “ 1', 'ª·“È “', 20,'aaa',"ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1ºÚΩÈ1");
INSERT INTO mrbs_room (room_name,type, capacity ,equipment,description) VALUES ( 'ª·“È “ 2', 'ª·“È “', 10,'aaa',"ºÚΩÈ2");
INSERT INTO mrbs_room (room_name,type, capacity,equipment ,description) VALUES ( 'ª·“È “ 3', '∂‡π¶ƒ‹Ã¸', 30,'aaa',"ºÚΩÈ3");
INSERT INTO mrbs_room (room_name,type,disabled,  capacity,equipment ,description) VALUES ( 'ª·“È “ 4', 'ª·“È “',1, 40,'aaa',"ºÚΩÈ4");
INSERT INTO mrbs_room (room_name,type,  capacity,equipment ,description) VALUES ( 'ª·“È “ 5', '∂‡π¶ƒ‹Ã¸', 40,'aaa',"ºÚΩÈ5");
drop table if exists mrbs_entry;

CREATE TABLE mrbs_entry
(
  id             int NOT NULL auto_increment,
  name		   varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,
  student_id	  int DEFAULT '0' NOT NULL,
  room_id       int DEFAULT '1' NOT NULL,  
  tel        varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,
  email	    varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,
   unit	    varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,
  count	     int DEFAULT '1' NOT NULL,  
  theme	  	varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL,
  detail	 text CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL,  
  
  start_time     int DEFAULT '0' NOT NULL,
  end_time       int DEFAULT '0' NOT NULL,
  entry_type     int DEFAULT '0' NOT NULL,  
  timestamp      int DEFAULT '0' NOT NULL,
  
  status         tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idxStartTime (start_time),
  KEY idxEndTime   (end_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table if exists mrbs_users;

CREATE TABLE mrbs_users
(
  id        int NOT NULL auto_increment,
  level     smallint DEFAULT '0' NOT NULL,  
  name      varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci,
  password  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO mrbs_users (level, name, password ) VALUES ( '2', 'tj91', 'e0de3c61693cfeeb39da703c657be46c');
