/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  igorkozyrev
 * Created: 03.07.2019
 */

alter table glr_loc_type add LOC_TYPE_NM varchar(32) null;
update glr_loc_type set LOC_TYPE_NM='РЦ' where LOC_TYPE_CODE='RC';
update glr_loc_type set LOC_TYPE_NM='Магазин' where LOC_TYPE_CODE='SHOP';
update glr_loc_type set LOC_TYPE_NM='Тестовая точка' where LOC_TYPE_CODE='TEST';
insert into glr_loc_type set LOC_TYPE_NM='РЦ Поставщика',LOC_TYPE_CODE='RC_VENDOR';
insert into glr_org_type set ORG_TYPE_CD='RC_VENDOR', ORG_TYPE_NM='RC_VENDOR';