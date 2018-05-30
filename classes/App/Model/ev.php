<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Ev extends \PHPixie\ORM\Model {

    public $table = 'krn_ev';
    public $id_field = 'ev_id';
    protected $belongs_to = array(
        'strtgy' => array(
            'model' => 'strtgy',
            'key' => 'strtgy_cd'
        ),
        'pair' => array(
            'model' => 'pair',
            'key' => 'pair_cd'
        ),
        'exch' => array(
            'model' => 'exch',
            'key' => 'stck_exch_id'
        )
    );
    protected $has_many = array(
        'userstatuses' => array(
            'model' => 'evuserstatus',
            'key' => 'ev_id'
        ),
        'orders' => array(
            'model' => 'order',
            'key' => 'ev_id'
        )
    );

    public function getopen($exch_id, $user) {
        $user_id = $user->id();
        $qry = "select e.ev_id, e.ev_dttm, e.pair_cd, e.strtgy_cd, s.user_id, s.ev_sts_cd, s.ev_user_sts_id from krn_ev e, krn_ev_user_sts s where e.ev_id=s.ev_id and s.ev_sts_cd='OPEN' and s.user_id=$user_id and e.stck_exch_id=$exch_id";
        $res = $this->conn->execute($qry);
        return $res;
    }

    public function getinprocesscnt($user, $pair_cd, $strtgy_cd = null) {
        if ($user) {
            $user_id = $user->id();
            $qry = "select count(*) as cnt from krn_ev e, krn_ev_user_sts s where e.ev_id=s.ev_id and s.ev_sts_cd in ('IN_PROCESS','IN_RESELL') and s.user_id=$user_id and e.pair_cd='$pair_cd';";
        } else {
            $qry = "select count(*) as cnt from krn_ev e where e.ev_sts_cd='IN_PROCESS' and e.pair_cd='$pair_cd' and e.strtgy_cd='$strtgy_cd';";
        }

        $res = $this->conn->execute($qry);
        foreach ($res as $rec) {
            $ret = $rec->cnt;
        }
        return $ret;
    }

    public function last_ev_success($ev_type_cd, $pair_cd) {

        $qry = "select max(ev_dttm) as max_dttm from krn_ev e where pair_cd='$pair_cd' and ev_sts_cd='SUCCESS';";
        $res = $this->conn->execute($qry);
        $max_dttm = '';
        foreach ($res as $rec) {
            $max_dttm = $rec->max_dttm;
        }
        $qry = "select ev_type_cd from krn_ev e where pair_cd='$pair_cd' and ev_dttm='$max_dttm';";
        $res = $this->conn->execute($qry);
        $ev_t = '';
        foreach ($res as $rec) {
            $ev_t = $rec->ev_type_cd;
        }

        return ($ev_t == $ev_type_cd);
    }
   
            

    public function get_last_buy_event_id($user) {
        $user_id = $user->id();
        $pair_cd = $this->pair_cd;
        $strtgy_cd = $this->strtgy_cd;
        $ev_type_cd = $this->ev_type_cd;
        $id = $this->id();
        $qry = "select max(s.ev_id) as ev_id from krn_ev e, krn_ev_user_sts s where e.ev_id=s.ev_id and e.strtgy_cd='$strtgy_cd' and pair_cd='$pair_cd' and ev_type_cd='STRTGY_BUY'"
                . " and s.user_id=$user_id and e.ev_id<$id;";
        $res = $this->conn->execute($qry);
        $ret = null;
        foreach ($res as $rec) {
            $ret = $rec->ev_id;
        }
        return $ret;
    }

    public function get_last_buy_pair_event_id($user) {
        $user_id = $user->id();
        $pair_cd = $this->pair_cd;
        $strtgy_cd = $this->strtgy_cd;
        $ev_type_cd = $this->ev_type_cd;
        $id = $this->id();
        $qry = "select max(s.ev_id) as ev_id from krn_ev e, krn_ev_user_sts s where e.ev_id=s.ev_id and e.strtgy_cd='$strtgy_cd' and pair_cd='$pair_cd' and ev_type_cd='STRTGY_BUY_PAIR'"
                . " and s.user_id=$user_id and e.ev_id<$id and s.ev_sts_cd='SUCCESS';";
        $res = $this->conn->execute($qry);
        $ret = null;
        foreach ($res as $rec) {
            $ret = $rec->ev_id;
        }
        return $ret;
    }

    public function get_buy_cost($user) {
        $id = $this->id();
        $user_id = $user->id();

        if ($this->ev_type_cd == 'STRTGY_SELL') {
            $id = $this->get_last_buy_event_id($user);
        } else if ($this->ev_type_cd == 'STRTGY_BUY_PAIR') {
            $id = $this->id();
        }

        $qry = "select sum(trade_cost) as cost from krn_trade t, krn_order o where o.ev_id=$id and t.order_id=o.order_id and o.order_type_cd='buy' and t.trade_type_cd='buy' and o.user_id=$user_id;";

        $res = $this->conn->execute($qry);
        $cost = null;
        foreach ($res as $rec) {
            $cost = $rec->cost;
        }
        return $cost;
    }

    public function get_buy_vol($user) {
        $id = $this->id();
        $user_id = $user->id();

        if ($this->ev_type_cd == 'STRTGY_SELL') {
            $id = $this->get_last_buy_event_id($user);
        } else if ($this->ev_type_cd == 'STRTGY_BUY_PAIR') {
            $id = $this->id();
        }

        $qry = "select sum(trade_vol) as cost from krn_trade t, krn_order o where o.ev_id=$id and t.order_id=o.order_id and o.order_type_cd='buy' and t.trade_type_cd='buy' and o.user_id=$user_id;";

        $res = $this->conn->execute($qry);
        $cost = null;
        foreach ($res as $rec) {
            $cost = $rec->cost;
        }
        return $cost;
    }

    public function get_sell_vol($user) {
        $id = $this->id();
        $user_id = $user->id();

        if ($this->ev_type_cd == 'STRTGY_SELL') {
            $id = $this->get_last_buy_event_id($user);
        } else if ($this->ev_type_cd == 'STRTGY_BUY_PAIR') {
            $id = $this->id();
        }

        $qry = "select sum(trade_vol) as cost from krn_trade t, krn_order o where o.ev_id=$id and t.order_id=o.order_id and o.order_type_cd='sell' and t.trade_type_cd='sell' and o.user_id=$user_id;";

        $res = $this->conn->execute($qry);
        $cost = null;
        foreach ($res as $rec) {
            $cost = $rec->cost;
        }
        return $cost;
    }
    

    public function get_buy_fee($user) {
        $id = $this->id();
        $user_id = $user->id();

        if ($this->ev_type_cd == 'STRTGY_SELL') {
            $id = $this->get_last_buy_event_id($user);
        } else if ($this->ev_type_cd == 'STRTGY_BUY_PAIR') {
            $id = $this->id();
        }

        $qry = "select sum(trade_fee) as fee from krn_trade t, krn_order o where o.ev_id=$id and t.order_id=o.order_id and o.order_type_cd='buy' and t.trade_type_cd='buy' and o.user_id=$user_id;";

        $res = $this->conn->execute($qry);
        $fee = null;
        foreach ($res as $rec) {
            $fee = $rec->fee;
        }
        return $fee;
    }

    public function setev($exch_id, $user = null) {


        $ev_dttm = date('Y/m/d H:i:s');

        $qry = "insert into krn_ev (ev_type_cd, strtgy_cd, pair_cd,  ev_dttm, ev_sts_cd, ev_sts_changed)
                SELECT ev_type_cd, strtgy_cd, pair_cd, cond_val_dttm,'OPEN','$ev_dttm'
                from ( select v.pair_cd, c.strtgy_cd, v.cond_val_dttm, c.ev_type_cd,s.cond_trg_type_cd, count(*) 
                FROM krn_cond_val v, krn_cond c, krn_strtgy s 
                where c.strtgy_cd=s.strtgy_cd and c.cond_id=v.cond_id 
                and cond_val>=cond_val_min and cond_val<=cond_val_max 
                group by v.pair_cd, c.strtgy_cd, v.cond_val_dttm,c.ev_type_cd,s.cond_trg_type_cd
                having count(*)= 
                case 
                  when s.cond_trg_type_cd='ALL' then (select count(*) from krn_cond c1 where c1.strtgy_cd=c.strtgy_cd) 
                  when s.cond_trg_type_cd='ONE' then 1 END ) a 
                  where not exists 
                  (select 1 from krn_ev e where e.`ev_type_cd`=a.`ev_type_cd` 
                   and e.`pair_cd`=a.`pair_cd` and e.`ev_dttm`=a.cond_val_dttm) 
                   and pair_cd in (select pair_cd from krn_pair where pair_sts_type_cd='ACTIVE')";
        //$res = $this->conn->execute($qry);


        $cond_vals = $this->pixie->orm->get('condval')->
                where('ev_id', 'is', $this->pixie->db->expr('NULL'))->
                where('and', array('cond_val_dttm', '>', $this->pixie->db->expr('now() - interval 300 second')))->
                find_all();
        $strtgy_cond_cnt = [];
        $i = 0;

        $worked = $this->pixie->orm->get('strtgy')->worked($exch_id);

        foreach ($worked as $w_ev) {

            $ev_type_cd = $w_ev->ev_type_cd;
            $strtgy = $this->pixie->orm->get('strtgy')->where('strtgy_cd', $w_ev->strtgy_cd)->find();
            $cond_val_dttm = $w_ev->cond_val_dttm;
            $pair_cd = $w_ev->pair_cd;
            /*
              if (!isset($strtgy_cond_cnt[$strtgy->id()][$ev_type_cd][$cond_val->pair_cd])) {
              if (!isset($strtgy_cond_cnt[$strtgy->id()][$ev_type_cd])) {
              if (!isset($strtgy_cond_cnt[$strtgy->id()])) {
              $strtgy_cond_cnt[$strtgy->id()] = [];
              }
              $strtgy_cond_cnt[$strtgy->id()][$ev_type_cd] = [];
              }
              $strtgy_cond_cnt[$strtgy->id()][$ev_type_cd][$cond_val->pair_cd] = 0;
              }

             */

            //$strtgy_worked=$strtgy->worked($cond_val->pair_cd,$ev_type_cd);
            //Проверяем сработало ли условие по стратегии и паре
            // if ($strtgy_worked) {
            /*
              if (($cond_val->cond_val >= $cond_val->cond->cond_val_min) and ( $cond_val->cond_val <= $cond_val->cond->cond_val_max)) {

              $strtgy_cond_cnt[$strtgy->id()][$ev_type_cd][$cond_val->pair_cd] = $strtgy_cond_cnt[$strtgy->id()][$ev_type_cd][$cond_val->pair_cd] + 1;
              }
              //Если исполнились все условия для данной стратегии по данному типу событий и еще нет события с этим временем - то создаем событие с эти типом
              if (
              ($strtgy_cond_cnt[$strtgy->id()][$ev_type_cd][$cond_val->pair_cd] > 0) && ($strtgy_cond_cnt[$strtgy->id()][$ev_type_cd][$cond_val->pair_cd] == $strtgy->conds->where('ev_type_cd', $ev_type_cd)->count_all()
              // > 0  //если хотя бы одно условие сработало
              )
              ) {
             */
            //если последнее событие по этой паре - закрытое событие на покупку, тогда создаем событие на продажу, и наоборот
            /*    if (
              ($ev_type_cd == 'STRTGY_BUY_PAIR') ||
              (($ev_type_cd == 'STRTGY_BUY') && ($this->last_ev_success('STRTGY_SELL', $cond_val->pair_cd) ||
              (!$this->pixie->orm->get('ev')->where('pair_cd', $cond_val->pair_cd)->where('and', array('ev_type_cd', 'STRTGY_BUY'))->find()->loaded())
              )) ||
              (($ev_type_cd == 'STRTGY_SELL') && ($this->last_ev_success('STRTGY_BUY', $cond_val->pair_cd)))) { */

            /* Если еще такого события нет - то создаем */
            $ev = $this->pixie->orm->get('ev')->where('ev_dttm', $cond_val_dttm)->
                    where('and', array('pair_cd', $pair_cd))->
                    where('and', array('ev_type_cd', $ev_type_cd))->
                    where('and', array('strtgy_cd', $strtgy->id()))->
                    find();
            if (!$ev->loaded()) {
                $ev = $this->pixie->orm->get('ev');
                $ev->ev_sts_cd = 'OPEN';
                $ev->ev_type_cd = $ev_type_cd;
                $ev->pair_cd = $pair_cd;
                $ev->ev_dttm = $cond_val_dttm;
                $ev->strtgy_cd = $strtgy->id();
                $ev->ev_sts_changed = $ev_dttm;
                $ev->stck_exch_id = $exch_id;
                $ev->save();
            }
            //  $cond_val->ev_id = $ev->id();
            //   $cond_val->save();
            //создаем события для юзеров
            $users = $this->pixie->orm->get('user')->get_usersbyrole('kraken_user');
            $cond_user_id = $w_ev->user_id;



            foreach ($users as $user) {

                $user = $this->pixie->orm->get('user')->where('ID', $user->ID)->find();
                //Если данная стратегия активна для данного юзера и данная биржа подключена
                $user_exch = $user->exch->where('stck_exch_id', $exch_id)->find();
                if ((($strtgy->sts->
                                where('strtgy_sts_end_dttm', 'is', $this->pixie->db->expr('NULL'))->
                                where('and', array('user_id', $user->id()))->
                                find()->loaded()) &&
                        (($cond_user_id == 0) or ( $cond_user_id == $user->id()))) && ($user_exch->loaded()) && ($user_exch->user_stck_exch_sts_type_cd = 'ACTIVE')
                ) {
                    //Если такого события еще нет
                    if (!$user->userevents->where('ev_id', $ev->id())->find()->loaded()) {
                        //обрабатываем событие на покупку
                        if ($ev_type_cd == 'STRTGY_BUY') {
                            //если еще нет успешных событий на покупку или последнее событие по этой паре - закрытое событие на продажу, тогда создаем событие на покупку

                            if (
                                    (!$user->userevents->where('ev_sts_cd', 'in', $this->pixie->db->expr('("SUCCESS","IN_PROCESS","OPEN")'))->event->where('pair_cd', $pair_cd)->
                                            where('and', array('ev_type_cd', 'STRTGY_BUY'))->
                                            find()->loaded())
                                    or ( $user->last_ev_success('STRTGY_SELL', $pair_cd))) {
                                $user_ev = $this->pixie->orm->get('evuserstatus');
                                $user_ev->ev_sts_cd = 'OPEN';
                                $user_ev->ev_id = $ev->id();
                                $user_ev->user_id = $user->id();
                                $user_ev->stck_exch_id = $exch_id;
                                $user_ev->save();
                            }
                        }
                        //обрабатываем событие на продажу
                        if ($ev_type_cd == 'STRTGY_SELL') {
                            //если последнее событие по этой паре - закрытое событие на покупку, тогда создаем событие на продажу
                            if (
                                    ( $user->last_ev_success('STRTGY_BUY', $pair_cd))) {
                                $user_ev = $this->pixie->orm->get('evuserstatus');
                                $user_ev->ev_sts_cd = 'OPEN';
                                $user_ev->ev_id = $ev->id();
                                $user_ev->user_id = $user->id();
                                $user_ev->stck_exch_id = $exch_id;
                                $user_ev->save();
                            }
                        }
                        //обрабатываем событие на создание пары ордеров на покупку - продажу
                        if ($ev_type_cd == 'STRTGY_BUY_PAIR') {
                            $last_buy_pair_event_id = $ev->get_last_buy_pair_event_id($user);
                            //echo $ev->pair_cd.'-'.$last_buy_pair_event_id;
                            //die;
                            $in_proc_ev = $user->userevents->
                                    where('ev_sts_cd', 'in', $this->pixie->db->expr('("IN_PROCESS","OPEN")'))->
                                    event->
                                    where('pair_cd', $ev->pair_cd)->
                                    find();
                            if ($last_buy_pair_event_id) {
                                $sell_order_status = $user->orders->
                                                where('ev_id', $last_buy_pair_event_id)->
                                                where('and', array('order_type_cd', 'sell'))->
                                                orderstatuses->
                                                where('order_sts_type_cd', 'closed')->find();
                            }
                            // if ((!$in_proc_ev->loaded()) && ((!$last_buy_pair_event_id) || ($user->last_ev_success('STRTGY_BUY_PAIR', $pair_cd) && ($sell_order_status->loaded())))) {
                            $user_ev = $this->pixie->orm->get('evuserstatus');
                            $user_ev->ev_sts_cd = 'OPEN';
                            $user_ev->ev_id = $ev->id();
                            $user_ev->user_id = $user->ID;
                            $user_ev->stck_exch_id = $exch_id;
                            $user_ev->save();
                            //  }
                        }
                    }
                }
            }
            //}
        }

        $qry = "insert into krn_ev_user_sts (ev_id, user_id, ev_sts_cd, ev_sts_changed) SELECT ev_id, user_id, 'OPEN','$ev_dttm' "
                . "FROM `krn_ev` e, krn_strtgy_sts s where e.strtgy_cd=s.strtgy_cd and s.strtgy_sts_type_cd='ACTIVE' "
                . "and s.strtgy_sts_end_dttm is null and  e.ev_sts_changed='$ev_dttm'  and ev_sts_cd='OPEN' and not exists (select 1 from krn_ev_user_sts s2 where "
                . "s2.ev_id=e.ev_id and s2.user_id=s.user_id) ;";
        //   $res = $this->conn->execute($qry);
        //  print_r($strtgy_cond_cnt);
        //  die;
        //echo $i;
        //die;
        return true;
    }

    public function close_old($exch_id, $user = null) {
        if ($user) {
            $user_id = $user->id();

            $qry = "update krn_ev e, krn_ev_user_sts us set us.ev_sts_cd='CLOSED'
                    where e.ev_id=us.ev_id and us.ev_sts_cd='OPEN' 
                    and e.ev_dttm<now()-interval 10 minute 
                    and us.user_id=$user_id
                    and e.stck_exch_id=$exch_id
                    and not exists (select 1 from krn_order o where o.ev_id=e.ev_id and o.user_id=us.user_id);";
            $res = $this->conn->execute($qry);
            $qry = "update krn_ev e, krn_ev_user_sts us set us.ev_sts_cd='IN_PROCESS'
                    where e.ev_id=us.ev_id and us.ev_sts_cd='OPEN' 
                    and us.user_id=$user_id
                    and e.stck_exch_id=$exch_id
                    and exists (select 1 from krn_order o where o.ev_id=e.ev_id and o.user_id=us.user_id);";
            $res = $this->conn->execute($qry);
        } else {
            $qry = "update krn_ev set ev_sts_cd='CLOSED' where ev_sts_cd='OPEN' and ev_dttm<now()-interval 10 minute and stck_exch_id=$exch_id;";
            $res = $this->conn->execute($qry);
        }


        return $res;
    }

    public function change_ev_sts($exch_id, $user) {

        $user_id = $user->id();
        $qry = "update krn_ev_user_sts us, krn_order o set us.ev_sts_cd='EXPIRED' where us.ev_id=o.ev_id and o.ev_id is not null and 
                exists (select 1 from krn_order_sts s 
                where s.order_id=o.order_id and s.order_sts_type_cd='expired' and order_sts_end_dttm is null)
                and not exists (select 1 from krn_order o1 
                where o1.ev_id=o.ev_id and o1.user_id=o.user_id and o1.order_type_cd='sell')
                and o.user_id=$user_id and us.ev_sts_cd='IN_PROCESS' and us.stck_exch_id=$exch_id and us.user_id=$user_id";
        $orders = $this->conn->execute($qry);

        $qry = "update krn_ev_user_sts us, krn_ev e, krn_order o set us.ev_sts_cd='SUCCESS' where e.ev_id=us.ev_id and e.ev_type_cd='STRTGY_BUY' and us.ev_id=o.ev_id and o.ev_id is not null and 
                exists (select 1 from krn_order_sts s 
                where s.order_id=o.order_id and s.order_sts_type_cd='closed' and order_sts_end_dttm is null)
                and o.user_id=$user_id and us.ev_sts_cd in ('IN_PROCESS','CLOSED') and o.order_type_cd='buy'  and us.stck_exch_id=$exch_id and us.user_id=$user_id";

        $orders = $this->conn->execute($qry);
        
        $qry = "update krn_ev_user_sts s, `krn_order_small` o set s.ev_sts_cd='SUCCESS' where s.ev_id=o.ev_id and s.user_id=o.user_id and s.stck_exch_id=$exch_id and s.user_id=$user_id";

        $orders = $this->conn->execute($qry);
        /*
          $qry = "update krn_ev_user_sts us, krn_ev e, krn_order o set us.ev_sts_cd='SUCCESS' where e.ev_id=us.ev_id and e.ev_type_cd='STRTGY_BUY' and us.ev_id=o.ev_id and o.ev_id is not null and
          exists (select 1 from krn_order_sts s
          where s.order_id=o.order_id and s.order_sts_type_cd='closed' and order_sts_end_dttm is null)
          and o.user_id=$user_id and us.ev_sts_cd='IN_PROCESS' and o.order_type_cd='buy'";
          $orders = $this->conn->execute($qry);
         */
        /*
          $orders = $this->pixie->orm->get('order')->
          where('user_id', $user->id())->
          where('and', array('order_sts_type_cd', 'expired'))->
          where('and', array('order_type_cd', 'take-profit'))->
          find_all();
         */
        /*    foreach ($orders as $order) {

          $order = $this->pixie->orm->get('order')->where('order_id', $order->order_id)->find();

          $us = $order->event->userstatuses->where('user_id', $user->id())->
          where('and', array('ev_sts_cd', 'IN_PROCESS'))->find();
          if ($us->loaded()) {
          $us->ev_sts_cd = 'EXPIRED';
          //$asset = $order->event->pair->asset_from_cd;
          //  $bal = $user->balances->where('asset_cd', $asset)->find();
          //  $bal->bal_val = $bal->bal_val + $order->order_price_init * $order->order_volume;
          //  $bal->save();
          $us->save();
          }
          }
         */
        /*
          $qry = "update krn_ev_user_sts us, krn_order o set us.ev_sts_cd='CANCELED' where us.ev_id=o.ev_id and o.ev_id is not null and
          exists (select 1 from krn_order_sts s
          where s.order_id=o.order_id and s.order_sts_type_cd='canceled' and order_sts_end_dttm is null)
          and o.user_id=$user_id and us.ev_sts_cd='IN_PROCESS'  and us.stck_exch_id=$exch_id";
          $orders = $this->conn->execute($qry);
         */

        /*
          $orders = $this->pixie->orm->get('order')->
          where('user_id', $user->id())->
          where('and', array('order_sts_type_cd', 'expired'))->
          where('and', array('order_type_cd', 'take-profit'))->
          find_all();
         */
        /*
          foreach ($orders as $order) {

          $order = $this->pixie->orm->get('order')->where('order_id', $order->order_id)->find();

          $us = $order->event->userstatuses->where('user_id', $user->id())->
          where('and', array('ev_sts_cd', 'IN_PROCESS'))->find();
          if ($us->loaded()) {
          $us->ev_sts_cd = 'CANCELED';
          // $asset = $order->event->pair->asset_from_cd;
          // $bal = $user->balances->where('asset_cd', $asset)->find();
          // $bal->bal_val = $bal->bal_val + $order->order_price_init * $order->order_volume;
          // $bal->save();
          $us->save();
          }
          }
         */ /*
          $qry = "select * from krn_order o where order_type_cd='sell' and exists (select 1 from krn_order_sts s where s.order_id=o.order_id and s.order_sts_type_cd='canceled' and order_sts_end_dttm is null); ";
          $orders= $this->conn->execute($qry);
          foreach ($orders as $order) {

          $order=$this->pixie->orm->get('order')->where('order_id', $order->order_id)->find();

          $us = $order->event->userstatuses->where('user_id', $user->id())->
          where('and', array('ev_sts_cd', 'IN_PROCESS'))->find();
          if ($us->loaded()) {
          $us->ev_sts_cd = 'CANCELED';
          $us->save();
          }
          }
         */
        /*
          $qry = "update krn_ev_user_sts set ev_sts_cd='EXPIRED' "
          . "where ev_id in (select ev_id from krn_order o, krn_order_sts s "
          . "where s.order_id=o.order_id and s.order_sts_end_dttm is null and order_sts_type_cd='expired')";
          $res = $this->conn->execute($qry);

          $qry = "update krn_ev_user_sts set ev_sts_cd='CANCELED' "
          . "where ev_id in (select ev_id from krn_order o, krn_order_sts s "
          . "where s.order_id=o.order_id and s.order_sts_end_dttm is null and order_sts_type_cd='canceled')";
          $res = $this->conn->execute($qry); */

        //  $qry = "update krn_ev set ev_sts_cd='SUCCESS' where ev_id in (select ev_id from krn_order o, krn_order_sts s where s.order_id=o.order_id and s.order_sts_end_dttm is null and order_sts_type_cd='closed')";
        //  $res = $this->conn->execute($qry);
        return true;
    }

    public function get_price() {

        $ev_dttm = $this->ev_dttm;
        $pair_cd = $this->pair_cd;
        //  $strtgy_cd=$this->strtgy_cd;
        $qry = "select ohlc_close from krn_pair_ohlc b,"
                . "(select max(ohlc_dttm) ohlc_dttm from krn_pair_ohlc where ohlc_dttm<='$ev_dttm' and pair_cd='$pair_cd') a"
                . " where b.ohlc_dttm=a.ohlc_dttm and pair_cd='$pair_cd'";
        // echo $qry; die;
        $res = $this->conn->execute($qry);
        foreach ($res as $rec) {
            $ret = $rec->ohlc_close;
        }
        return $ret;
    }

    public function get_latest_price() {

        $ev_dttm = $this->ev_dttm;
        $pair_cd = $this->pair_cd;
        //  $strtgy_cd=$this->strtgy_cd;
        $qry = "select ohlc_close from krn_pair_ohlc_latest_view where pair_cd='$pair_cd'";
        // echo $qry; die;
        $res = $this->conn->execute($qry);
        foreach ($res as $rec) {
            $ret = $rec->ohlc_close;
        }
        return $ret;
    }

    public function get_avg_buy_price($user) {
        $id = $this->id();
        $user_id = $user->id();

        $ev_dttm = $this->ev_dttm;
        $pair_cd = $this->pair_cd;
        //  $strtgy_cd=$this->strtgy_cd;
        $qry = "select sum(trade_vol*trade_price)/sum(trade_vol) as price from krn_trade t, krn_order o where o.ev_id=$id and t.order_id=o.order_id and o.order_type_cd='buy' and t.trade_type_cd='buy' and o.user_id=$user_id;";
        // echo $qry; die;
        $res = $this->conn->execute($qry);
        foreach ($res as $rec) {
            $ret = $rec->price;
        }
        return $ret;
    }

    public function events_to_close($exch_id, $user = null) {
        /*
          if (!$user) {
          $qry = "SELECT
          ev_id,
          e.pair_cd,
          e.ev_type_cd,
          ev_dttm,
          p.price,
          p.vol,
          e.ev_dttm + INTERVAL ots.period hour as ev_finish,
          (select min(ohlc_dttm) from krn_pair_ohlc o where o.pair_cd=p.pair and ohlc_vwap>=p.price and ohlc_dttm>e.ev_dttm ) as ohlc_dttm,
          (select ohlc_vwap from krn_pair_ohlc o2 where o2.ohlc_dttm=
          (select min(ohlc_dttm) from krn_pair_ohlc o where o.pair_cd=p.pair and ohlc_vwap>=p.price and ohlc_dttm>e.ev_dttm) and o2.pair_cd=p.pair) as ohlc_vwap,
          (select p1.price from test_pair p1 where p1.ev=p.ev and p1.type='buy') as cost,
          (select ohlc_vwap from krn_pair_ohlc o2 where o2.ohlc_dttm=
          (select min(ohlc_dttm) from krn_pair_ohlc o where o.pair_cd=p.pair and ohlc_vwap>=p.price and ohlc_dttm>e.ev_dttm) and o2.pair_cd=p.pair)-
          (select p1.price from test_pair p1 where p1.ev=p.ev and p1.type='buy') as profit
          FROM
          test_pair p,
          krn_ev e,
          krn_order_type_strtgy ots
          where
          ots.strtgy_cd=e.strtgy_cd AND
          e.ev_id=p.ev and
          p.type='sell' and
          ev_sts_cd='IN_PROCESS'
          HAVING
          ohlc_dttm is not null";
          } else {
          $user_id = $user->id();
          $qry = "SELECT
          e.ev_id,
          e.pair_cd,
          e.ev_type_cd,
          e.ev_dttm,
          o.order_price as price,
          o.order_volume as vol,
          now() as ev_finish,
          o.order_price as ohlc_vwap,
          o.order_price*o.order_volume -
          (select sum(o1.order_price*o1.order_volume)
          from
          krn_order o1
          where
          o1.ev_id=o.ev_id
          and order_type_cd in ('take-profit','buy')
          and o1.user_id=o.user_id) as profit
          FROM
          krn_ev e,
          krn_order o,
          krn_order_sts s,
          krn_ev_user_sts us
          where
          o.ev_id=e.ev_id
          and o.order_id=s.order_id
          and s.order_sts_type_cd in ('closed','canceled')
          and s.order_sts_end_dttm is null
          and o.order_type_cd='sell'
          and o.user_id=$user_id
          and us.ev_id=e.ev_id
          and us.user_id=$user_id
          and us.ev_sts_cd='IN_PROCESS';";
          }
         */
        if (!$user) {
            $qry = "SELECT
                        ev_id,
                        e.pair_cd,
                        e.ev_type_cd,
                        ev_dttm,
                        p.price,
                        p.vol,
                        e.ev_dttm + INTERVAL ots.period hour as ev_finish,
                        (select min(ohlc_dttm) from krn_pair_ohlc o where o.pair_cd=p.pair and ohlc_close>=p.price and ohlc_dttm>e.ev_dttm ) as ohlc_dttm,
                        (select ohlc_close from krn_pair_ohlc o2 where o2.ohlc_dttm=
                        (select min(ohlc_dttm) from krn_pair_ohlc o where o.pair_cd=p.pair and ohlc_close>=p.price and ohlc_dttm>e.ev_dttm) and o2.pair_cd=p.pair) as ohlc_close,
                        p.vol*p.price as sell_cost,
                        p.vol*p.price*(0.0026) as sell_fee
                    from    
                        test_pair p, 
                        krn_ev e,
                        krn_order_type_strtgy ots
                    where 
                        ots.strtgy_cd=e.strtgy_cd AND
                        ots.ev_type_cd=ots.ev_type_cd and
                        e.ev_id=p.ev and
                        e.stck_exch_id=$exch_id and
                        p.type='sell' and
                        ev_sts_cd='IN_PROCESS' 
                    HAVING 
                        ohlc_dttm is not null";
        } else {
            $user_id = $user->id();
            $qry = "SELECT	
                     e.ev_id,
                     e.pair_cd,
                     e.ev_type_cd,
                     e.ev_dttm, 
                     o.order_price as price,
                     o.order_volume as vol,
		     now() as ev_finish, 
                     o.order_price as ohlc_close,
                     (select sum(t.trade_cost) 
                      from  
                        krn_trade t 
                      where 
                        t.order_id=o.order_id 
                        and trade_type_cd='sell' 
                     ) as sell_cost,
                     (select sum(t.trade_fee) 
                      from  
                        krn_trade t 
                      where 
                        t.order_id=o.order_id 
                        and trade_type_cd='sell' 
                     ) as sell_fee
                        
                    FROM	
                        krn_ev e, 
                        krn_order o, 
                        krn_order_sts s, 
                        krn_ev_user_sts us
                    where 
                        o.ev_id=e.ev_id 
                        and o.order_id=s.order_id 
                        and s.order_sts_type_cd in ('closed')
                        and s.order_sts_end_dttm is null 
                        and o.order_type_cd='sell' 
                        and o.user_id=$user_id
                        and us.ev_id=e.ev_id 
                        and us.user_id=$user_id
                        and e.stck_exch_id=$exch_id
                        and us.ev_sts_cd in ('IN_PROCESS','ERROR_PAIR_CREATE','IN_RESELL');";
        }

        $res = $this->conn->execute($qry);
        return $res;
    }

    public function get_interval_num($val, $int) {
        return floor($val / $int);
    }

    public function get_first_buy_price($user) {
        $buy = $this->orders->
                where('order_type_cd', 'buy')->
                where('and', array('user_id', $user->id()))->
                orderstatuses->
                where('order_sts_type_cd', 'closed')->
                where('and', array('order_sts_end_dttm', 'is', $this->pixie->db->expr('NULL')))->
                order->order_by('order_dttm')->limit(1)->
                find();
        if ($buy->loaded()) {
            return $buy->order_price;
        } else {
            return 0;
        }
    }

    public function loss($user) {
        $buy_price = $this->get_first_buy_price($user);
        $cur_price = $this->get_latest_price();
        $loss = ($buy_price - $cur_price) * 100 / $buy_price;
        return $loss;
    }

    public function order_bought($loss_step, $user) {
        if (($this->id() == 208201) || ($this->id() == 208068)) {
            return true;
        }
        $loss = $this->loss($user);
        $int_num = $this->get_interval_num($loss, $loss_step);
        $buy_orders = $this->orders->
                where('order_type_cd', 'buy')->
                where('and', array('user_id', $user->id()))->
                orderstatuses->
                where('order_sts_type_cd', 'closed')->
                where('and', array('order_sts_end_dttm', 'is', $this->pixie->db->expr('NULL')))->
                order->
                find_all();
        $buy_price = $this->get_first_buy_price($user);
        foreach ($buy_orders as $order) {
            $loss = ($buy_price - $order->order_price) * 100 / $buy_price;
            if ($this->get_interval_num($loss, $loss_step) == $int_num) {
                return true;
            }
        }
        return false;
    }

    public function buy_cnt($user) {
        return $this->orders->
                        where('order_type_cd', 'buy')->
                        where('and', array('user_id', $user->id()))->
                        orderstatuses->
                        where('order_sts_type_cd', 'closed')->
                        where('and', array('order_sts_end_dttm', 'is', $this->pixie->db->expr('NULL')))->
                        order->
                        count_all();
    }

    public function int_num($user, $loss_step) {
        $loss = $this->loss($user);
        $int_num = $this->get_interval_num($loss, $loss_step);
        return $int_num;
    }

}
