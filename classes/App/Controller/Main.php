<?php

namespace app\controller;

class Main extends \App\Page {
    /*
      public function before() {
      parent::before();
      //$this->view = $this->pixie->view('main');
      //$this->view->localizator->user_language='ru';
      $lang=false;
      if($this->pixie->auth->user() != null){
      $user=$this->pixie->auth->user();
      $lang=$user->LANG_CODE;
      }
      $this->view->localizator = $this->pixie->localization->get('feed',$lang);
      $this->view->localizator->user_language='ru';
      }
     */

    public function action_login() {
        if ($this->request->method == 'POST') {
            $login = $this->request->post('username');
            $password = $this->request->post('password');
//echo $login;
//Attempt to login the user using his
//username and password
            $logged = $this->pixie->auth
                    ->provider('password')
                    ->login($login, $password);

//On successful login redirect the user to
//our protected page
            if ($logged) {
                $usertoken = $this->pixie->orm->get('Usertoken');
                $usertoken->TOKEN = bin2hex(random_bytes(32));
                //md5(mt_rand(1000000000, 2000000000));
                $usertoken->USER_ID = $this->pixie->auth->user()->id();
                $usertoken->save();
                return $this->redirect('/');
            }
        }
//Include 'login.php' subview
        $this->view->subview = 'login';
    }

    public function action_getusertoken() {


        if ($this->request->method == 'POST') {
            $login = $this->request->post('username');
            $password = $this->request->post('password');
        }

        if ($this->request->method == 'GET') {
            $login = $this->request->param('username');
            $password = $this->request->param('password');
        }

        $logged = $this->pixie->auth
                ->provider('password')
                ->login($login, $password);

        if ($logged) {
            $usertoken = $this->pixie->orm->get('Usertoken');
            $usertoken->TOKEN = md5(mt_rand(1000000000, 2000000000));
            $usertoken->USER_ID = $this->pixie->auth->user()->id();
            $usertoken->save();
            $this->view->message = $usertoken->TOKEN;
        } else {
            $this->view->message = 'Incorrect username or password';
        }


//Include 'login.php' subview
        $this->view->subview = 'apianswer';
    }

    public function action_apiauth() {
        if (!$this->logged_in('user'))
            return;
        $this->redirect('https://oauth.yandex.ru/authorize?response_type=code&client_id=2415a14266224a618a3b2256c246ba36');
    }

    public function action_logout() {
        $this->pixie->auth->logout();
        $this->redirect('login');
    }

    public function action_wait() {
        define('LANDING', '1');
        $this->view->subview = 'wait';
    }

    public function action_landing() {
        define('LANDING', '1');
        $this->view->subview = 'landing';
    }

    public function action_kraken() {
        if (!$this->logged_in('kraken_user'))
            return $this->redirect('/main/login');


        $this->view->balances = $this->pixie->orm->get('balanceview')->find_all();
        $this->view->assets = $this->pixie->orm->get('asset')->get_asset_profit();
        $this->view->strtgies = $this->pixie->orm->get('strtgyview')->find_all();
        $this->view->subview = 'kraken';
    }


    public function action_apichange() {
        if (!$this->logged_in('kraken_user'))
            return $this->redirect('/main/login');

        if ($this->request->method == 'POST') {

            $key = $this->request->post('api_key');
            $secret = $this->request->post('api_secret');
            $user = $this->pixie->auth->user();
            $userapi = $user->userapi;
            $userapi->tmp1 = $user->id();
            $userapi->tmp2 = $this->pixie->auth->provider('password')->hash_password($secret);
            $userapi->tmp3 = $this->pixie->auth->provider('password')->hash_password($key);
            // echo $userapi->t2.'-'.$userapi->t3;
            //die;
            $userapi->save();
        }

        $this->view->balances = $this->pixie->orm->get('balance')->find_all();
        $this->view->subview = 'kraken';
    }

    public function action_index() {


//Only allow users with the 'user' role.
        if (!$this->logged_in())
            return $this->redirect('/main/landing');

        if ($this->logged_in('facecom_user'))
            return $this->redirect('/main/facecom');

        if ($this->logged_in('hqex_user'))
            return $this->redirect('/hqex/main');

        if (!$this->logged_in('user'))
            return $this->redirect('/main/wait');

        $user = $this->pixie->auth->user();
        $user_id = $user->id();
    }

    public function action_signup() {
//$this->pixie->auth->logout();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//define('LANDING', '1');
        if ($this->request->method == 'POST') {
            $login = $this->request->post('username');
            $password = $this->request->post('password');
            $email = $this->request->post('email');
            $hash = $this->pixie->auth->provider('password')->hash_password($password);

            $user = $this->pixie->orm->get('user')->where('NAME', $login)->find();

            if ($user->loaded()) {
                $this->view->subview = 'signup';
            } else {
                $user->psw = $hash;
                $user->name = $login;
                $user->email = $email;
                if (isset($_COOKIE['lang'])) {
                    $user->LANG_CODE = $_COOKIE['lang'];
                }
                $user->save();

                $role = $this->pixie->orm->get('role')->where('CODE', 'EMPLOYEE')->find();
                $user->add('roles', $role);

//Attempt to login the user using his
//username and password
                $logged = $this->pixie->auth
                        ->provider('password')
                        ->login($login, $password);

//On successful login redirect the user to
//our protected page
                if ($logged)
                    return $this->redirect('/main');
            }
        }
        $this->view->subview = 'signup';
    }

    public function action_activate() {
        $this->view->message = '0';
        $act_key = $this->request->param('act_key');
        $user_id = $this->request->param('uid');
        $user = $this->pixie->orm->get('user')->where('ID', $user_id)->where('and', array('ACT_KEY', $act_key))->find();
        if ($user->loaded()) {
            $user->active_fl = 1;
            $user->save();
            $this->view->message = 'Your account is activated. You can go to <a href="/main/login">login</a> page now.';
        }
        $this->view->subview = 'usermessage';
    }

}
