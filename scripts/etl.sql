/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: Apr 29, 2019
 */
drop table etl_raw_tu_detail;
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