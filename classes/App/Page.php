<?php

namespace App;

/**
 * Base controller
 *
 * @property-read \App\Pixie $pixie Pixie dependency container
 */
class Page extends \PHPixie\Controller {

    protected $auth;
    protected $view;

    public function logerror($sender, $message, $level_cd = 'ERROR', $ev_id = null, $user_id = null) {
        if ($level_cd != '') {
            $log = $this->pixie->orm->get('Log');
            $log->sender_cd = $sender;
            $log->message = $message;
            $log->level_cd = $level_cd;
            if ($ev_id) {
                $log->ev_id = $ev_id;
            }
            if ($user_id) {
                $log->user_id = $user_id;
            }
            $log->save();
        }
    }

    public function before() {
        $this->view = $this->pixie->view('main');
        $this->view->token =  null;
    /*    if ($this->pixie->auth->user() != null) {


            $user = $this->pixie->auth->user();
            //die($user->id());
            $this->view->user = $user;
            $this->view->token = $this->pixie->orm->get('Usertoken')->where('ACTIVE_FL', 1)->
                    where('and', array('USER_ID', $user->id()))->
                    find();
            //die($this->view->token);
        } else {
            $this->view->token = null;
        }

        $qry = 'SET session wait_timeout = 900;';
        $res = $this->pixie->orm->get('Usertoken')->conn->execute($qry);

        $lang = 'en';
        if ($this->pixie->auth->user() != null) {
            $user = $this->pixie->auth->user();
            $lang = $user->LANG_CODE;
        } else {
            if (isset($_COOKIE['lang'])) {
                $lang = $_COOKIE['lang'];
            }
        }*/
        // $this->view->localizator = $this->pixie->localization->get('feed', $lang);
        // $this->view->localizator->user_language = 'en';
        //die($this->view->token->TOKEN);
        //This is our main page layout
    }

    public function after() {
/*
        $message = json_decode($this->view->message);
        if (is_object($message)) {
            $log = $this->pixie->orm->get('log');
            $log->sender_cd = 'API';
            if (!is_string($message->Error)) {
                $err_m='';
                foreach ($message->Error as $err) {
                    $err_m=$err;
                }
                $log->message = "array of " . count($message->Error) . ' objects array[1]:'.json_encode($err_m);
            } else {
                $log->message = $message->Error;
            }

            if (!is_string($message->Data)) {
                $log->message = "array of " . count($message->Data) . ' objects';
            } else {
                $log->message = $message->Data;
            }


            $log->action = $message->Result;
            if (isset($this->user)) {
                $log->user_id = $this->user->id();
            }
            if ($message->Error != '') {
                $log->level_cd = 'ERROR';
            } else {
                $log->level_cd = 'INFO';
            }
//echo    $log->message;     
            $log->save();
  */      
        $this->response->body = $this->view->render();
    }

    //This method will redirect the user to the login page
    //if he is not logged in yet, or present him with a message
    //if he lacks the required role.
    protected function logged_in($role = null) {
        if ($this->pixie->auth->user() == null) {
            $this->redirect('/main/login');
            return false;
        }
        if ($role && !$this->pixie->auth->has_role($role)) {
            $this->response->body = "You don't have the permissions to access this page";
            $this->execute = false;
            return false;
        }

        //if (!$this->pixie->auth->user()->eula_agreed('BASE')) {
        //    $this->redirect('/eula/type=BASE');
        //}

        return true;
    }

}
