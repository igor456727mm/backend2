/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: May 15, 2019
 */

insert into glr_trnsp_acptd_sts_type SELECT distinct `ACCEPTED` FROM `etl_raw_tu_detail` WHERE 1;
create view etl_glr_trnsp_accept as 
 select 
 *,
 (
   select 
    ACCEPTED 
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