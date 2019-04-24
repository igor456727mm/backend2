<?php

namespace app\controller;

define('API', '1');

class Apiopen extends \App\Page {

    // api login logins the user and returns new token 

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
    }

    public function action_restore() {

        if ($this->view->message) {
            return;
        }
        $password = trim($this->request->post('password'));
        $restore_code = trim($this->request->post('rc'));

        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $restore_code = filter_var($restore_code, FILTER_SANITIZE_STRING);

        if (strlen($password) < 8) {
            $this->view->message = json_encode(array('Error' => 'Password should be at least 8 characters', 'Result' => 'restorepsw','Data' =>''));
            return;
        }

        $user_ident = $this->pixie->orm->get('user')->where('RESTORE_CODE', $restore_code)->where('and', array('IN_RESTORE', 1))->find();

        if ($user_ident->loaded()) {

            if ($user_ident->fa_fl) {
                $oneCode = $this->request->post('onecode');
                $this->user = $user_ident;
                $res = $this->checkga($oneCode);
                $t = json_decode($res);
                if (!(isset($t->Data) && $t->Data)) {
                    $this->view->message = json_encode(array('Error' => 'Wrong 2fa code', 'Result' => 'restorepsw','Data' =>''));
                    return;
                }
            }

            $hash = $this->pixie->auth->provider('password')->hash_password($password);
            $user_ident->PSW = $hash;
            $user_ident->IN_RESTORE = 0;
            $user_ident->RESTORE_CODE = '';
            require '../assets/config/env.php';
            $res = $this->pixie->email->send($user_ident->EMAIL,array($site_email => "Доставка ЛМ"), "Доставка в магазины Леруа Мерлен - ваш пароль изменен", "Вы изменили пароль на сайте dostavkalm.ru
" . "Нужна помощь?" . "
" . "+7-903-665-40-52" . "
" . "© 2019 dostavkalm.ru " . "
" . "Авторские права защищены.");
            
            
            $user_ident->save();
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'restorepsw', 'Data' => 'Your password has changed.'));
            return;
        } else {
            $this->view->message = json_encode(array('Error' => 'This link is not active.', 'Result' => 'restorepsw','Data' =>''));
            return;
        }
    }

    public function action_resetpsw() {
        if ($this->view->message) {
            return;
        }
        $email = trim($this->request->post('email'));

        if (isset($email) && ($email != '')) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->view->message = json_encode(array('Error' => 'Email format is wrong', 'Result' => 'restorepsw','Data' =>''));
                return;
            }
            $user_ident = $this->pixie->orm->get('user')->
                    where('EMAIL', $email)->
                    //    where('and',array('IN_RESTORE', 0))->
                    find();
            if ($user_ident->loaded()) {
                if ($user_ident->user_sts_type_cd <> 'activated') {
                    $this->view->message = json_encode(array('Error' => 'Account is not activated', 'Result' => 'restorepsw','Data' =>''));
                    return;
                }
                if ($user_ident->fa_fl) {
                    $oneCode = $this->request->post('onecode');
                    $this->user = $user_ident;
                    $res = $this->checkga($oneCode);
                    $t = json_decode($res);
                    if (!(isset($t->Data) && $t->Data)) {
                        $this->view->message = json_encode(array('Error' => 'Wrong 2fa code', 'Result' => 'restorepsw','Data' =>''));
                        return;
                    }
                }
                $restore_code = //bin2hex(random_bytes(32));
                md5(mt_rand(1000000000, 2000000000));
                $user_ident->RESTORE_CODE = $restore_code;
                $user_ident->IN_RESTORE = 1;
                $user_ident->save();
                require '../assets/config/env.php';
                $res = $this->pixie->email->send($email, array($site_email => "Доставка ЛМ"), "Доставка в магазины Леруа Мерлен - запрос на изменение пароля", "Уважаемый пользователь!
Для изменения пароля перейдите по ссылке: 
" .
                        $site_url . '/restore.xhtml?rc=' . $restore_code . "
" . "Нужна помощь?" . "
" . "+7-903-665-40-52" . "
" . "© 2019 dostavkalm.ru " . "
" . "Авторские права защищены.");
                $this->view->message = json_encode(array('Error' => '', 'Result' => 'restorepsw', 'Data' => 'If the email is registered, we have sent the restoration link to it.'));
                return;
            } else {
                $this->view->message = json_encode(array('Error' => '', 'Result' => 'restorepsw', 'Data' => 'If the email is registered, we have sent the restoration link to it.'));
                return;
            }
        } else {
            $this->view->message = json_encode(array('Error' => 'Email format is wrong', 'Result' => 'restorepsw','Data' =>''));
            return;
        }
    }

    public function action_login() {
        
        usleep(500);

        $this->view->subview = 'apianswer';

        if ($this->request->method == 'POST') {
            $login = $this->request->post('username');
            $password = $this->request->post('password');
            $ip=$this->request->post('ip');
        }

        if ($this->request->method == 'GET') {
            $login = $this->request->param('username');
            $password = $this->request->param('password');
            $ip=$this->request->param('ip');
        }

        if (($login == '') || ($password == '')) {
            $this->view->message = json_encode(array('Error' => 'Username or password does not exist.', 'Result' => 'login', 'Data' => ''));
            return;
        }

        $login = filter_var($login, FILTER_SANITIZE_STRING);

        $user = $this->pixie->orm->get('user')->where('EMAIL', $login)->find();

        if ($user->loaded() && $user->user_sts_type_cd == 'deleted') {
            $this->view->message = json_encode(array('Error' => 'User is deleted.', 'Result' => 'login', 'Data' => ''));
            return;
        }

        $password = filter_var($password, FILTER_SANITIZE_STRING);

        $pl = trim(mb_convert_case($login, MB_CASE_LOWER, "UTF-8"));

        $transp = $this->pixie->orm->get('transp')->
                where($this->pixie->db->expr("SUBSTRING_INDEX(SUBSTRING_INDEX(FULL_NM, ' ', 1), ' ', -1)"), $pl)->
                where('and', array('TU', $password))->
                find();

        if ($transp->loaded()) {
            $login = 'driver';
            $password = 'ury6793hdjgseut7593fmgb';
            //die($password);
        }

        $logged = false;
        if (($login == '') || ($password == '')) {
            $logged = false;
        } else {
            //die("login:".$login."-password:".$password);
            $logged = $this->pixie->auth
                    ->provider('password')
                    ->login($login, $password);
        }

        if ($logged) {
            $usertoken = $this->pixie->orm->get('Usertoken');
            $usertoken->TOKEN = md5(mt_rand(1000000000, 2000000000));
            $usertoken->USER_ID = $this->pixie->auth->user()->id();
            $usertoken->ip=$ip;
            $usertoken->save();
            if ($transp->loaded()) {
                $transp->USER_TOKEN = $usertoken->TOKEN;
                $transp->save();
            }
            $res = [];
            $res['token'] = $usertoken->TOKEN;
            $user = $this->pixie->auth->user();
            $roles = $user->roles->find_all();
            $res['roles'] = $roles->as_array(true);
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'token', 'Data' => $res));
        } else {
            $this->view->message = json_encode(array('Error' => 'Username or password does not exist.', 'Result' => 'login', 'Data' => ''));
        }

        $this->view->subview = 'apianswer';
    }

    public function action_activate() {
        //$this->view->message = '0';
        //  $act_key = $this->request->param('act_key');
        if ($this->view->message) {
            return;
        }

        $uid = $this->request->post('uid');
        $uid = filter_var($uid, FILTER_SANITIZE_STRING);

        if ($uid == '') {
            $this->view->message = json_encode(array('Error' => 'Uid must not be empty', 'Result' => 'activate', 'Data' => ''));
            return;
        }

        $password = $this->request->post('password');
        $password = filter_var($password, FILTER_SANITIZE_STRING);


        if (strlen($password) < 8) {
            $this->view->message = json_encode(array('Error' => 'Пароль должен содержать не менее 8 символов', 'Result' => '', 'Data' => ''));
            return;
        }

        $hash = $this->pixie->auth->provider('password')->hash_password($password);


        // echo "uid='".$uid."'";
        // die;


        $user = $this->pixie->orm->get('user')->
                        where('ACT_KEY', $uid)->find();
        if ($user->loaded()) {
            if ($user->ACTIVE_FL == 1) {
                $this->view->message = json_encode(array('Error' => 'Ссылка более не действительна.', 'Result' => 'activate', 'Data' => ''));
                return;
            }
            $user->active_fl = 1;
            $user->psw = $hash;
            $user->save();
            $user->setstatus('activated');
            $this->view->message = json_encode(array('Error' => '', 'Result' => 'activate', 'Data' => 'Ok'));
            //   $this->view->message = 'Your account is activated. You can go to <a href="/main/login">login</a> page now.';
        } else {
            $this->view->message = json_encode(array('Error' => 'Аккаунт не найден.', 'Result' => 'activate', 'Data' => ''));
        }
        // $this->view->subview = 'usermessage';
        $this->view->subview = 'apianswer';
    }

    /*
      public function action_register() {
      date_default_timezone_set('UTC');
      $this->view->message = json_encode(array('Error' => 'Some problem occured. Please contact support.', 'Result' => ''));

      if ($this->request->method == 'POST') {
      $password = $this->request->post('psw');
      $password = filter_var($password, FILTER_SANITIZE_STRING);
      $email = $this->request->post('email');
      $email = filter_var($email, FILTER_SANITIZE_STRING);
      $login = $email;
      if ($email == '') {
      $this->view->message = json_encode(array('Error' => 'Email must not be empty', 'Result' => ''));
      return;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->view->message = json_encode(array('Error' => 'Email format is wrong', 'Result' => ''));
      return;
      }

      if (strlen($password) < 8) {
      $this->view->message = json_encode(array('Error' => 'Password must be 8 characters at least', 'Result' => ''));
      return;
      }



      $hash = $this->pixie->auth->provider('password')->hash_password($password);
      $user = $this->pixie->orm->get('user')->where('NAME', $login)->find();

      if ($user->loaded()) {

      } else {

      $user->psw = $hash;
      $user->name = $login;
      $user->email = $email;
      $user->act_key = md5(mt_rand(1000000000, 2000000000));
      $lang_code = 'en';
      if (isset($_COOKIE['lang'])) {
      $user->LANG_CODE = $_COOKIE['lang'];
      $lang_code = $_COOKIE['lang'];
      }
      $user->save();

      $role = $this->pixie->orm->get('role')->where('CODE', 'USER')->find();
      $user->add('roles', $role);

      $this->pixie->email->send($email, 'info@glarus.io', "Glarus account activation", "Thank you for registering with us, " . $login . "!
      Please activate your membership by clicking the link below:
      " .
      'glarus.io/main/activate/uid=' . $user->id() . '&act_key=' . $user->act_key);
      $logged = $this->pixie->auth
      ->provider('password')
      ->login($login, $password);
      if ($logged) {
      $usertoken = $this->pixie->orm->get('Usertoken');
      $usertoken->TOKEN = md5(mt_rand(1000000000, 2000000000));
      $usertoken->USER_ID = $this->pixie->auth->user()->id();
      $usertoken->save();
      $this->view->message = json_encode(array('Error' => '', 'Result' => 'token', 'Data' => $usertoken->TOKEN));
      }
      }
      }
      $this->view->subview = 'apianswer';
      }
     */

    public function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
