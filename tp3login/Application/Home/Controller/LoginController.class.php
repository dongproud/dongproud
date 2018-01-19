<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        print_r(cookie());
        print_r($_COOKIE);
       // exit;
        if($_COOKIE['username']!='')
        {
            session('username',$_COOKIE['username']);
        }
        if(session('username')=='')
        {
            $this->display('index');
        }else{
            $this->redirect('Index/welcome');
        }


    }
    public function do_login()
    {
//        echo "<pre>";
//        print_r(I('post.'));
        $username=I('post.username');
        $password=md5(md5(I('post.password')).'Login');
        $info=M('users')->where("idcate='".$username."'")->find();
        if(!empty($info))
        {
            if($info['password']==$password)
            {
                session('username',$username);
                session('userid',$info['id']);
                if(I('post.ischecks'))
                {
                   // C('COOKIE_EXPIRE',time()+864000);
                    cookie('username',$username,864000);
                    //setcookie('username',$username,864000);
                }
                //$_SESSION['']
                $datas=[
                    'msg'=>'登录成功',
                    'status'=>1
                ];
                $this->ajaxReturn($datas);
               // $this->success('登录成功','welcome');
            }
            else{
                $datas=[
                    'msg'=>'密码错误',
                    'status'=>0
                ];
                $this->ajaxReturn($datas);
                //$this->error('密码错误！');
            }
        }
        else{
            $datas=[
                'msg'=>'用户名错误',
                'status'=>0
            ];
            $this->ajaxReturn($datas);
            //$this->error('用户名错误！');
        }

    }

}