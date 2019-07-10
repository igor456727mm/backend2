/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: Apr 27, 2019
 */
drop view glr_allpoints;
CREATE VIEW 
`glr_allpoints` 
AS 
 select 
`t`.`TU` AS `TU`,
`t`.`FULL_NM` AS `FULL_NM`,
`p`.`TRNSP_PNT_ID` AS `TRNSP_PNT_ID`,
`p`.`LOC_PLAN_DTTM` AS `LOC_PLAN_DTTM`,
`s`.`TRNSP_PNT_STS_TYPE_NM` AS `TRNSP_PNT_STS_TYPE_NM`,
`p`.`TRNSP_PNT_STS_TYPE_CD`,
`p`.`STS_DTTM` AS `STS_DTTM`,
`ls`.`LOC_NM` AS `LOC_SRC_NM`,
`lt`.`LOC_NM` AS `LOC_TGT_NM`,
 if(`ls`.`LOC_NM` is null,`lt`.`LOC_NM`,`ls`.`LOC_NM`) as RC,
`lt`.`ADDR` AS `ADDR`,
`lt`.`LAT` AS `LAT`,
`lt`.`LON` AS `LON`,
`o`.`ORG_NM` AS `ORG_NM`,
`o`.`ORG_ID` AS `ORG_ID`,
`ot`.`ORG_TYPE_NM` AS `ORG_TYPE_NM`,
`lt`.`LOC_TYPE_CODE` AS `LOC_TGT_TYPE_CD`,
`t`.`TRNSP_ID` AS `TRNSP_ID`,
`lt`.`ORG_ID` AS `ORG_TGT_ID`,
`ls`.`ORG_ID` AS `ORG_SRC_ID`,
m.MARK,
p.REL_STS_DTTM,
m.MARK_COMMENT,
t.DRIVER_PHONE,
t.TRNSP_TYPE_CD,
tp.TRNSP_TYPE_NM
from 
 `glr_trnsp_pnt` `p` 
  join `glr_trnsp` `t` on `p`.`TRNSP_ID` = `t`.`TRNSP_ID`
  left join `glr_loc` `ls` on `ls`.`LOC_ID` = `p`.`LOC_SRC_ID`
  join `glr_loc` `lt` on `lt`.`LOC_ID` = `p`.`LOC_TGT_ID`
  join `glr_trnsp_pnt_sts_type` `s` on `s`.`TRNSP_PNT_STS_TYPE_CD` = `p`.`TRNSP_PNT_STS_TYPE_CD`
  join `glr_org` `o` on `o`.`ORG_ID` = `t`.`ORG_ID`
  join `glr_org_type` `ot` on `o`.`ORG_TYPE_CD` = `ot`.`ORG_TYPE_CD`
  left join glr_trnsp_pnt_mark m on m.TRNSP_PNT_ID=p.TRNSP_PNT_ID and MARK_TYPE_CD in ('SHOP_MARKS_TU','RC_MARKS_TU')
  left join glr_trnsp_type tp on tp.TRNSP_TYPE_CD=t.TRNSP_TYPE_CD;
