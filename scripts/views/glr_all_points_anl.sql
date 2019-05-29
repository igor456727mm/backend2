drop view glr_all_points_anl;
CREATE VIEW 
 `glr_all_points_anl` 
AS 
select 
 `t`.`TU` AS `TU`,
 `t`.`FULL_NM` AS `FULL_NM`,
 `p`.`TRNSP_PNT_ID` AS `TRNSP_PNT_ID`,
 `p`.`LOC_PLAN_DTTM` AS `LOC_PLAN_DTTM`,
 `s`.`TRNSP_PNT_STS_TYPE_NM` AS `TRNSP_PNT_STS_TYPE_NM`,
 `p`.`STS_DTTM` AS `STS_DTTM`,
 `ls`.`LOC_NM` AS `LOC_SRC_NM`,
 `lt`.`LOC_NM` AS `LOC_TGT_NM`,
 `lt`.`ADDR` AS `ADDR`,
 `lt`.`LAT` AS `LAT`,
 `lt`.`LON` AS `LON`,
 `o`.`ORG_NM` AS `ORG_NM`,
 `o`.`ORG_ID` AS `ORG_ID`,
 `ot`.`ORG_TYPE_NM` AS `ORG_TYPE_NM`,
 (unix_timestamp(`p`.`LOC_PLAN_DTTM`) - unix_timestamp(`p`.`STS_DTTM`)) AS `TIME_DIFF_FACT` 
from 
 ((((((`glr_trnsp_pnt` `p` join `glr_trnsp` `t`) 
 left join `glr_loc` `ls` on((`ls`.`LOC_ID` = `p`.`LOC_SRC_ID`))) 
 join `glr_loc` `lt`) 
 join `glr_trnsp_pnt_sts_type` `s`) 
 join `glr_org` `o`) 
 join `glr_org_type` `ot` on((`o`.`ORG_TYPE_CD` = `ot`.`ORG_TYPE_CD`))) 
where 
 ((`p`.`TRNSP_ID` = `t`.`TRNSP_ID`) and
  (`lt`.`LOC_ID` = `p`.`LOC_TGT_ID`) and
  (`s`.`TRNSP_PNT_STS_TYPE_CD` = `p`.`TRNSP_PNT_STS_TYPE_CD`) and (`o`.`ORG_ID` = `t`.`ORG_ID`) and (`s`.`TRNSP_PNT_STS_TYPE_CD` in ('DELIVERED','RELEASED')));