<?php
namespace app\login\controller;


use think\Controller;


class Login extends Controller
{
	public function index()
    {
		// $aa='001014';
		// $bb=md5(md5($aa).'Login');
		// echo $bb;exit;
        return $this->fetch();

    }
    public function zhuce()
    {
        return $this->fetch();

    }
    public function adduser()
    {
        $data=input('post.');
		$name=input('post.name');
		$password=md5(md5(input('post.password')).'Login');
        $db=db('usergou')->where("name='".$data['name']."'")->find();
        if($db){
            $this->success('已有该用户','login/index');
        }else{
            $info=db('usergou')->insert($data);
            if($info){
                $this->success('注册成功','login/index');
            }
        }

    }
    public function do_login()
    {
        $data=input('post.');
        $db=db('usergou')->where("name='".$data['name']."'")->find();
        if($db){
            if(md5(md5($data['password']).'Login')==$db['password']){
                $this->success('登录成功','login/welcome');
            }else{
                $this->success('密码错误','login/index');
            }

        }else{
            $this->success('没有此用户，请注册后登陆','login/zhuce');
        }


    }
	public function welcome()
	{
		return $this->fetch();
	}
	
}
?>