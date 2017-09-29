<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    protected function init($db){
        $redis = new \Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->select(0);
        return $redis;
    }
    protected function checkUser(){
        if(empty($_SESSION[C("SESS_ACCOUNT")])){
            $this->error("请登录","/home/index/login"); 
        }
    }
    public function index(){
        $this->checkUser();
        $world = "aaa";
        $this->assign("account",$_SESSION[C("SESS_ACCOUNT")]);
        $this->assign('world',$world);
        $this->display();
    }
    public function reg(){
        if(!empty($_SESSION[C("SESS_ACCOUNT")])){
            $this->success("你已经登录了","/home/index/index");
            
        }else{
            if(empty($_POST)){
                $this->display();
            }else{
                $data["account"] = I("post.account");
                $data["pass"] = I("post.pass");
                if(empty($data["account"])){
                    $this->error("账户不能为空");
                }elseif(empty($data["pass"])){
                    $this->error("密码不能为空");
                }
                $redis = $this->init(0);

                $info = $redis->get($data["account"]);

                if(!$info){
                    $res = M("User")->add($data);
                    if($res){
                        $uid = M("User")->getLastInsID();
                        $resa = $redis->set($data["account"],$uid);
                    }
                }
                $redis->close();
                if(empty($resa)){
                    $this->error("注册失败");
                }else{
                    $this->success("注册成功","/home/index/login");
                }
            }
        }
        
        
    }
    
    public function login(){
//       echo __Public__;DIE;
        if(!empty($_SESSION[C("SESS_ACCOUNT")])){
            $this->success("你已经登录了","/home/index/index");
        }else{
            if(empty($_POST)){
                $this->display();
            }else{
                $data["account"] = I("post.account");
                $data["pass"] = I("post.pass");
                if(empty($data["account"])){
                    $this->error("账户不能为空");
                }elseif(empty($data["pass"])){
                    $this->error("密码不能为空");
                }
                $redis = $this->init(0);
                $uid = $redis->get($data["account"]);
                $redis->close();
                if($uid){
                    $info = M("User")->where(array("id"=>$uid))->field("pass")->find();
                    if($info["pass"] == $data["pass"]){
                        $_SESSION[C("SESS_ACCOUNT")] = $data["account"];
                        $_SESSION[C("SESS_UID")] = $uid;

                        $this->success("登录成功","/home/index/index");
                    }else{
                        $this->error("密码错误");
                    }  
                }else{
                    $this->error("用户不存在");
                }
            }
        }
    }
    
    public function logout(){
        if(isset($_SESSION[C("SESS_ACCOUNT")])){
            unset($_SESSION[C("SESS_ACCOUNT")]);
            unset($_SESSION[C("SESS_UID")]);
            session('[destroy]');
            $this->success("退出成功","/home/index/login");
        }else{
            $this->success("已经退出","/home/index/login");
        }
    }
   
}