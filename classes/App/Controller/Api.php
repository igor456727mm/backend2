<?php

namespace app\controller;

use DateTime;
use DateTimeZone;
use ReallySimpleJWT\Token;

if (!defined('API'))
    define('API', '1');

class Api extends \App\Page {

    protected $dev_gui;
    protected $device;

    public function before() {

        //die('1');
        // sleep(1);
        header('Content-Type: application/json');
        $this->view = $this->pixie->view('main');
        $this->view->subview = 'apianswer';
        $this->view->message = null;
        if ($this->request->method != 'POST') {
            //die('2');
            $this->view->message = json_encode(array('Error' => 'POST method should be used', 'Result' => '', 'Data' => ''));
            // die('3');
            return;
        }

        $this->token = $this->request->post('token');
        if ($this->token == '') {
            $this->view->message = json_encode(array('Error' => 'Token should be provided', 'Result' => '', 'Data' => ''));
            return;
        }

        $token = $this->pixie->orm->get('usertoken')->
                where('token', $this->token)->
                where('and', array('CREATED_DATE', '>', $this->pixie->db->expr('now() -interval 1 hour')))->
                find();
        if (!$token->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Token is incorrect', 'Result' => '', 'Data' => ''));
            return;
        } else {
            $this->view->user = $token->user;
            $this->user = $token->user;
            $this->token = $token->TOKEN;
            if ($this->user->user_sts_type_cd == 'deleted') {
                $this->view->message = json_encode(array('Error' => 'User is deleted', 'Result' => '', 'Data' => ''));
                return;
            }
            if ($this->user->ACTIVE_FL != 1) {
                $this->view->message = json_encode(array('Error' => 'User is not activated', 'Result' => '', 'Data' => ''));
                return;
            }
        }
    }

    public function action_getdashboards() {
        if ($this->view->message) {
            return;
        }
        $org_id = $this->user->org->id();
        $dashboards = $this->user->org->orgtype->dashboards->find_all();
        //$org=$this->user->org->find();
        // die($dashboard_id);
        $i = 0;
        foreach ($dashboards as $dashboard) {

            if ($this->user->org->ORG_TYPE_CD == 'HEAD') {
                $payload = [
                    'resource' => ["dashboard" => intval($dashboard->METABASE_ID)],
                    'params' => (Object) []
                ];
            } else {
                $payload = [
                    'resource' => ["dashboard" => intval($dashboard->METABASE_ID)],
                    'params' =>
                    //(Object) []
                        ["ид_тк" => intval($org_id)]
                ];
            }
            $secret = '3cf6553218a113836bb700289e6fd3bc7bbe1b5b871801a7f316d2df4f21620f';
            $token = Token::customPayload($payload, $secret);
            $link = "https://analytics.dostavkalm.ru/embed/dashboard/" . $token;
            $res = [];
            $res['link'] = $link;
            $res['name'] = $dashboard->DASHBOARD_NM;
            $rec[$i] = $res;
            $i++;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getdashboards', 'Data' => $rec));
        $this->view->subview = 'apianswer';
    }

    public function action_jwttoken() {
        if ($this->view->message) {
            return;
        }
        $org_id = $this->user->org->id();
        $dashboard_id = $this->user->org->orgtype->dashboard_id;
        //$org=$this->user->org->find();
        // die($dashboard_id);


        if ($this->user->org->ORG_TYPE_CD == 'HEAD') {
            $payload = [
                'resource' => ["dashboard" => intval($dashboard_id)],
                'params' => (Object) []
            ];
        } else {
            $payload = [
                'resource' => ["dashboard" => intval($dashboard_id)],
                'params' =>
                //(Object) []
                    ["ид_тк" => intval($org_id)]
            ];
        }
        $secret = '3cf6553218a113836bb700289e6fd3bc7bbe1b5b871801a7f316d2df4f21620f';
        $token = Token::customPayload($payload, $secret);
        $this->view->message = json_encode(array('Error' => '', 'Result' => 'jwttoken', 'Data' => "https://analytics.dostavkalm.ru/embed/dashboard/" . $token));
        $this->view->subview = 'apianswer';
    }

    public function action_addloc() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'addshop', 'Data' => ''));
            return;
        }

        $loc = $this->check_field('loc_id', 'loc', 'LOC_ID', true, false);
        if (!is_object($loc)) {
            $this->view->message = json_encode(array('Error' => $loc, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $loc_type = $this->check_field('loc_type_code', 'loctype', 'LOC_TYPE_CODE');
        if (!is_object($loc_type)) {
            $this->view->message = json_encode(array('Error' => $loc_type, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $loc_code = $this->check_field('loc_code', 'loc', 'loc_code', false, false);
        if (!is_object($loc_code)) {
            $this->view->message = json_encode(array('Error' => $loc_code, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $lat = $this->check_field('lat', '', '', false, false);
        if (!is_object($lat)) {
            $this->view->message = json_encode(array('Error' => $lat, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $lon = $this->check_field('lon', '', '', false, false);
        if (!is_object($lon)) {
            $this->view->message = json_encode(array('Error' => $lon, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $loc_nm = $this->check_field('loc_nm', '', '', false, false);
        if (!is_object($loc_nm)) {
            $this->view->message = json_encode(array('Error' => $loc_nm, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $addr = $this->check_field('addr', '', '', false, false);
        if (!is_object($addr)) {
            $this->view->message = json_encode(array('Error' => $addr, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $sector1 = $this->check_field('sector1', '', '', false, false, false);
        if (!is_object($sector1)) {
            $this->view->message = json_encode(array('Error' => $sector1, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $sector2 = $this->check_field('sector2', '', '', false, false, false);
        if (!is_object($sector2)) {
            $this->view->message = json_encode(array('Error' => $sector2, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $manager1 = $this->check_field('manager1', '', '', false, false, false);
        if (!is_object($manager1)) {
            $this->view->message = json_encode(array('Error' => $manager1, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        $manager2 = $this->check_field('manager2', '', '', false, false, false);
        if (!is_object($manager2)) {
            $this->view->message = json_encode(array('Error' => $manager2, 'Result' => 'addloc', 'Data' => ''));
            return;
        }

        if ($loc->loaded()) {
            $org = $loc->org;
        } else {
            $org = $this->pixie->orm->get('org');
        }

        $org->ORG_CD = $loc_code->value;
        $org->ORG_NM = $loc_nm->value;
        $org->ORG_TYPE_CD = $loc_type->LOC_TYPE_CODE;
        $org->ORG_STS_TYPE_CD = 'ACTIVE';
        $org->save();

        $sector1_e = $org->contacts->where('APPT_CD', 'sector1')->find();
        $sector1_e->ORG_ID = $org->id();
        $sector1_e->EMAIL = $sector1->value;
        $sector1_e->APPT_CD = 'SECTOR1';
        $sector1_e->save();

        $sector2_e = $org->contacts->where('APPT_CD', 'sector2')->find();
        $sector2_e->ORG_ID = $org->id();
        $sector2_e->EMAIL = $sector2->value;
        $sector2_e->APPT_CD = 'SECTOR2';
        $sector2_e->save();

        $manager1_e = $org->contacts->where('APPT_CD', 'manager1')->find();
        $manager1_e->ORG_ID = $org->id();
        $manager1_e->EMAIL = $manager1->value;
        $manager1_e->APPT_CD = 'MANAGER1';
        $manager1_e->save();

        $manager2_e = $org->contacts->where('APPT_CD', 'manager2')->find();
        $manager2_e->ORG_ID = $org->id();
        $manager2_e->EMAIL = $manager2->value;
        $manager2_e->APPT_CD = 'MANAGER2';
        $manager2_e->save();

        $loc->org_id = $org->id();
        $loc->loc_type_code = $loc_type->LOC_TYPE_CODE;
        $loc->loc_code = $loc_code->value;
        $loc->lat = $lat->value;
        $loc->lon = $lon->value;
        $loc->loc_nm = $loc_nm->value;
        $loc->addr = $addr->value;
        $loc->save();

        $loc->setstatus('ACTIVE');


        $this->view->message = json_encode(array('Error' => '', 'Result' => 'addloc', 'Data' => 'Магазин изменен.'));


        $this->view->subview = 'apianswer';
    }

    public function action_delloc() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'delloc', 'Data' => ''));
            return;
        }

        $loc = $this->check_field('loc_id', 'loc', 'LOC_ID');
        if (!is_object($loc)) {
            $this->view->message = json_encode(array('Error' => $loc, 'Result' => 'delloc', 'Data' => ''));
            return;
        }

        $loc->setstatus('DELETED');

        $loc->save();

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'delloc', 'Data' => 'Магазин изменен.'));

        $this->view->subview = 'apianswer';
    }

    public function action_changeuser() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'addrole', 'Data' => ''));
            return;
        }

        $user = $this->check_field('user_id', 'user', 'ID');
        if (!is_object($user)) {
            $this->view->message = json_encode(array('Error' => $user, 'Result' => 'changeuser', 'Data' => ''));
            return;
        }

        $org = $this->check_field('org_id', 'org', 'ORG_ID');
        if (!is_object($org)) {
            $this->view->message = json_encode(array('Error' => $org, 'Result' => 'changeuser', 'Data' => ''));
            return;
        }

        if (!$user->loaded()) {
            $this->view->message = json_encode(array('Error' => 'User not found', 'Result' => 'changeuser', 'Data' => ''));
        } else {
            $user->add('org', $org);
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'changeuser', 'Data' => 'Пользователь изменен.'));
        }

        $this->view->subview = 'apianswer';
    }

    public function action_getuseractivationlink() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getuseractivationlink', 'Data' => ''));
            return;
        }

        $user = $this->check_field('user_id', 'user', 'ID');
        if (!is_object($user)) {
            $this->view->message = json_encode(array('Error' => $user, 'Result' => 'getuseractivationlink', 'Data' => ''));
            return;
        }
        
         require '../assets/config/env.php';


        if ((!$user->loaded()) || ($user->ACTIVE_FL == 1)) {
            $this->view->message = json_encode(array('Error' => 'User not found', 'Result' => 'getuseractivationlink', 'Data' => ''));
        } else {
            $this->view->message = json_encode(array('Error' => '',
                'Result' => 'getuseractivationlink',
                'Data' => $site_url.'/activate.xhtml?uid=' . $user->ACT_KEY));
        }

        $this->view->subview = 'apianswer';
    }

    public function action_addrole() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'addrole', 'Data' => ''));
            return;
        }

        $role = $this->check_field('role', 'role', 'CODE');
        if (!is_object($role)) {
            $this->view->message = json_encode(array('Error' => $role, 'Result' => 'addrole', 'Data' => ''));
            return;
        }

        $user = $this->check_field('user_id', 'user', 'ID');
        if (!is_object($user)) {
            $this->view->message = json_encode(array('Error' => $user, 'Result' => 'addrole', 'Data' => ''));
            return;
        }

        if (!$user->loaded()) {
            $this->view->message = json_encode(array('Error' => 'User not found', 'Result' => 'addrole', 'Data' => ''));
        } else {
            $user->add('roles', $role);
            if (($role->CODE == 'ADMIN')||($role->CODE == 'ADMIN_LIGHT')) {
                $user->ORG_ID=1;
                $user->save();
            }
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'addrole', 'Data' => 'Роль добавлена.'));
        }

        $this->view->subview = 'apianswer';
    }

    private function check_field($input, $entity, $field, $need_entity = true, $need_exist = true, $need_value = true, $type = false) {
        $val = $this->request->post($input);
        $val = filter_var($val, FILTER_SANITIZE_STRING);
        if ($need_value) {
            if ($val == '') {
                return $input . ' must not be empty';
            }
        }

        if ($type == 'int') {
            if (!is_numeric($val)) {
                return $input . ' must be integer';
            }
        }

        if (!$need_entity) {
            $ret = new \stdClass();
            $ret->value = $val;
            return $ret;
        }

        $ent = $this->pixie->orm->get($entity)->where($field, $val)->find();

        if (!$need_exist) {
            return $ent;
        }

        if (!$ent->loaded()) {
            return $entity . ' not found';
        }
        return $ent;
    }

    public function action_delpoint() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'delpoint', 'Data' => ''));
            return;
        }

        $point = $this->check_field('point_id', 'pnt', 'TRNSP_PNT_ID');
        if (!is_object($point)) {
            $this->view->message = json_encode(array('Error' => $point, 'Result' => 'delpoint', 'Data' => ''));
            return;
        }

        $point->setstatus('DELETED', 0, $this->user->id());

        $this->view->message = json_encode(array('Error' => "", 'Result' => 'delpoint', 'Data' => 'Ok'));
    }

    public function action_changepointdate() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'changepointdate', 'Data' => ''));
            return;
        }

        $point = $this->check_field('pnt_id', 'pnt', 'TRNSP_PNT_ID');
        if (!is_object($point)) {
            $this->view->message = json_encode(array('Error' => $point, 'Result' => 'changepointdate', 'Data' => ''));
            return;
        }

        $dttm = $this->check_field('dttm', '', '', false, false);
        if (!is_object($dttm)) {
            $this->view->message = json_encode(array('Error' => $dttm, 'Result' => 'changepointdate', 'Data' => ''));
            return;
        }

        if ($point->status('RELEASED')) {
            $rel_dttm = $point->REL_STS_DTTM;
            $point->setstatus('DELIVERED', 0, $this->user->id(), null, $dttm->value);
            $point->setstatus('RELEASED', 0, $this->user->id(), null, $rel_dttm);
        } else {
            $point->setstatus('DELIVERED', 0, $this->user->id(), null, $dttm->value);
        }
        $this->view->message = json_encode(array('Error' => "", 'Result' => 'changepointdate', 'Data' => 'Ok'));
    }

    public function action_test() {

        require '../assets/config/env.php';
        $rc = md5(mt_rand(1000000000, 2000000000));
        $res = $this->pixie->email->send('ikozyrev-JAGV@mail-tester.com', array($site_email => "Доставка ЛМ"), "Доставка2 в магазины Леруа Мерлен - запрос на изменение пароля", "Уважаемый пользователь!
Для изменения пароля перейдите по ссылке: 
" .
                $site_url . '/restore.xhtml?rc=' . $rc . "
" . "Нужна помощь?" . "
" . "+7-800-600-82-02| Telegram Доставка в магазины ЛМ: поддержка" . "
" . "© 2019 dostavkalm.ru " . "
" . "Авторские права защищены.");

        $this->view->message = json_encode(array('Error' => "", 'Result' => 'test', 'Data' => $res));
        return;
    }

    public function action_addtransp1() {
        
    }

    public function action_addtransp() {

        if ($this->view->message) {
            return;
        }
        $qry = "SET @@session.wait_timeout=900";
        $this->pixie->orm->get('etlrun')->conn->execute($qry);

        $etlrun = $this->pixie->orm->get('etlrun');
        $etlrun->entity_nm = 'glr_trnsp_pnt';
        $etlrun->user_id = $this->user->id();
        $etlrun->loaded = 0;
        $etlrun->updated = 0;
        $etlrun->etl_run_dttm = date("Y-m-d H:i:s");

        $datetime = new DateTime();
        //    $this->logerror('addtransp', 'Addtransp started at:' . $datetime->format('Y-m-d H:i:s u'));

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'addtransp', 'Data' => ''));
            return;
        }

        $transp = $this->request->post('transp');
        if ($transp == '') {
            $this->view->message = json_encode(array('Error' => 'Transp must not be empty', 'Result' => 'addtransp', 'Data' => ''));
            return;
        }
        $transp = //html_entity_decode($transp);
                html_entity_decode($transp, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $transps = json_decode($transp);

        ///echo $transps;
        //die;


        if (!is_array($transps)) {
            $this->view->message = json_encode(array('Error' => array('Transp format is wrong'), 'Result' => 'addtransp', 'Data' => ''));
            return;
        }
        $i = 0;
        $j = 0;
        $err_array = [];

        $datetime = new DateTime();
        //  $this->logerror('addtransp', 'For loop started:' . $datetime->format('Y-m-d H:i:s u'));

        foreach ($transps as $transp) {

            if (!isset($transp->tu)) {
                $this->view->message = json_encode(array('Error' => 'TU must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'TU не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $tu = $transp->tu;
            $tu = filter_var($tu, FILTER_SANITIZE_STRING);
            if ($tu == '') {
                $this->view->message = json_encode(array('Error' => 'TU must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'TU не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }

            if (!isset($transp->phone)) {
                $this->view->message = json_encode(array('Error' => 'Phone must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Телефон не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $phone = $transp->phone;
            $phone = filter_var($phone, FILTER_SANITIZE_STRING);

            if (!isset($transp->from)) {
                $this->view->message = json_encode(array('Error' => 'From must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'РЦ не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $from = $transp->from;
            $from = filter_var($from, FILTER_SANITIZE_STRING);
            if ($from == '') {
                $this->view->message = json_encode(array('Error' => 'From must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'РЦ не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            if (!isset($transp->to)) {

                $this->view->message = json_encode(array('Error' => 'To must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Магазин не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $to = $transp->to;
            $to = filter_var($to, FILTER_SANITIZE_STRING);
            if ($to == '') {
                $this->view->message = json_encode(array('Error' => 'To must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Магазин не указан';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }


            if (!isset($transp->from_date)) {
                $this->view->message = json_encode(array('Error' => 'From date must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Дата встречи в РЦ не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $from_date = $transp->from_date;
            $from_date = filter_var($from_date, FILTER_SANITIZE_STRING);
            if ($from_date == '') {
                $this->view->message = json_encode(array('Error' => 'From date must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Дата встречи в РЦ не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }


            if (!isset($transp->to_date)) {
                $this->view->message = json_encode(array('Error' => 'To date must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Дата встречи в магазине не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $to_date = $transp->to_date;
            $to_date = filter_var($to_date, FILTER_SANITIZE_STRING);
            if ($to_date == '') {
                $this->view->message = json_encode(array('Error' => 'To date must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Дата встречи в магазине не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }


            if (!isset($transp->fio)) {
                $this->view->message = json_encode(array('Error' => 'FIO must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Фамилия не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $fio = $transp->fio;
            $fio = filter_var($fio, FILTER_SANITIZE_STRING);
            if (($fio == '') || ($fio == 'Не указано')) {
                $this->view->message = json_encode(array('Error' => 'To date must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Фамилия не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }

            if (isset($transp->org_type)) {
                $org_type = $transp->org_type;
            } else {
                $org_type = '';
            }
            $org_type = filter_var($org_type, FILTER_SANITIZE_STRING);
            /*  if (($org_type == '') || ($fio == 'Не указано')) {
              $this->view->message = json_encode(array('Error' => 'To date must not be empty', 'Result' => 'addtransp', 'Data' => ''));
              $transp->reason = 'Фамилия не указана';
              $err_array[$j] = $transp;
              $j = $j + 1;
              continue;
              }
             */

            if (!isset($transp->org)) {
                $this->view->message = json_encode(array('Error' => 'Organization must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Организация не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $org = $transp->org;
            $org = filter_var($org, FILTER_SANITIZE_STRING);
            $org = html_entity_decode($org);
            if (($org == '') || ($org == 'ООО ""')) {
                $this->view->message = json_encode(array('Error' => 'Organization must not be empty', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Организация не указана';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }

            $transp_d = $this->pixie->orm->get('transp')->
                    where('TU', $tu)->pnts->
                    where('and', array('TRNSP_PNT_STS_TYPE_CD', 'IN', $this->pixie->db->expr('("DELIVERED","RELEASED")')))->
                    find();

            if ($transp_d->loaded()) {
                $transp->reason = 'В этом рейсе есть доставленные точки';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }

            $transp_e = $this->pixie->orm->get('transp')->
                    where('TU', $tu)->
                    find();

            $org_e = $this->pixie->orm->get('org')->
                    where('ORG_NM', $org)->
                    find();

            if (!$org_e->loaded()) {
                $org_type_e = $this->pixie->orm->get('orgtype')->
                        where('ORG_TYPE_NM', $org_type)->
                        find();
                if ($org_type_e->loaded()) {
                    $org_e->ORG_TYPE_CD = $org_type_e->ORG_TYPE_CD;
                } else {
                    $org_e->ORG_TYPE_CD = 'TRANSPORT_COMPANY';
                }
            }

            $org_e->ORG_NM = $org;

            $org_e->save();

            $transp_e->TU = $tu;
            $transp_e->DRIVER_PHONE = $phone;
            $transp_e->FULL_NM = $fio;
            $transp_e->ORG_ID = $org_e->id();
            $transp_e->save();

            $to_e = $this->pixie->orm->get('loc')->
                    where('LOC_NM', $to)->
                    where('and', array('LOC_STS_TYPE_CD', '<>', 'DELETED'))->
                    find();
            $datetime = new DateTime();
            //       $this->logerror('addtransp', 'Shop have not found started:' . $datetime->format('Y-m-d H:i:s u'));

            if (!$to_e->loaded()) {

                $transp->reason = 'Магазин не найден';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $from_e = $this->pixie->orm->get('loc')->
                    where('LOC_NM', $from)->
                    where('and', array('LOC_STS_TYPE_CD', '<>', 'DELETED'))->
                    find();

            if (!$from_e->loaded()) {

                $transp->reason = 'РЦ не найден';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }

            $pnt_e = $this->pixie->orm->get('transp')->
                    where('TU', $tu)->pnts->
                    where('LOC_TGT_ID', $to_e->id())->
                    find();




            $pnt_e->TRNSP_ID = $transp_e->id();
            $pnt_e->LOC_SRC_ID = $from_e->id();
            $pnt_e->LOC_TGT_ID = $to_e->id();
            $from_date = date_create_from_format('d.m.Y G:i:s', $from_date . ':00');
            if (!$from_date) {
                $this->view->message = json_encode(array('Error' => 'Date fromat is wrong', 'Result' => 'addtransp', 'Data' => ''));
                $transp->reason = 'Формат даты неверен';
                $err_array[$j] = $transp;
                $j = $j + 1;
                continue;
            }
            $from_date = date_format($from_date, 'Y-m-d H:i:s');
            $to_date = date_format(date_create_from_format('d.m.Y G:i:s', $to_date . ':00'), 'Y-m-d H:i:s');
            $pnt_e->LOC_SRC_PLAN_DTTM = $from_date;
            $pnt_e->LOC_PLAN_DTTM = $to_date;
            $pnt_e->TRNSP_PNT_STS_TYPE_CD = 'CREATED';
            if (!$pnt_e->loaded()) {
                $etlrun->loaded = $etlrun->loaded + 1;
            } else {
                $etlrun->updated = $etlrun->updated + 1;
            }
            $datetime = new DateTime();
            $this->logerror('addtransp', 'Point_e save started:' . $datetime->format('Y-m-d H:i:s u'));
            $pnt_e->save();
            $datetime = new DateTime();
            //  $this->logerror('addtransp', 'Point_e status set started:' . $datetime->format('Y-m-d H:i:s u'));
            $pnt_e->setstatus('CREATED', 0, $this->user->id());

            $pnt_s = $this->pixie->orm->get('transp')->
                    where('TU', $tu)->pnts->
                    where('LOC_TGT_ID', $from_e->id())->
                    find();



            $pnt_s->TRNSP_ID = $transp_e->id();
            $pnt_s->LOC_TGT_ID = $from_e->id();
            $pnt_s->LOC_PLAN_DTTM = $from_date;
            $pnt_s->TRNSP_PNT_STS_TYPE_CD = 'CREATED';
            if (!$pnt_s->loaded()) {
                $etlrun->loaded = $etlrun->loaded + 1;
            } else {
                $etlrun->updated = $etlrun->updated + 1;
            }
            $datetime = new DateTime();
            //    $this->logerror('addtransp', 'Point_s save started:' . $datetime->format('Y-m-d H:i:s u'));
            $pnt_s->save();
            $datetime = new DateTime();
            //    $this->logerror('addtransp', 'Point_s status set started:' . $datetime->format('Y-m-d H:i:s u'));
            $pnt_s->setstatus('CREATED', 0, $this->user->id());
            $i = $i + 1;
            //    $log = $this->pixie->orm->get('log');
            //     $log->sender_cd = 'API';
            //     $log->message = $transp_e->TU;
            //     $log->action = 'addtransp';
            //     $log->user_id = $this->user->id();
            //     $log->level_cd = 'INFO';
//echo    $log->message;     
            //   $log->save();
            $datetime = new DateTime();
            //        $this->logerror('addtransp', 'For loop ended:' . $datetime->format('Y-m-d H:i:s u'));
        }



        $this->view->message = json_encode(array('Error' => $err_array, 'Result' => 'addtransp', 'Data' => $i));
        $etlrun->etl_run_end_dttm = date("Y-m-d H:i:s");

        $etlrun->save();

        $k = 0;

        foreach ($err_array as $err) {
            $pntlog = $this->pixie->orm->get('pntlog');
            $pntlog->tu = $err->tu;
            $pntlog->from_pnt = $err->from;
            $pntlog->to_pnt = $err->to;
            $pntlog->from_date = $err->from_date;
            $pntlog->to_date = $err->to_date;
            $pntlog->fio = $err->fio;
            $pntlog->org = $err->org;
            $pntlog->user_created = $this->user->id();
            $pntlog->reason = $err->reason;
            $pntlog->etl_run_id = $etlrun->id();
            $pntlog->save();
            $k = $k + 1;
            $etlrun->not_loaded = $k;
        }

        $etlrun->save();

        $this->view->subview = 'apianswer';
    }

    public function action_delrole() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'delrole', 'Data' => ''));
            return;
        }

        $role = $this->check_field('role', 'role', 'CODE');
        if (!is_object($role)) {
            $this->view->message = json_encode(array('Error' => $role, 'Result' => 'delrole', 'Data' => ''));
            return;
        }

        $user = $this->check_field('user_id', 'user', 'ID');
        if (!is_object($user)) {
            $this->view->message = json_encode(array('Error' => $user, 'Result' => 'delrole', 'Data' => ''));
            return;
        }

        if (!$user->loaded()) {
            $this->view->message = json_encode(array('Error' => 'User not found', 'Result' => 'delrole', 'Data' => ''));
        } else {
            $user->remove('roles', $role);
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'delrole', 'Data' => 'Роль удалена.'));
        }

        $this->view->subview = 'apianswer';
    }

    public function action_deluser() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'deluser', 'Data' => ''));
            return;
        }

        $user = $this->check_field('user_id', 'user', 'ID');
        if (!is_object($user)) {
            $this->view->message = json_encode(array('Error' => $user, 'Result' => 'addrole', 'Data' => ''));
            return;
        }

        if (!$user->loaded()) {
            $this->view->message = json_encode(array('Error' => 'User not found', 'Result' => 'deluser', 'Data' => ''));
        } else {

            $user->setstatus('deleted');

            $this->view->message = json_encode(array('Error' => '', 'Result' => 'deluser', 'Data' => 'Пользователь удален.'));
        }

        $this->view->subview = 'apianswer';
    }

    public function action_getroles() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getroles', 'Data' => ''));
            return;
        }
        $user = $this->check_field('user_id', 'user', 'ID');
        if (!is_object($user)) {
            $this->view->message = json_encode(array('Error' => $user, 'Result' => 'getroles', 'Data' => ''));
            return;
        }
        /* $user_id = $this->request->post('user_id');
          $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
          if ($user_id == '') {
          $this->view->message = json_encode(array('Error' => 'User_id must not be empty', 'Result' => 'getroles', 'Data' => ''));
          return;
          }

          $user = $this->pixie->orm->get('user')->where('ID', $user_id)->find();
         *
          if (!$user->loaded()) {
          $this->view->message = json_encode(array('Error' => 'User not found', 'Result' => 'getroles', 'Data' => ''));
          return;
          }
         */
        $roles = $user->roles->find_all();

        //echo print_r($roles->as_array(1));
        //die;


        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getroles', 'Data' => $roles->as_array(1)));


        $this->view->subview = 'apianswer';
    }

    public function action_reguser() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        $email = $this->request->post('email');
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        if ($email == '') {
            $this->view->message = json_encode(array('Error' => 'Заполните электронный адрес', 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view->message = json_encode(array('Error' => 'Формат электронного адреса не верен', 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        $role = $this->request->post('role');
        $role = filter_var($role, FILTER_SANITIZE_STRING);
        if ($role == '') {
            $this->view->message = json_encode(array('Error' => 'Role must not be empty', 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        //  if 
        //      (($role != 'ADMIN') && ($role != 'ADMIN_LIGHT') && ($role != 'TRANSPORT_COMPANY') && ($role != 'RC') && ($role != 'VENDOR') && ($role != 'SHOP')) 
      //  {
      //      $this->view->message = json_encode(array('Error' => 'Role is wrong', 'Result' => 'reguser', 'Data' => ''));
      //      return;
      //  }

        $org = $this->check_field('org_id', 'org', 'ORG_ID', true, false, false);
        if (!is_object($org)) {
            $this->view->message = json_encode(array('Error' => $org, 'Result' => 'changeuser', 'Data' => ''));
            return;
        }

        $user = $this->pixie->orm->get('user')->where('EMAIL', $email)->find();
        $password = md5(mt_rand(1000000000, 2000000000));

        $hash = $this->pixie->auth->provider('password')->hash_password($password);

        if ($user->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Такой электронный адрес уже существует', 'Result' => 'reguser', 'Data' => ''));
        } else {

            $user->psw = $hash;
            $user->name = $email;
            $user->email = $email;
            $user->created_by = $this->user->id();
            if ($org->loaded()) {
                $user->org_id = $org->id();
            }

            $user->act_key = md5(mt_rand(1000000000, 2000000000));
            $lang_code = 'ru';
            $user->setstatus('created');

            $role = $this->pixie->orm->get('role')->where('CODE', $role)->find();
            $user->add('roles', $role);
            $user->setstatus('registered');
            require '../assets/config/env.php';

            $res = $this->pixie->email->send($email, array($site_email => "Доставка ЛМ"), "Добро пожаловать на сайт доставки в магазины Леруа Мерлен! Остался еще один шаг", "Вы зарегистрированы на сайте dostavkalm.ru!
Чтобы начать, нажмите на ссылку для подтверждения вашего электронного адреса:
" .
                    $site_url . '/activate.xhtml?uid=' . $user->act_key . "
" . "Нужна помощь?" . "
" . "+7-800-600-82-02" . "
" . "© 2019 dostavkalm.ru " . "
" . "Авторские права защищены.");

            $user->setstatus('emailsent');
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'reguser', 'Data' => 'Пожалуйста дождитесь пиьсма со ссылкой на активацию аккаунта.'));
        }

        $this->view->subview = 'apianswer';
    }

    public function action_emailrepeat() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        $email = $this->request->post('email');
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        if ($email == '') {
            $this->view->message = json_encode(array('Error' => 'Заполните электронный адрес', 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view->message = json_encode(array('Error' => 'Формат электронного адреса не верен', 'Result' => 'reguser', 'Data' => ''));
            return;
        }



        $user = $this->pixie->orm->get('user')->
                where('EMAIL', $email)->
                where('and', array('user_sts_type_cd', 'emailsent'))->
                where('and', array('active_fl', 0))->
                find();

        if (!$user->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Такого электронного адреса не существует', 'Result' => 'reguser', 'Data' => ''));
        } else {
            require '../assets/config/env.php';

            $res = $this->pixie->email->send($email, array($site_email => "Доставка ЛМ"), "Добро пожаловать на сайт доставки в магазины Леруа Мерлен! Остался еще один шаг", "Вы зарегистрированы на сайте dostavkalm.ru!
Чтобы начать, нажмите на ссылку для подтверждения вашего электронного адреса:
" .
                    $site_url . '/activate.xhtml?uid=' . $user->ACT_KEY . "
" . "Нужна помощь?" . "
" . "+7-800-600-82-02" . "
" . "© 2019 dostavkalm.ru " . "
" . "Авторские права защищены.");

            $this->view->message = json_encode(array('Error' => '', 'Result' => 'reguser', 'Data' => 'Пожалуйста дождитесь письма со ссылкой на активацию аккаунта.'));
        }

        $this->view->subview = 'apianswer';
    }

    function haversineGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    function glr_curl($path, $param) {
        //connect to web service
        $url = 'http://localhost' . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    function check_action($action, $path, $param, $ret_param, $is_array = false) {
        $ret = $this->glr_curl($path, $param);
        if (!$ret) {
            $res = 'grt_action_' . $action . ' -1';
        }
        //parse XML response
        $data = json_decode($ret);
        //echo '<pre>'.print_r($data,true).'</pre>'; die();
        if ($is_array) {
            if (!isset($data->Data[0]->$ret_param)) {
                $res = 'grt_action_' . $action . ' 0';
            } else {
                $res = 'grt_action_' . $action . ' 1';
            }
        } else {
            if (!isset($data->Data->$ret_param)) {
                $res = 'grt_action_' . $action . ' 0';
            } else {
                $res = 'grt_action_' . $action . ' 1';
            }
        }
        return $res;
    }

    function action_prometheus() {
        $ip = $_SERVER['HTTP_HOST'];
        $ret = $this->glr_curl("/apiopen/login", 'username=iko.zyrev@gmail.com&password=seliger9&ip=' . $ip);
        $data = json_decode($ret);
        $this->view->message = $this->check_action("login", "/apiopen/login", 'username=iko.zyrev@gmail.com&password=seliger9&ip=' . $ip, 'token');
        $this->view->message = $this->view->message . "
" . $this->check_action("getallpoints", "/api/getallpoints", 'token=' . $data->Data->token . '&date_from=2019-03-22 01:00&date_to=2019-03-22 01:15', 'TU', true);
        $this->view->subview = 'apianswer';
    }

    function get_timezone($latitude, $longitude, $username) {

        //error checking
        if (!is_numeric($latitude)) {
            return json_encode(array('Error' => 'A numeric latitude is required.', 'Result' => 'get_timezone', 'Data' => ''));
        }
        if (!is_numeric($longitude)) {
            return json_encode(array('Error' => 'A numeric longitude is required.', 'Result' => 'get_timezone', 'Data' => ''));
        }
        if (!$username) {
            return json_encode(array('Error' => 'A GeoNames user account is required. You can get one here: http://www.geonames.org/login', 'Result' => 'get_timezone', 'Data' => ''));
        }

        //connect to web service
        $url = 'http://api.geonames.org/timezoneJSON?lat=' . trim($latitude) . '&lng=' . trim($longitude) . '&style=full&username=' . urlencode($username);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
        $ret = curl_exec($ch);
        curl_close($ch);
        if (!$ret) {
            return json_encode(array('Error' => 'The GeoNames service did not return any data', 'Result' => 'get_timezone', 'Data' => ''));
        }

        //parse XML response
        $data = json_decode($ret);
        //echo '<pre>'.print_r($data,true).'</pre>'; die();
        if (!isset($data->timezoneId)) {
            return json_encode(array('Error' => 'The GeoNames service returned Error:' . $ret, 'Result' => 'get_timezone', 'Data' => ''));
        }
        $timezone = trim(strip_tags($data->timezoneId));
        if ($timezone) {
            return $timezone;
        } else {
            return json_encode(array('Error' => 'The GeoNames service did not return any data', 'Result' => 'get_timezone', 'Data' => ''));
        }
    }

    function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
        $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code) : DateTimeZone::listIdentifiers();

        if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

            $time_zone = '';
            $tz_distance = 0;

            //only one identifier?
            if (count($timezone_ids) == 1) {
                $time_zone = $timezone_ids[0];
            } else {

                foreach ($timezone_ids as $timezone_id) {
                    $timezone = new DateTimeZone($timezone_id);
                    $location = $timezone->getLocation();
                    $tz_lat = $location['latitude'];
                    $tz_long = $location['longitude'];

                    $theta = $cur_long - $tz_long;
                    $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat))) + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                    $distance = acos($distance);
                    $distance = abs(rad2deg($distance));
                    // echo '<br />'.$timezone_id.' '.$distance; 

                    if (!$time_zone || $tz_distance > $distance) {
                        $time_zone = $timezone_id;
                        $tz_distance = $distance;
                        $ret = $timezone;
                    }
                }
            }
            return $ret;
        }
        return 'unknown';
    }

    public function action_driverrelease() {

        if ($this->view->message) {
            return;
        }
        $role_admin = $this->user->roles->where('CODE', 'ADMIN')->find();
        $role_shop = $this->user->roles->where('CODE', 'SHOP')->find();
        $role_rc = $this->user->roles->where('CODE', 'RC')->find();

        if (!($role_admin->loaded() || $role_shop->loaded() || $role_rc->loaded())) {
            $this->view->message = json_encode(array('Error' => 'You dont have access to this method.', 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }

        date_default_timezone_set('Europe/Moscow');

        /*   $pnt_id = $this->request->post('pnt_id');
          $pnt_id = filter_var($pnt_id, FILTER_SANITIZE_STRING);
          if (!is_numeric($pnt_id)) {
          $this->view->message = json_encode(array('Error' => 'Point id is wrong.', 'Result' => 'driverrelease', 'Data' => ''));
          return;
          } */
        $pnt = $this->check_field('pnt_id', 'pnt', 'trnsp_pnt_id');
        if (!is_object($pnt)) {
            $this->view->message = json_encode(array('Error' => $pnt, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }
        if ($pnt->loctgt->org->orgtype->id() == 'SHOP') {
            $mark_type_cd = 'SHOP_MARKS_TU';
        } else
        if ($pnt->loctgt->org->orgtype->id() == 'RC') {
            $mark_type_cd = 'RC_MARKS_TU';
        } else {
            $this->view->message = json_encode(array('Error' => 'Для данного типа точки нельзя устанавливать оценки', 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }
        // die($mark_type_cd);
        if ($role_admin->loaded()) {
            $pnt = $this->user->org->loc->pnts->
                    where("TRNSP_PNT_ID", $pnt->id())->
                    where("and", array("TRNSP_PNT_STS_TYPE_CD", 'IN', $this->pixie->db->expr('("DELIVERED","RELEASED")')))->
                    find();
        } else if ($role_shop->loaded() || $role_rc->loaded()) {
            $pnt = $this->user->org->loc->pnts->
                    where("TRNSP_PNT_ID", $pnt->id())->
                    where("and", array("TRNSP_PNT_STS_TYPE_CD", "DELIVERED"))->
                    find();
        }
        if (!$pnt->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Point is not found.', 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }
        /*
          $claim_type = $this->check_field('claim_type_cd', 'claimtype', 'CLAIM_TYPE_CD');
          if (!is_object($claim_type)) {
          $this->view->message = json_encode(array('Error' => $claim_type, 'Result' => 'driverrelease', 'Data' => ''));
          return;
          }
         */
        $claim_types = $this->request->post('claim_types');
        $claim_types = //html_entity_decode($transp);
                html_entity_decode($claim_types, ENT_QUOTES | ENT_XML1, 'UTF-8');
        if (($claim_types == '') || ($claim_types == 'null')) {
            $claim_types = [];
        } else {
            $claim_types = json_decode($claim_types);
        }
        if (!is_array($claim_types)) {
            $this->view->message = json_encode(array('Error' => array('Claim types format is wrong'), 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }

        $shop_mark = $this->check_field('shop_mark', '', '', false, false, true, 'int');
        if (!is_object($shop_mark)) {
            $this->view->message = json_encode(array('Error' => $shop_mark, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }

        $shop_comment = $this->check_field('shop_comment', '', '', false, false, false, false);
        if (!is_object($shop_comment)) {
            $this->view->message = json_encode(array('Error' => $shop_comment, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }
        if (strlen($shop_comment->value) > 4000) {
            $this->view->message = json_encode(array('Error' => 'Comment is too long', 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }

        $dttm = $this->check_field('rel_dttm', '', '', false, false, false, false);
        if (!is_object($dttm)) {
            $this->view->message = json_encode(array('Error' => $dttm, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }


        $lat = $pnt->loctgt->LAT;
        $lon = $pnt->loctgt->LON;
        //$timezone = $this->get_nearest_timezone($lat, $lon, 'RU');
        $timezone_id = $this->get_timezone($lat, $lon, 'glarus_digital');

        if (isset(json_decode($timezone_id)->Error)) {
            $this->view->message = json_encode(array('Error' => 'Timezone is not recognized:' . json_decode($timezone_id)->Error, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }
        $timezone = new DateTimeZone($timezone_id);
        if (!(($dttm->value == 'null') || ($dttm->value == ''))) {
            $timezone = null;
        } else {
            $dttm->value = null;
        }
        //die($dttm->value);
        $pnt->deleteoldclaims($mark_type_cd, $this->user->id());
        foreach ($claim_types as $claim_type) {
            $pnt->addclaim($mark_type_cd, $claim_type->CLAIM_TYPE_CD, $this->user->id());
        }
        $pnt->setmark($mark_type_cd, $shop_mark->value, $shop_comment->value, $this->user->id());
        $pnt->setstatus('RELEASED', 0, $this->user->id(), $timezone, $dttm->value);



        $this->view->message = json_encode(array('Error' => '', 'Result' => 'driverrelease', 'Data' => $pnt->REL_STS_DTTM));

        $this->view->subview = 'apianswer';
    }

    public function action_driverfinish() {

        if ($this->view->message) {
            return;
        }
        date_default_timezone_set('Europe/Moscow');

        $pnt_id = $this->request->post('pnt_id');
        $pnt_id = filter_var($pnt_id, FILTER_SANITIZE_STRING);
        if (!is_numeric($pnt_id)) {
            $this->view->message = json_encode(array('Error' => 'Point id is wrong.', 'Result' => 'driverfinish', 'Data' => ''));
            return;
        }
        $pnt = $this->pixie->orm->get('pnt')->
                where("TRNSP_PNT_ID", $pnt_id)->
                where("and", array("TRNSP_PNT_STS_TYPE_CD", "CREATED"))->
                find();
        if (!$pnt->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Point is not found.', 'Result' => 'driverfinish', 'Data' => ''));
            return;
        }

        $role = $this->user->roles->where('CODE', 'ADMIN')->find();
        if ($role->loaded()) {
            $transp = $pnt->transp->find();
            $dttm = $this->request->post('dttm');
            $dttm = filter_var($dttm, FILTER_SANITIZE_STRING);
            if ($dttm == '') {
                $dttm = null;
            }
        } else {
            $dttm = null;
            $transp = $this->pixie->orm->get('transp')->where('USER_TOKEN', $this->token)->find();
        }

        if (!$transp->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Transportation not found', 'Result' => 'driverfinish', 'Data' => ''));
            return;
        }

        $usertoken = $this->pixie->orm->get('usertoken')->where('TOKEN', $this->token)->find();
        $user_id = $usertoken->USER_ID;


        $lat = $this->request->post('lat');
        $lon = $this->request->post('lon');



        $lat = filter_var($lat, FILTER_SANITIZE_STRING);
        if (!is_numeric($lat)) {
            $this->view->message = json_encode(array('Error' => 'Lat id is wrong.', 'Result' => 'driverfinish', 'Data' => ''));
            return;
        }
        $lon = filter_var($lon, FILTER_SANITIZE_STRING);
        if (!is_numeric($lon)) {
            $this->view->message = json_encode(array('Error' => 'Lon id is wrong.', 'Result' => 'driverfinish', 'Data' => ''));
            return;
        }

        //$timezone = $this->get_nearest_timezone($lat, $lon, 'RU');
        $timezone_id = $this->get_timezone($lat, $lon, 'glarus_digital');
        if (isset(json_decode($timezone_id)->Error)) {
            $this->view->message = json_encode(array('Error' => 'Timezone is not recognized:' . json_decode($timezone_id)->Error, 'Result' => 'driverfinish', 'Data' => ''));
            return;
        }
        $timezone = new DateTimeZone($timezone_id);

        $loc_tgt_lat = $pnt->loctgt->LAT;
        $loc_tgt_lon = $pnt->loctgt->LON;
        $d = $this->haversineGreatCircleDistance($lat, $lon, $loc_tgt_lat, $loc_tgt_lon);
        //if ($d > 1000) {
        if (false) {
            $this->view->message = json_encode(array('Error' => 'You are not in 1km radius of the destination point.', 'Result' => 'driverfinish', 'Data' => ''));
            return;
        } else {
            $pnt->setstatus('DELIVERED', $d, $user_id, $timezone, $dttm);
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'driverfinish', 'Data' => $pnt->STS_DTTM));
        }
        $this->view->subview = 'apianswer';
    }

    public function action_getclaims() {

        if ($this->view->message) {
            return;
        }

        $role_admin = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        $role_shop = $this->user->roles->where('CODE', 'SHOP')->find();
        $role_rc = $this->user->roles->where('CODE', 'RC')->find();
        $role_vendor = $this->user->roles->where('CODE', 'VENDOR')->find();
        $role_transp = $this->user->roles->
                where('CODE', 'TRANSPORT_COMPANY')->
                find();

        if (!($role_admin->loaded() || $role_shop->loaded() || $role_rc->loaded() || $role_vendor->loaded() || $role_transp->loaded())) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getclaims', 'Data' => ''));
            return;
        }

        $pnt = $this->check_field('pnt_id', 'pnt', 'TRNSP_PNT_ID');
        if (!is_object($pnt)) {
            $this->view->message = json_encode(array('Error' => $pnt, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }

        $orgtype = $pnt->loctgt->org->orgtype->ORG_TYPE_CD;

        if ($orgtype == 'RC') {
            $marktype = 'RC_MARKS_TU';
        } else if ($orgtype == 'SHOP') {
            $marktype = 'SHOP_MARKS_TU';
        } else {
            $marktype = '';
        }

        $res = [];
        $i = 0;
        $claims = $pnt->claims->where('MARK_TYPE_CD', $marktype)->find_all();
        foreach ($claims as $claim) {
            $rec = [];
            $rec['CLAIM_TYPE_CD'] = $claim->CLAIM_TYPE_CD;
            $rec['CLAIM_TYPE_NM'] = $claim->claimtype->CLAIM_TYPE_NM;
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getclaims', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_getlocs() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'DRIVER')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        $transp = $this->pixie->orm->get('transp')->where('USER_TOKEN', $this->token)->find();

        if (!$transp->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Transportations not found', 'Result' => 'gettransp', 'Data' => ''));
            return;
        }

        $res = [];
        $i = 0;
        $locs = $transp->locs->find_all();
        foreach ($locs as $loc) {
            $res[$i] = $loc->as_array();
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getlocs', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_getusers() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getusers', 'Data' => ''));
            return;
        }

        //  $org = $this->user->org;
        //  if (!$org->loaded()) {
        $users = $this->pixie->orm->get('user')->where('NAME', '<>', 'driver')->find_all();
        //  } else {
        //      $users = $this->pixie->orm->get('user')->where('ORG_ID', $org->id())->where('and', array('NAME', '<>', 'driver'))->find_all();
        //  }

        $res = [];
        $i = 0;
        foreach ($users as $user) {
            $rec = [];
            $roles = $user->roles->find_all()->as_array(1);
            $rec['USER_ID'] = $user->id();
            $rec['EMAIL'] = $user->EMAIL;
            $rec['ACTIVE_FL'] = $user->ACTIVE_FL;
            $rec['CREATED_DATE'] = $user->CREATED_DATE;
            $rec['USER_STS_TYPE_CD'] = $this->pixie->orm->get('usersts')->where('user_sts_type_cd', $user->user_sts_type_cd)->find()->user_sts_type_name;
            $rec['ORG_ID'] = $user->ORG_ID;
            if ($user->ORG_ID != '') {
                $rec['ORG_NM'] = $user->org->ORG_NM;
            } else {
                $rec['ORG_NM'] = '';
            }
            $rec['roles'] = $roles;
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getusers', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_getpoints() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'DRIVER')->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        $transp = $this->pixie->orm->get('transp')->where('USER_TOKEN', $this->token)->find();

        if (!$transp->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Transportations not found', 'Result' => 'gettransp', 'Data' => ''));
            return;
        }

        $res = [];
        $i = 0;
        $pnts = $transp->pnts->where('TRNSP_PNT_STS_TYPE_CD', '<>', 'DELETED')->order_by('LOC_PLAN_DTTM')->find_all();
        foreach ($pnts as $pnt) {
            $rec = [];
            $rec['TU'] = $transp->TU;
            $rec['DRIVER_PHONE'] = $transp->DRIVER_PHONE;
            $rec['FULL_NM'] = $transp->FULL_NM;
            $rec['TRNSP_PNT_ID'] = $pnt->TRNSP_PNT_ID;
            $rec['LOC_PLAN_DTTM'] = $pnt->LOC_PLAN_DTTM;
            $rec['TRNSP_PNT_STS_TYPE_CD'] = $pnt->sts->TRNSP_PNT_STS_TYPE_NM;
            $rec['STS_DTTM'] = $pnt->STS_DTTM;
            if ($pnt->LOC_SRC_ID != '') {
                $rec['LOC_SRC_NM'] = $pnt->locsrc->LOC_NM;
            } else {
                $rec['LOC_SRC_NM'] = '';
            }
            $rec['LOC_TGT_NM'] = $pnt->loctgt->LOC_NM;
            $rec['LOC_ADDR'] = $pnt->loctgt->ADDR;
            $rec['LOC_TGT_LAT'] = $pnt->loctgt->LAT;
            $rec['LOC_TGT_LON'] = $pnt->loctgt->LON;
            $rec['ORG_NM'] = $transp->org->ORG_NM;
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getlocs', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_getallpoints() {

        ini_set('memory_limit', '256000000');

        if ($this->view->message) {
            return;
        }

        $role_admin = $this->user->roles->
                where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->
                find();
        $role_transp = $this->user->roles->
                where('CODE', 'TRANSPORT_COMPANY')->
                find();
        $role_vendor = $this->user->roles->
                where('CODE', 'VENDOR')->
                find();
        $role_rc = $this->user->roles->
                where('CODE', 'RC')->
                find();
        $role_shop = $this->user->roles->
                where('CODE', 'SHOP')->
                find();
        if (!($role_admin->loaded() || ($role_transp->loaded()) || ($role_vendor->loaded()) || ($role_rc->loaded()) || ($role_shop->loaded()))) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getallpoints', 'Data' => ''));
            return;
        }

        $org = $this->user->org;

        $date_from = $this->check_field("date_from", "", "", false, false, false)->value;
        $date_to = $this->check_field("date_to", "", "", false, false, false)->value;

        if ($date_from == '') {
            $date_from = '2000-01-01';
        }
        if ($date_to == '') {
            $date_to = '9999-01-01';
        }

        if ($role_admin->loaded()) {
            //$pnts = $this->pixie->orm->get('pnt')->with('transp')->with('locsrc')->with('loctgt')->with('sts')->find_all();
            $pnts = $this->pixie->orm->get('pntall')->
                    where('LOC_PLAN_DTTM', '>=', $date_from)->
                    where('and', array('LOC_PLAN_DTTM', '<=', $date_to))->
                    find_all();
            //          $message = $this->pixie->orm->get('pntall')->
            //                          where('LOC_PLAN_DTTM', '>=', $date_from)->
            //                          where('and', array('LOC_PLAN_DTTM', '<=', $date_to))->
            //                  query->query()[0];
            //           $this->logerror('getallpoints', $message, 'ERROR');
        } else if ($role_transp->loaded() || $role_vendor->loaded()) {
            //$pnts = $this->pixie->orm->get('transp')->where('ORG_ID', $org->id())->pnts->find_all();
            $pnts = $this->pixie->orm->get('pntall')->where('ORG_ID', $org->id())->
                    where('and', array('LOC_PLAN_DTTM', '>=', $date_from))->
                    where('and', array('LOC_PLAN_DTTM', '<=', $date_to))->
                    find_all();
        } else if ($role_rc->loaded()) {
            $pnts = $org->getallpoints_rc($date_from, $date_to);
            //$pnts = $this->pixie->orm->get('org')->where('ORG_TYPE_CD','RC')->loc->pnts->find_all();
            /* $pnts = $this->pixie->orm->get('pntall')->
              where('ORG_SRC_ID', $org->id())->
              where('or', array('ORG_TGT_ID', $org->id()))->
              where('and', array('LOC_PLAN_DTTM', '>=', $date_from))->
              where('and', array('LOC_PLAN_DTTM', '<=', $date_to))->
              find_all(); */
            //$pnts = $org->getallpoints($date_from, $date_to);
        } else if ($role_shop->loaded()) {
            $pnts = $org->getallpoints($date_from, $date_to);
            //$pnts = $this->pixie->orm->get('org')->where('ORG_TYPE_CD','RC')->loc->pnts->find_all();
            //  $pnts = $this->pixie->orm->get('pntall')->
            //        where('ORG_TGT_ID', $org->id())->
            //      transp->pntall->
            //where('LOC_TGT_TYPE_CD', 'RC')->
            // where('or',array('ORG_TGT_ID',$org->id()))->
            //    find_all();
            // query->query()[0];
            // die($pnts);
            // $pnts_shop = $this->pixie->orm->get('pntall')->
            //         where('ORG_TGT_ID', $org->id())->
            //         find_all();
        }



        $res = [];
        $i = 0;

        foreach ($pnts as $pnt) {
            $rec = [];
            /*
              $rec['TU'] = $pnt->transp->TU;
              $rec['FULL_NM'] = $pnt->transp->FULL_NM;
              $rec['TRNSP_PNT_ID'] = $pnt->TRNSP_PNT_ID;
              $rec['LOC_PLAN_DTTM'] = $pnt->LOC_PLAN_DTTM;
              $rec['TRNSP_PNT_STS_TYPE_CD'] = $pnt->sts->TRNSP_PNT_STS_TYPE_NM;
              $rec['STS_DTTM'] = $pnt->STS_DTTM;
              if ($pnt->LOC_SRC_ID != '') {
              $rec['LOC_SRC_NM'] = $pnt->locsrc->LOC_NM;
              } else {
              $rec['LOC_SRC_NM'] = '';
              }
              $rec['LOC_TGT_NM'] = $pnt->loctgt->LOC_NM;
              $rec['LOC_ADDR'] = $pnt->loctgt->ADDR;
              $rec['LOC_TGT_LAT'] = $pnt->loctgt->LAT;
              $rec['LOC_TGT_LON'] = $pnt->loctgt->LON;
              $rec['ORG_NM'] = $pnt->transp->org->ORG_NM;

             */
            $rec['TU'] = $pnt->TU;
            $rec['FULL_NM'] = $pnt->FULL_NM;
            $rec['DRIVER_PHONE'] = $pnt->DRIVER_PHONE;
            $rec['TRNSP_PNT_ID'] = $pnt->TRNSP_PNT_ID;
            $rec['LOC_PLAN_DTTM'] = $pnt->LOC_PLAN_DTTM;
            $rec['TRNSP_PNT_STS_TYPE_CD'] = $pnt->TRNSP_PNT_STS_TYPE_NM;
            $rec['STS_DTTM'] = $pnt->STS_DTTM;
            if ($pnt->LOC_SRC_NM != '') {
                $rec['LOC_SRC_NM'] = $pnt->LOC_SRC_NM;
            } else {
                $rec['LOC_SRC_NM'] = '';
            }
            $rec['LOC_TGT_NM'] = $pnt->LOC_TGT_NM;
            $rec['LOC_ADDR'] = $pnt->ADDR;
            $rec['LOC_TGT_LAT'] = $pnt->LAT;
            $rec['LOC_TGT_LON'] = $pnt->LON;
            $rec['LOC_TGT_TYPE_CD'] = $pnt->LOC_TGT_TYPE_CD;
            $rec['ORG_NM'] = $pnt->ORG_NM;
            $rec['ORG_TYPE_NM'] = $pnt->ORG_TYPE_NM;
            //     if (!($role_transp->loaded() || $role_vendor->loaded())) {
            $rec['SHOP_MARK'] = $pnt->MARK;
            $rec['REL_STS_DTTM'] = $pnt->REL_STS_DTTM;
            $rec['SHOP_COMMENT'] = $pnt->MARK_COMMENT;
            /*  $pnt1 = $this->pixie->orm->get('pnt')->
              where('TRNSP_PNT_ID', $pnt->TRNSP_PNT_ID)->find();
              $claims = $pnt1->claims->find_all();
              $carr = [];
              $j = 0;
              foreach ($claims as $claim) {

              $res1 = [];
              $res1['CLAIM_TYPE_CD'] = $claim->CLAIM_TYPE_CD;
              $res1['CLAIM_TYPE_NM'] = $claim->claimtype->CLAIM_TYPE_NM;
              $carr[$j] = $res1;
              $j = $j + 1;
              }

              $rec['CLAIMS'] = $carr; */
            //   }
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getlocs', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_getalllocs() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getalllocs', 'Data' => ''));
            return;
        }


        $shops = $this->pixie->orm->get('shop')->find_all();

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getalllocs', 'Data' => $shops->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getloadlogs() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getloadlogs', 'Data' => ''));
            return;
        }

        $etl_run = $this->check_field('etl_run_id', 'etlrun', 'etl_run_id');
        if (!is_object($etl_run)) {
            $this->view->message = json_encode(array('Error' => $etl_run, 'Result' => 'getloadlogs', 'Data' => ''));
            return;
        }


        $logs = $etl_run->pntlogs->find_all();

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getloadlogs', 'Data' => $logs->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getloadsessions() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getloadsessions', 'Data' => ''));
            return;
        }

        $etlruns = $this->pixie->orm->get('etlrun')->find_all();

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getloadsessions', 'Data' => $etlruns->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getuserlogs() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getuserlogs', 'Data' => ''));
            return;
        }

        $amt = $this->check_field('amt', '', '', false, false);
        if (!is_object($amt)) {
            $this->view->message = json_encode(array('Error' => $amt, 'Result' => 'getuserlogs', 'Data' => ''));
            return;
        }


        $logs = $this->pixie->orm->get('log')->order_by('created_dttm', 'desc')->limit($amt->value)->find_all();

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getuserlogs', 'Data' => $logs->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getclaimtypes() {

        if ($this->view->message) {
            return;
        }

        $role_shop = $this->user->roles->where('CODE', 'SHOP')->find();
        $role_rc = $this->user->roles->where('CODE', 'RC')->find();
        $role_admin = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();

        if (!($role_shop->loaded() || $role_admin->loaded() || $role_rc->loaded())) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getclaimtypes', 'Data' => ''));
            return;
        }

        // $org = $this->user->org;
        // if (!$org->loaded()) {
        $pnt = $this->check_field('pnt_id', 'pnt', 'TRNSP_PNT_ID');
        if (!is_object($pnt)) {
            $this->view->message = json_encode(array('Error' => $pnt, 'Result' => 'driverrelease', 'Data' => ''));
            return;
        }

        $orgtype = $pnt->loctgt->org->orgtype->ORG_TYPE_CD;
        // die($orgtype);

        if ($orgtype == 'RC') {
            $marktype = 'RC_MARKS_TU';
        } else if ($orgtype == 'SHOP') {
            $marktype = 'SHOP_MARKS_TU';
        } else {
            $marktype = '';
        }

        $claimtypes = $this->pixie->orm->get('claimtype')
                ->where('MARK_TYPE_CD', $marktype)
                ->find_all();
        // } else {
        //     $roles = $this->pixie->orm->get('role')->
        //             where('PARENT_CODE', 'TRANSPORT_COMPANY')->
        //             find_all();
        // }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getclaimtypes', 'Data' => $claimtypes->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getallroles() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getallroles', 'Data' => ''));
            return;
        }

        // $org = $this->user->org;
        // if (!$org->loaded()) {
        $roles = $this->pixie->orm->get('role')->
                where('PARENT_CODE', 'ADMINLERUA')->
                where('or', array('PARENT_CODE', 'TRANSPORT_COMPANY'))->
                find_all();
        // } else {
        //     $roles = $this->pixie->orm->get('role')->
        //             where('PARENT_CODE', 'TRANSPORT_COMPANY')->
        //             find_all();
        // }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getallroles', 'Data' => $roles->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getpnthst() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'reguser', 'Data' => ''));
            return;
        }
        $pnt = $this->check_field('pnt_id', 'pnt', 'trnsp_pnt_id');
        if (!is_object($pnt)) {
            $this->view->message = json_encode(array('Error' => $pnt, 'Result' => 'getpnthst', 'Data' => ''));
            return;
        }


        $pntstshis = $pnt->stshis->find_all();
        $res = [];
        $i = 0;
        foreach ($pntstshis as $pntsts) {
            $rec = [];
            $rec['PNT_ID'] = $pnt->id();
            $rec['USER'] = $pntsts->user->EMAIL;
            //  $rec['IP']=$pntsts->user->usertokens->where($pntsts->TRNSP_PNT_STS_FROM);
            $rec['LOG_DTTM'] = $pntsts->TRNSP_PNT_STS_FROM;
            $rec['MESSAGE'] = ' Изменил статус на ' . $pntsts->status->TRNSP_PNT_STS_TYPE_NM;
            // $rec['TRNSP_PNT_STS_TYPE_CD']=$pntsts->TRNSP_PNT_STS_TYPE_CD;
            // $rec['STS_DTTM']=$pntsts->STS_DTTM;
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $pntmarkhis = $pnt->markhis->find_all();
        foreach ($pntmarkhis as $markhis) {
            $rec = [];
            $rec['PNT_ID'] = $pnt->id();
            $rec['USER'] = $markhis->user->EMAIL;
            //  $rec['IP']=$pntsts->user->usertokens->where($pntsts->TRNSP_PNT_STS_FROM);
            $rec['LOG_DTTM'] = $markhis->TRNSP_PNT_MARK_FROM;
            $rec['MESSAGE'] = ' Изменил оценку на ' . $markhis->MARK;
            // $rec['TRNSP_PNT_STS_TYPE_CD']=$pntsts->TRNSP_PNT_STS_TYPE_CD;
            // $rec['STS_DTTM']=$pntsts->STS_DTTM;
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getpnthst', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_gettransp() {

        if ($this->view->message) {
            return;
        }

        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'reguser', 'Data' => ''));
            return;
        }

        $org = $this->user->org->find();

        if (!$org->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Organization not found', 'Result' => 'gettransp', 'Data' => ''));
            return;
        }

        $res = [];
        $i = 0;
        $pnts = $transp->pnts->find_all();
        foreach ($pnts as $pnt) {
            $rec = [];
            $rec['TU'] = $transp->TU;
            $rec['FULL_NM'] = $transp->FULL_NM;
            $rec['TRNSP_PNT_ID'] = $pnt->TRNSP_PNT_ID;
            $rec['LOC_PLAN_DTTM'] = $pnt->LOC_PLAN_DTTM;
            $rec['LOC_TGT_NM'] = $pnt->loctgt->LOC_NM;
            $rec['LOC_TGT_LAT'] = $pnt->loctgt->LAT;
            $rec['LOC_TGT_LON'] = $pnt->loctgt->LON;
            $res[$i] = $rec;
            $i = $i + 1;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getlocs', 'Data' => $res));

        $this->view->subview = 'apianswer';
    }

    public function action_getorg() {

        if ($this->view->message) {
            return;
        }

        $org = $this->check_field('org_id', 'org', 'ORG_ID');
        if (!is_object($org)) {
            $this->view->message = json_encode(array('Error' => $org, 'Result' => 'delpoint', 'Data' => ''));
            return;
        }



        if (!$org->loaded()) {
            $this->view->message = json_encode(array('Error' => 'Organization not found', 'Result' => 'gettransp', 'Data' => ''));
            return;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getlocs', 'Data' => $org->as_array(1)));

        $this->view->subview = 'apianswer';
    }

    public function action_getallorgs() {

        if ($this->view->message) {
            return;
        }
        $role = $this->user->roles->where('CODE', 'IN', $this->pixie->db->expr('("ADMIN","ADMIN_LIGHT")'))->find();
        if (!$role->loaded()) {
            $this->view->message = json_encode(array('Error' => "You dont't have access to this method", 'Result' => 'getallorgs', 'Data' => ''));
            return;
        }

        $this->view->message = json_encode(array('Error' => '', 'Result' => 'getallorgs',
            'Data' => $this->pixie->orm->get('org')->
                    where('ORG_STS_TYPE_CD', 'ACTIVE')->
                    where('ORG_TYPE_CD', '<>', 'TEST')->
                    order_by('ORG_NM')->find_all()->as_array(1)));

        $this->view->subview = 'apianswer';
    }

}
