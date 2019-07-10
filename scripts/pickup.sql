/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  igorkozyrev
 * Created: 09.07.2019
 */

CREATE TABLE IF NOT EXISTS `ikozyr9w_dh`.`glr_trnsp_type` (
  `TRNSP_TYPE_CD` VARCHAR(32) NOT NULL,
  `TRNSP_TYPE_NM` VARCHAR(255) NULL,
  PRIMARY KEY (`TRNSP_TYPE_CD`))
ENGINE = InnoDB;

insert into glr_trnsp_type set TRNSP_TYPE_CD='PICKUP', TRNSP_TYPE_NM='Пикап';
alter table glr_trnsp add TRNSP_TYPE_CD varchar(32) null;
insert into glr_mark_type set MARK_TYPE_CD='VENDOR_MARKS_TU', MARK_TYPE_NM='Оценки Поставщика';