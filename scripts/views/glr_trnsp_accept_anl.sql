/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: May 28, 2019
 */
drop view glr_trnsp_accept_anl;
CREATE VIEW 
  `glr_trnsp_accept_anl` AS 
select 
 `glr_trnsp_accept`.`TRNSP_ACCEPT_ID` AS `TRNSP_ACCEPT_ID`,
 `glr_trnsp_accept`.`TU` AS `TU`,
 `glr_trnsp_accept`.`ACCEPTED_STS_TYPE_CD` AS `ACCEPTED_STS_TYPE_CD`,
 `glr_trnsp_accept`.`ORG_ID` AS `ORG_ID`,
 `glr_trnsp_accept`.`ACCEPTED_DTTM` AS `ACCEPTED_TIME`,
 `glr_trnsp_accept`.`RC` AS `RC`,
 `glr_trnsp_accept`.`SHOP` AS `SHOP`,
 `glr_org`.`ORG_NM` AS `ORG_NM`,
 `glr_trnsp_accept`.`ACCEPTED_DTTM` AS `LOC_PLAN_DTTM` 
from 
 (`glr_trnsp_accept` join `glr_org`) where (`glr_org`.`ORG_ID` = `glr_trnsp_accept`.`ORG_ID`);