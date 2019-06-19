/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  igorkozyrev
 * Created: 19.06.2019
 */

insert into 
 mst_role_tab 
set 
 CODE='ADMIN_LIGHT',
 NAME='Администратор только для чтения',
 DESCR='Видит все как администратор, но понменять ничего не может',
 PARENT_CODE='ADMINLERUA',
 CREATED_BY=19;
