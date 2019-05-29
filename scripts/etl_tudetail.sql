/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: May 15, 2019
 */

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




DROP TABLE glr_trnsp_accept;

DROP TABLE glr_trnsp_accept_hst;

DROP TABLE glr_trnsp_acptd_sts_type;

CREATE TABLE glr_trnsp_accept
(
	TRNSP_ACCEPT_ID      INTEGER NOT NULL AUTO_INCREMENT,
	TU                   VARCHAR(32) NULL,
	ACCEPTED_STS_TYPE_CD VARCHAR(32) NULL,
	ORG_ID               INTEGER NULL,
	ACCEPTED_DTTM        DATETIME NULL,
	RC                   VARCHAR(255) NULL,
	SHOP                 VARCHAR(255) NULL,
	PRIMARY KEY (TRNSP_ACCEPT_ID)
);

CREATE UNIQUE INDEX XAK1gle_trnsp_pnt_accept ON glr_trnsp_accept
(
	TU ASC,
	ORG_ID ASC,
	RC ASC,
	SHOP ASC
);

CREATE TABLE glr_trnsp_accept_hst
(
	ACCEPTED_STS_TYPE_CD VARCHAR(32) NULL,
	TU                   VARCHAR(32) NULL,
	RC                   VARCHAR(255) NULL,
	SHOP                 VARCHAR(255) NULL,
	TRNSP_ACCEPT_HST_ID  INTEGER NOT NULL AUTO_INCREMENT,
	TRNSP_ACCEPT_FROM    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	TRNSP_ACCEPT_TO      TIMESTAMP NULL,
	ORG_ID               INTEGER NOT NULL,
	PRIMARY KEY (TRNSP_ACCEPT_HST_ID)
);

CREATE UNIQUE INDEX XAK1glr_trnsp_accept_hst ON glr_trnsp_accept_hst
(
	TU ASC,
	RC ASC,
	SHOP ASC,
	ORG_ID ASC,
	ACCEPTED_STS_TYPE_CD ASC,
	TRNSP_ACCEPT_FROM ASC
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


create view etl_glr_trnsp_accept as 
 select 
 *,
 (
   select 
    max(ACCEPTED) 
   from 
    `etl_raw_tu_detail` b 
   where 
    b.TU=a.TU and
    b.ORG_NM=a.ORG_NM and
    b.RC=a.RC and
    b.SHOP=a.SHOP and
    str_to_date(concat(concat(b.`ACCEPT_DATE`,' '),b.`ACCEPT_TIME`),"%d.%m.%Y %H:%i")=a.accept_dttm) as ACCEPTED 
   from 
    (
     SELECT 
      `TU`,
      `ORG_NM`,
      `RC`,
      `SHOP`,
      max(str_to_date(concat(concat(`ACCEPT_DATE`,' '),`ACCEPT_TIME`),"%d.%m.%Y %H:%i")) as accept_dttm 
     FROM 
     `etl_raw_tu_detail` 
     group by 
     `TU`,`ORG_NM`,`RC`,`SHOP`
    ) a

CREATE  VIEW 
 `etl_glr_transp_accept_hst` AS 
select 
 `t`.`TU` AS `TU`,
 `t`.`ORG_NM` AS `ORG_NM`,
 `o`.`ORG_ID` AS `ORG_ID`,
 `t`.`RC` AS `RC`,
 `t`.`SHOP` AS `SHOP`,
 `t`.`ACCEPTED` AS `ACCEPTED`,
  str_to_date(concat(concat(`t`.`ACCEPT_DATE`,' '),`t`.`ACCEPT_TIME`),'%d.%m.%Y %H:%i') AS `accept_dttm` 
from 
  (
    `etl_raw_tu_detail` `t` 
     left join `glr_org` `o` 
     on((`t`.`ORG_NM` = `o`.`ORG_NM`))
  ) 
order by 
      `t`.`TU`,
      `t`.`RC`,
      `t`.`SHOP`,
       str_to_date(concat(concat(`t`.`ACCEPT_DATE`,' '),`t`.`ACCEPT_TIME`),'%d.%m.%Y %H:%i')