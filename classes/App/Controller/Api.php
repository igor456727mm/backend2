<?php

namespace app\controller;

if (!defined('API'))
    define('API', '1');

class Api extends \App\Page {

    public function before() {

        //      die('1');
        // sleep(1);
        header('Content-Type: application/json');
        $this->view = $this->pixie->view('main');
        $this->view->subview = 'apianswer';
        $this->view->message = null;
        if ($this->request->method != 'POST') {
            //die('2');
            $this->view->message = json_encode(array('Error' => 'POST method should be used', 'Result' => ''));
            return;
        }
    }

    public function action_addfragment() {
        if ($this->view->message) {
            return;
        }
        $this->view->message = json_encode(array('Error' => '', 'Result' => 'addfragment', 'Data' => 'Ok'));


        $dev_gui = $this->request->post('dev_gui');
        $frgm = $this->request->post('frgm');
        $frgm_start_dttm = $this->request->post('frgm_start_dttm');
        $frgm_end_dttm = $this->request->post('frgm_end_dttm');
        $ev_type_cd = $this->request->post('ev_type_cd');
        $obj_id = $this->request->post('obj_id');
    }

}
