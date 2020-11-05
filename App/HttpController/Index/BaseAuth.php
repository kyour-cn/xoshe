<?php
namespace App\HttpController\Index;

use App\HttpController\BaseController;

class BaseAuth extends BaseController
{
    //验证参数
    public $authRule = [];

    function onRequest(?string $action): ?bool
    {
        if(parent::onRequest($action)){
            //鉴权
            $check = $this->checkAuth();
            if(!$check){
                $this->withData(102, '访问权限不足');
            }
            return $check;
        }
        return false;
    }

    //权限验证
    private function checkAuth(): bool
    {

        //判断
        if($this->authRule){
            foreach($this->authRule as $v){

                $check = true;
                //验证二级数组的所有，只要一个不通过，直接返回false
                foreach($v as $item => $val){
                    switch ($item) {
                        //用户登录验证
                        case 'login':
                            if(empty($this->session('user'))){
                                $check = false;
                            }
                            break;
                        //管理员用户验证
                        case 'admin':
                            // if(empty($this->session('user'))){
                            //     $check = false;
                            // }
                            break;
                    }
                }
                //验证通过
                if($check) return true;
            }
            //验证完还没有正确的
            return false;
        }else{
            return true;
        }

    }

}