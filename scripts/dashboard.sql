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

select 
 a.cnt*100/b.cnt as Процент1,
 c.cnt*100/d.cnt as Процент2,
 e.cnt*100/f.cnt as Процент3,
 g.cnt*100/h.cnt as Процент3,
 a.cnt*25/b.cnt+c.cnt*25/d.cnt+e.cnt*25/f.cnt+g.cnt*25/h.cnt as Рейтинг,
 (select ORG_NM from glr_org o where o.ORG_ID=a.ORG_ID) as ORG_NM
from 
(select count(*) as cnt, ORG_ID from glr_trnsp_pnt p, glr_trnsp t
where p.TRNSP_ID=t.TRNSP_ID and TRNSP_PNT_STS_TYPE_CD in ('DELIVERED','RELEASED') and LOC_PLAN_DTTM<now() group by ORG_ID) a
join 
(select count(*) as cnt,ORG_ID from glr_trnsp_pnt p, glr_trnsp t 
where p.TRNSP_ID=t.TRNSP_ID and LOC_PLAN_DTTM<now() group by ORG_ID) b 
on a.ORG_ID=b.ORG_ID
left join 
(select count(*) as cnt, ORG_ID from glr_trnsp_accept_anl 
where ACCEPTED_STS_TYPE_CD='принят' group by ORG_ID) c 
on a.ORG_ID=c.ORG_ID
left join 
(select count(*) as cnt, ORG_ID from glr_trnsp_accept_anl  
where ACCEPTED_STS_TYPE_CD in ('отклонен','принят') group by ORG_ID) d
on a.ORG_ID=d.ORG_ID
left join 
(select count(*) as cnt, t.ORG_ID from glr_trnsp_pnt p, glr_trnsp t, glr_loc l where l.LOC_ID=p.LOC_TGT_ID and l.LOC_TYPE_CODE='RC' and p.TRNSP_ID=t.TRNSP_ID and TRNSP_PNT_STS_TYPE_CD in ('DELIVERED','RELEASED') and unix_timestamp(LOC_PLAN_DTTM)>=unix_timestamp(p.STS_DTTM) group by t.ORG_ID) e 
on a.ORG_ID=e.ORG_ID
left join 
(select count(*) as cnt, t.ORG_ID from glr_trnsp_pnt p, glr_trnsp t, glr_loc l where l.LOC_ID=p.LOC_TGT_ID and l.LOC_TYPE_CODE='RC' and p.TRNSP_ID=t.TRNSP_ID and TRNSP_PNT_STS_TYPE_CD in ('DELIVERED','RELEASED') group by t.ORG_ID) f
on a.ORG_ID=f.ORG_ID
left join 
(select count(*) as cnt, t.ORG_ID from glr_trnsp_pnt p, glr_trnsp t, glr_loc l where l.LOC_ID=p.LOC_TGT_ID and l.LOC_TYPE_CODE='SHOP' and p.TRNSP_ID=t.TRNSP_ID and TRNSP_PNT_STS_TYPE_CD in ('DELIVERED','RELEASED') and unix_timestamp(LOC_PLAN_DTTM)>=unix_timestamp(p.STS_DTTM) group by t.ORG_ID) g
on a.ORG_ID=g.ORG_ID
left join 
(select count(*) as cnt, t.ORG_ID from glr_trnsp_pnt p, glr_trnsp t, glr_loc l where l.LOC_ID=p.LOC_TGT_ID and l.LOC_TYPE_CODE='SHOP' and p.TRNSP_ID=t.TRNSP_ID and TRNSP_PNT_STS_TYPE_CD in ('DELIVERED','RELEASED') group by t.ORG_ID) h
on a.ORG_ID=h.ORG_ID
order by Рейтинг desc;
