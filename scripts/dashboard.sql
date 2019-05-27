/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: Apr 20, 2019
 */

alter table glr_org_type add dashboard_id int null;
update glr_org_type set dashboard_id=68 where ORG_TYPE_CD='TRANSPORT_COMPANY';
insert into glr_org_type set org_type_cd='HEAD', org_type_nm='Головная организация';
update glr_org_type set dashboard_id=69 where ORG_TYPE_CD='HEAD';
insert into glr_org set ORG_ID=1, ORG_TYPE_CD='HEAD', ORG_STS_TYPE_CD='ACTIVE', ORG_NM='ООО "Леруа Мерлен Восток"';
alter table mst_user_tab modify ORG_ID int default 1 null;
update mst_user_tab set ORG_ID=1 where org_id is null;
update glr_org_type set dashboard_id=101 where ORG_TYPE_CD='RC';
update glr_org_type set dashboard_id=100 where ORG_TYPE_CD='SHOP';
