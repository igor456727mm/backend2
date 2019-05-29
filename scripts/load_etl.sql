/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ik186005
 * Created: May 28, 2019
 */

delimiter //
CREATE PROCEDURE load_tudetail ()
BEGIN
load data local infile '/root/raw/tudetail/Детализация за апрель.csv' into table etl_raw_tu_detail FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY'\"' IGNORE 3 LINES;
END//