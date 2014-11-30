<?php
/*
**Author:tianling
**createTime:14-11-30 上午3:13
*/

class UserAccessController extends BaseController{

    public function register(){

        $mobile = Input::get('mobile');
        $email = Input::get('email');

        $password = Input::get('password');
        //对密码进行hash加密
        $password = Hash::make($password);

        $user = new User;
        $user->password = $password;
        $user->last_login_time = time();
        $user->last_login_ip = $this->getIP();
        $user->lock = 0;
        $user->user_type = 'business';
        $user->add_time = time();


        if($user->save()){
            $uid = $user->id;
        }else{
            echo "user base Error";
            exit;
        }

        $Buser = new BUser();
        $Buser->uid = $uid;
        $Buser->email = $email;
        $Buser->mobile = $mobile;
        $Buser->email_passed = 0;
        $Buser->mobile_passed = 1;

        if($Buser->save()){
            echo "ok";
        }



    }


    //登录接口
    public function login(){

        $account = Input::get('account');
        $password = Input::get('password');

        $accountCheck = $this->accountCheck($account);
        if(!is_object($accountCheck)){
            echo json_encode(array(
                'status'=>400,
                'msg'=>'账户不存在'
            ));

            exit();
        }

        $passwordCheck = Hash::check($password,$accountCheck->user->password);

        if($passwordCheck){
            Auth::login($accountCheck);
        }

        var_dump(Auth::user()->email);


    }

    //账号查询,支持邮箱和手机
    private function accountCheck($account){

        $accountData = BUser::where('email' ,'=', $account)->orWhere('mobile','=',$account)->first();


        if(!$accountData){
            return 400;//若账户不存在，返回错误码400
        }else{
            return $accountData;
        }
    }


    //获取客户端ip地址
    private function getIP(){
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif(!empty($_SERVER["REMOTE_ADDR"])){
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        else{
            $cip = "无法获取！";
        }
        return $cip;
    }
}

