<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Home\Controller;
use Think\Controller;
class ShowController extends Controller {
    public function downLoad(){
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            header("Location:".C(ANFRIODHREF));
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            header("Location:".C(ANFRIODHREF));
        }else{
            echo 'systerm is other';
        }
    }
   
}
