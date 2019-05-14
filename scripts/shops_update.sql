ALTER TABLE `glr_org`  
ADD `ORG_STS_TYPE_CD` VARCHAR(32) NOT NULL DEFAULT 'ACTIVE'  AFTER `ORG_TYPE_CD`,  
ADD `STS_DTTM` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `ORG_STS_TYPE_CD`;

update glr_org o, glr_loc l set o.ORG_STS_TYPE_CD=l.LOC_STS_TYPE_CD where l.ORG_ID=o.ORG_ID;

delete from glr_cntct where org_id in (select ORG_ID from glr_org where not exists (select 1 from glr_loc l where l.ORG_ID=glr_org.ORG_ID and ORG_TYPE_CD='SHOP') and ORG_TYPE_CD='SHOP');
delete FROM glr_org where not exists (select 1 from glr_loc l where l.ORG_ID=glr_org.ORG_ID and ORG_TYPE_CD='SHOP') and ORG_TYPE_CD='SHOP';

drop view glr_allpoints;
create view glr_allpoints as
select `t`.`TU` AS `TU`,`t`.`FULL_NM` AS `FULL_NM`,`p`.`TRNSP_PNT_ID` AS `TRNSP_PNT_ID`,`p`.`LOC_PLAN_DTTM` AS `LOC_PLAN_DTTM`,`s`.`TRNSP_PNT_STS_TYPE_NM` AS `TRNSP_PNT_STS_TYPE_NM`,`p`.`STS_DTTM` AS `STS_DTTM`,`ls`.`LOC_NM` AS `LOC_SRC_NM`,`lt`.`LOC_NM` AS `LOC_TGT_NM`,`lt`.`ADDR` AS `ADDR`,`lt`.`LAT` AS `LAT`,`lt`.`LON` AS `LON`,`o`.`ORG_NM` AS `ORG_NM`,`o`.`ORG_ID` AS `ORG_ID`,`ot`.`ORG_TYPE_NM` AS `ORG_TYPE_NM`,`lt`.`LOC_TYPE_CODE` AS `LOC_TGT_TYPE_CD`,`t`.`TRNSP_ID` AS `TRNSP_ID`, lt.ORG_ID as ORG_TGT_ID from ((((((`ikozyr9w_dh`.`glr_trnsp_pnt` `p` join `ikozyr9w_dh`.`glr_trnsp` `t`) left join `ikozyr9w_dh`.`glr_loc` `ls` on((`ls`.`LOC_ID` = `p`.`LOC_SRC_ID`))) join `ikozyr9w_dh`.`glr_loc` `lt`) join `ikozyr9w_dh`.`glr_trnsp_pnt_sts_type` `s`) join `ikozyr9w_dh`.`glr_org` `o`) join `ikozyr9w_dh`.`glr_org_type` `ot` on((`o`.`ORG_TYPE_CD` = `ot`.`ORG_TYPE_CD`))) where ((`p`.`TRNSP_ID` = `t`.`TRNSP_ID`) and (`lt`.`LOC_ID` = `p`.`LOC_TGT_ID`) and (`s`.`TRNSP_PNT_STS_TYPE_CD` = `p`.`TRNSP_PNT_STS_TYPE_CD`) and (`o`.`ORG_ID` = `t`.`ORG_ID`));

update glr_loc set LOC_TYPE_CODE='RC' where LOC_TYPE_CODE='SHOP' and LOC_NM like 'РЦ%';
insert into glr_org_type set ORG_TYPE_CD='RC', ORG_TYPE_NM='РЦ';
update glr_org set ORG_TYPE_CD='RC' where ORG_TYPE_CD='SHOP' and ORG_NM like 'РЦ%';

insert into mst_role_tab set CODE='SHOP',NAME='Магазин', DESCR='Выгружает список своих рейсов', PARENT_CODE='ADMINLERUA',CREATED_BY=19;