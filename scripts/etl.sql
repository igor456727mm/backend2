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
create table etl_raw_tu_detail (
 ORG_NM varchar(2000) NULL,
 TU varchar(32) NULL,
 ACCEPTED varchar(32) NULL,
 RC varchar(2000) NULL,
 SHOP varchar(2000) NULL,
 LOG varchar(2000) NULL,
 LOG1 varchar(1) NULL,
 STATUS varchar(32) NULL,
 TRUCK_FORM varchar(32) NULL,
 TRUCK_TYPE varchar(32) NULL,
 AVIZ_DATE varchar(32) NULL,
 AVIZ_TIME varchar(32) NULL,
 ACCEPT_DATE varchar(32) NULL,
 ACCEPT_TIME varchar(32) NULL
);



CREATE TABLE glr_trnsp_accept
(
	TRNSP_ACCEPT_ID      INTEGER NOT NULL AUTO_INCREMENT,
	TU                   VARCHAR(32) NULL,
	ACCEPTED_STS_TYPE_CD VARCHAR(32) NULL,
	ORG_ID               INTEGER NULL,
	ACCEPTED_TIME        DATETIME NULL,
	RC                   VARCHAR(255) NULL,
	SHOP                 VARCHAR(255) NULL,
	PRIMARY KEY (TRNSP_ACCEPT_ID)
);

CREATE TABLE glr_trnsp_accept_hst
(
	ACCEPTED_STS_TYPE_CD VARCHAR(32) NULL,
	TU                   VARCHAR(32) NULL,
	RC                   VARCHAR(255) NULL,
	SHOP                 VARCHAR(255) NULL,
	TRNSP_ACCEPT_HST_ID  INTEGER NOT NULL AUTO_INCREMENT,
	TRNSP_PNT_MARK_FROM  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	TRNSP_PNT_MARK_TO    TIMESTAMP NULL,
	ORG_ID               INTEGER NULL,
	PRIMARY KEY (TRNSP_ACCEPT_HST_ID)
);

CREATE TABLE glr_trnsp_acptd_sts_type
(
	ACCEPTED_STS_TYPE_CD VARCHAR(32) NOT NULL,
	PRIMARY KEY (ACCEPTED_STS_TYPE_CD)
);

ALTER TABLE glr_trnsp_accept
ADD FOREIGN KEY (ACCEPTED_STS_TYPE_CD) REFERENCES glr_trnsp_acptd_sts_type (ACCEPTED_STS_TYPE_CD);

ALTER TABLE glr_trnsp_accept
ADD FOREIGN KEY (ORG_ID) REFERENCES glr_org (ORG_ID);

ALTER TABLE glr_trnsp_accept_hst
ADD FOREIGN KEY (ACCEPTED_STS_TYPE_CD) REFERENCES glr_trnsp_acptd_sts_type (ACCEPTED_STS_TYPE_CD);

ALTER TABLE glr_trnsp_accept_hst
ADD FOREIGN KEY (ORG_ID) REFERENCES glr_org (ORG_ID);

