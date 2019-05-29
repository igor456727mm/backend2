/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: Apr 29, 2019
 */
/*drop table etl_raw_tu_detail;*/


DROP TABLE etl_src_file;

DROP TABLE etl_src;

DROP TABLE etl_src_type;

DROP TABLE etl_src_status_type;

CREATE TABLE etl_src
(
	etl_src_id           INTEGER NOT NULL AUTO_INCREMENT,
	etl_src_name         VARCHAR(255) NULL,
	etl_src_type_cd      VARCHAR(32) NULL,
	etl_src_path         VARCHAR(255) NULL,
	PRIMARY KEY (etl_src_id)
);

CREATE TABLE etl_src_file
(
	etl_src_id           INTEGER NULL,
	etl_src_file_id      INTEGER NOT NULL AUTO_INCREMENT,
	etl_src_file_name    VARCHAR(255) NULL,
	etl_src_status_type_cd CHAR(18) NULL,
	etl_src_file_size    INTEGER NULL,
	PRIMARY KEY (etl_src_file_id)
);

CREATE TABLE etl_src_status_type
(
	etl_src_status_type_cd CHAR(18) NOT NULL,
	PRIMARY KEY (etl_src_status_type_cd)
);

CREATE TABLE etl_src_type
(
	etl_src_type_cd      VARCHAR(32) NOT NULL,
	etl_src_type_nm      VARCHAR(255) NULL,
	PRIMARY KEY (etl_src_type_cd)
);

ALTER TABLE etl_src
ADD FOREIGN KEY (etl_src_type_cd) REFERENCES etl_src_type (etl_src_type_cd);

ALTER TABLE etl_src_file
ADD FOREIGN KEY (etl_src_id) REFERENCES etl_src (etl_src_id);

ALTER TABLE etl_src_file
ADD FOREIGN KEY (etl_src_status_type_cd) REFERENCES etl_src_status_type (etl_src_status_type_cd);
