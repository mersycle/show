<?php
namespace Home\Controller;
use Think\Controller;
class DoController extends Controller {
    public function index(){
        $this->show('helloworld');
    }
}