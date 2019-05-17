/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: May 15, 2019
 */

alter table  mst_user_token_tab add ip varchar(32) null;
alter table glr_trnsp add DRIVER_PHONE varchar(32) null;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: Apr 21, 2019
 */


/*
alter table glr_trnsp_pnt add SHOP_MARK int null;
alter table glr_trnsp_pnt add SHOP_COMMENT varchar(4000) null;
alter table glr_trnsp_pnt add CLAIM_TYPE_CD varchar(32) null;
alter table glr_trnsp_pnt add SHOP_MARK_DTTM datetime null default CURRENT_TIMESTAMP;
alter table glr_trnsp_pnt add SHOP_MARK_USER_ID int null;
alter table glr_trnsp_pnt add foreign key (CLAIM_TYPE_CD) references glr_claim_type(CLAIM_TYPE_CD);
alter table glr_trnsp_pnt add foreign key (SHOP_MARK_USER_ID) references mst_user_tab(ID);
*/
alter table glr_trnsp_pnt add REL_STS_DTTM datetime null;
/*
CREATE TABLE glr_trnsp_pnt_mark
(
	TRNSP_PNT_ID         INTEGER NULL,
	TRNSP_PNT_MARK_ID    INTEGER NOT NULL AUTO_INCREMENT,
	CLAIM_TYPE_CD        VARCHAR(32) NULL,
	SHOP_MARK            INTEGER NULL,
	SHOP_COMMENT         VARCHAR(4000) NULL,
	TRNSP_PNT_MARK_FROM  TIMESTAMP NULL,
	TRNSP_PNT_MARK_TO    TIMESTAMP NULL,
	USER_ID              INTEGER NULL,
	PRIMARY KEY (TRNSP_PNT_MARK_ID)
);

ALTER TABLE glr_trnsp_pnt_mark
ADD FOREIGN KEY (TRNSP_PNT_ID) REFERENCES glr_trnsp_pnt (TRNSP_PNT_ID);

ALTER TABLE glr_trnsp_pnt_mark
ADD FOREIGN KEY (CLAIM_TYPE_CD) REFERENCES glr_claim_type (CLAIM_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_mark
ADD FOREIGN KEY (USER_ID) REFERENCES mst_user_tab (ID);
*/


CREATE TABLE glr_claim_type
(
	CLAIM_TYPE_CD        VARCHAR(32) NOT NULL,
	PRNT_CLAIM_TYPE_CD   VARCHAR(32) NULL,
	CLAIM_TYPE_NM        VARCHAR(255) NULL,
	LVL                  INTEGER NULL,
	MARK_TYPE_CD         VARCHAR(32) NULL,
	PRIMARY KEY (CLAIM_TYPE_CD)
);

CREATE TABLE glr_mark_type
(
	MARK_TYPE_CD         VARCHAR(32) NOT NULL,
	PRIMARY KEY (MARK_TYPE_CD)
);

CREATE TABLE glr_trnsp_pnt_claim
(
	TRNSP_PNT_ID         INTEGER NOT NULL,
	CLAIM_TYPE_CD        VARCHAR(32) NULL,
	TRNSP_PNT_CLAIM_ID   INTEGER NOT NULL AUTO_INCREMENT,
	USER_ID              INTEGER NULL,
	CLAIM_DTTM           DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	MARK_TYPE_CD         VARCHAR(32) NULL,
	PRIMARY KEY (TRNSP_PNT_CLAIM_ID)
);

CREATE UNIQUE INDEX XAK1glr_trnsp_pnt_mark ON glr_trnsp_pnt_claim
(
	TRNSP_PNT_ID ASC,
	CLAIM_TYPE_CD ASC,
	MARK_TYPE_CD ASC
);

CREATE TABLE glr_trnsp_pnt_claim_hst
(
	TRNSP_PNT_CLAIM_HST_ID INTEGER NOT NULL AUTO_INCREMENT,
	TRNSP_PNT_ID         INTEGER NULL,
	CLAIM_TYPE_CD        VARCHAR(32) NULL,
	USER_ID              INTEGER NULL,
	TRNSP_PNT_CLAIM_TYPE_FROM DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	TRNSP_PNT_CLAIM_TYPE_TO DATETIME NULL,
	MARK_TYPE_CD         VARCHAR(32) NULL,
	PRIMARY KEY (TRNSP_PNT_CLAIM_HST_ID)
);

CREATE TABLE glr_trnsp_pnt_mark
(
	TRNSP_PNT_ID         INTEGER NOT NULL,
	MARK                 INTEGER NULL,
	MARK_COMMENT         VARCHAR(4000) NULL,
	MARK_DTTM            DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	MARK_TYPE_CD         VARCHAR(32) NULL,
	USER_ID              INTEGER NULL,
	TRNSP_PNT_MARK_ID    INTEGER NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (TRNSP_PNT_MARK_ID)
);

CREATE UNIQUE INDEX XAK1glr_trnsp_pnt_mark ON glr_trnsp_pnt_mark
(
	TRNSP_PNT_ID ASC,
	MARK_TYPE_CD ASC
);

CREATE TABLE glr_trnsp_pnt_mark_hst
(
	TRNSP_PNT_ID         INTEGER NULL,
	TRNSP_PNT_MARK_HST_ID INTEGER NOT NULL AUTO_INCREMENT,
	MARK                 INTEGER NULL,
	MARK_COMMENT         VARCHAR(4000) NULL,
	TRNSP_PNT_MARK_FROM  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	TRNSP_PNT_MARK_TO    TIMESTAMP NULL,
	USER_ID              INTEGER NULL,
	MARK_TYPE_CD         VARCHAR(32) NULL,
	PRIMARY KEY (TRNSP_PNT_MARK_HST_ID)
);

CREATE UNIQUE INDEX XAK1glr_trnsp_pnt_mark ON glr_trnsp_pnt_mark_hst
(
	TRNSP_PNT_ID ASC,
	MARK_TYPE_CD ASC,
	TRNSP_PNT_MARK_FROM ASC
);

ALTER TABLE glr_claim_type
ADD FOREIGN KEY (PRNT_CLAIM_TYPE_CD) REFERENCES glr_claim_type (CLAIM_TYPE_CD);

ALTER TABLE glr_claim_type
ADD FOREIGN KEY (MARK_TYPE_CD) REFERENCES glr_mark_type (MARK_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_claim
ADD FOREIGN KEY (TRNSP_PNT_ID) REFERENCES glr_trnsp_pnt (TRNSP_PNT_ID);

ALTER TABLE glr_trnsp_pnt_claim
ADD FOREIGN KEY (CLAIM_TYPE_CD) REFERENCES glr_claim_type (CLAIM_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_claim
ADD FOREIGN KEY (USER_ID) REFERENCES mst_user_tab (ID);

ALTER TABLE glr_trnsp_pnt_claim
ADD FOREIGN KEY (MARK_TYPE_CD) REFERENCES glr_mark_type (MARK_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_claim_hst
ADD FOREIGN KEY (TRNSP_PNT_ID) REFERENCES glr_trnsp_pnt (TRNSP_PNT_ID);

ALTER TABLE glr_trnsp_pnt_claim_hst
ADD FOREIGN KEY (CLAIM_TYPE_CD) REFERENCES glr_claim_type (CLAIM_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_claim_hst
ADD FOREIGN KEY (USER_ID) REFERENCES mst_user_tab (ID);

ALTER TABLE glr_trnsp_pnt_claim_hst
ADD FOREIGN KEY (MARK_TYPE_CD) REFERENCES glr_mark_type (MARK_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_mark
ADD FOREIGN KEY (TRNSP_PNT_ID) REFERENCES glr_trnsp_pnt (TRNSP_PNT_ID);

ALTER TABLE glr_trnsp_pnt_mark
ADD FOREIGN KEY (MARK_TYPE_CD) REFERENCES glr_mark_type (MARK_TYPE_CD);

ALTER TABLE glr_trnsp_pnt_mark
ADD FOREIGN KEY (USER_ID) REFERENCES mst_user_tab (ID);

ALTER TABLE glr_trnsp_pnt_mark_hst
ADD FOREIGN KEY (TRNSP_PNT_ID) REFERENCES glr_trnsp_pnt (TRNSP_PNT_ID);

ALTER TABLE glr_trnsp_pnt_mark_hst
ADD FOREIGN KEY (USER_ID) REFERENCES mst_user_tab (ID);

ALTER TABLE glr_trnsp_pnt_mark_hst
ADD FOREIGN KEY (MARK_TYPE_CD) REFERENCES glr_mark_type (MARK_TYPE_CD);


insert into glr_mark_type set MARK_TYPE_CD='SHOP_MARKS_TU';
insert into glr_mark_type set MARK_TYPE_CD='RC_MARKS_TU';

insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='LATE', CLAIM_TYPE_NM='1.Грузовик опоздал';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DOCS', CLAIM_TYPE_NM='2.Проблемы с документами';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='PALLET_DAMAGE', CLAIM_TYPE_NM='3.Есть паллеты с повреждениями';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='PALLET_LOST', CLAIM_TYPE_NM='4.Паллет отсутствует / лишний';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD', CLAIM_TYPE_NM='5.Некорректная загрузка';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM', CLAIM_TYPE_NM='6.Проблемы с транспортным средством';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM', CLAIM_TYPE_NM='7.Проблемы с водителем';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='OTHER_0', CLAIM_TYPE_NM='8.Другое';

insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DOCS_TRANSP',PRNT_CLAIM_TYPE_CD='DOCS',LVL=1, CLAIM_TYPE_NM='Нет транспортной накладной';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DOCS_TORGS',PRNT_CLAIM_TYPE_CD='DOCS',LVL=1, CLAIM_TYPE_NM='Нет ТОРГов';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DOCS_ERRORS',PRNT_CLAIM_TYPE_CD='DOCS',LVL=1, CLAIM_TYPE_NM='Ошибки в документах';

insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD_WEIGHT',PRNT_CLAIM_TYPE_CD='BAD_LOAD',LVL=1, CLAIM_TYPE_NM='Тяжелый стоит на легком/хрупком';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD_NOT_FIXED',PRNT_CLAIM_TYPE_CD='BAD_LOAD',LVL=1, CLAIM_TYPE_NM='Незакрепленный навал';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD_NOT_LABELLED',PRNT_CLAIM_TYPE_CD='BAD_LOAD',LVL=1, CLAIM_TYPE_NM='Навал без этикеток';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD_NOT_FIXED_IN_TRACK',PRNT_CLAIM_TYPE_CD='BAD_LOAD',LVL=1, CLAIM_TYPE_NM='Груз не раскреплен внутри грузовика';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD_NOT_ORDERED',PRNT_CLAIM_TYPE_CD='BAD_LOAD',LVL=1, CLAIM_TYPE_NM='Паллеты загружены вперемешку для разных мест выгрузки';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='BAD_LOAD_FALLEN',PRNT_CLAIM_TYPE_CD='BAD_LOAD',LVL=1, CLAIM_TYPE_NM='Завалились паллеты (невозможно выгрузить погрузчиком)';

insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_PLOMB',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Приехал без пломбы';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_TENT',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Поврежден тент';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_TROSS',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Поврежден пломбировочный трос';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_SMOKE',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Чрезвычайно дымный выхлоп';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_FLOOR',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Проблемы с полом';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_TEMP',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Нарушен температурный режим';

insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_ROUGH',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Водитель грубит';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_DRUNK',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Водитель не трезв';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_RAMP',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Долго встает на рампу';
insert into glr_claim_type set MARK_TYPE_CD='SHOP_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_DOC',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Отметил прибытие, но поздно представил документы (более 10 минут)';

delete from glr_claim_type where CLAIM_TYPE_CD='OTHER_0_1';
delete from glr_claim_type where CLAIM_TYPE_CD='OTHER_0';

ALTER TABLE `ikozyr9w_dh`.`glr_claim_type` DROP PRIMARY KEY, ADD PRIMARY KEY (`CLAIM_TYPE_CD`, `MARK_TYPE_CD`) USING BTREE;

insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LATE', CLAIM_TYPE_NM='1.Грузовик опоздал';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM', CLAIM_TYPE_NM='2.Проблемы с грузом поставщика';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM', CLAIM_TYPE_NM='3.Проблемы с транспортным средством';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM', CLAIM_TYPE_NM='4.Проблемы с водителем';

insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_DOCS',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Ошибки в документах';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_ORDER',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Нет заказа/ТОРГов в WMS';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_PALLET_QUALITY',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Некачественный поддон';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_PLTZING_QUALITY',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Некачественная паллетизация';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_PALLET_DAMAGE',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Паллеты с повреждениями';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_PALLET_LABEL',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Не читаются паллетные этикетки';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_FALLEN',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Завалились паллеты (невозможно выгрузить погрузчиком)';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_ALLIEN',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='В грузовике находится сторонний груз';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_LESS',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Недовоз паллет';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_TU',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Не тот номер отгрузки прибыл';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_BIG',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Негабарит невозможно выгрузить погрузчиком';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='LOAD_PROBLEM_WEIGHT',PRNT_CLAIM_TYPE_CD='LOAD_PROBLEM',LVL=1, CLAIM_TYPE_NM='Неправильный вес/коэффициент паллет';

insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_TENT',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Поврежден тент';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_TROSS',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Поврежден пломбировочный трос';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_SMOKE',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Чрезвычайно дымный выхлоп';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_FLOOR',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Проблемы с полом';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='VHL_PROBLEM_TEMP',PRNT_CLAIM_TYPE_CD='VHL_PROBLEM',LVL=1, CLAIM_TYPE_NM='Нарушен температурный режим';

insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_ROUGH',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Водитель грубит';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_DRUNK',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Водитель не трезв';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_RAMP',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Долго встает на рампу';
insert into glr_claim_type set MARK_TYPE_CD='RC_MARKS_TU', CLAIM_TYPE_CD='DRV_PROBLEM_DOC',PRNT_CLAIM_TYPE_CD='DRV_PROBLEM',LVL=1, CLAIM_TYPE_NM='Отметил прибытие, но поздно представил документы (более 10 минут)';

insert into glr_claim_type (CLAIM_TYPE_CD,PRNT_CLAIM_TYPE_CD,CLAIM_TYPE_NM,LVL,MARK_TYPE_CD) 
select concat(CLAIM_TYPE_CD,"_1"),CLAIM_TYPE_CD,CLAIM_TYPE_NM,1,MARK_TYPE_CD 
from  glr_claim_type c where lvl is null and not exists (select 1 from glr_claim_type c1 where c1.PRNT_CLAIM_TYPE_CD=c.CLAIM_TYPE_CD and c1.MARK_TYPE_CD=c.MARK_TYPE_CD);

insert into glr_trnsp_pnt_sts_type set TRNSP_PNT_STS_TYPE_CD='RELEASED',TRNSP_PNT_STS_TYPE_NM='Убыл';
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
`p`.`STS_DTTM` AS `STS_DTTM`,
`ls`.`LOC_NM` AS `LOC_SRC_NM`,
`lt`.`LOC_NM` AS `LOC_TGT_NM`,
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
t.DRIVER_PHONE
from 
 `glr_trnsp_pnt` `p` 
  join `glr_trnsp` `t` on `p`.`TRNSP_ID` = `t`.`TRNSP_ID`
  left join `glr_loc` `ls` on `ls`.`LOC_ID` = `p`.`LOC_SRC_ID`
  join `glr_loc` `lt` on `lt`.`LOC_ID` = `p`.`LOC_TGT_ID`
  join `glr_trnsp_pnt_sts_type` `s` on `s`.`TRNSP_PNT_STS_TYPE_CD` = `p`.`TRNSP_PNT_STS_TYPE_CD`
  join `glr_org` `o` on `o`.`ORG_ID` = `t`.`ORG_ID`
  join `glr_org_type` `ot` on `o`.`ORG_TYPE_CD` = `ot`.`ORG_TYPE_CD`
  left join glr_trnsp_pnt_mark m on m.TRNSP_PNT_ID=p.TRNSP_PNT_ID and MARK_TYPE_CD in ('SHOP_MARKS_TU','RC_MARKS_TU');

