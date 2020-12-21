<?php declare(strict_types=1);
namespace App\HttpController\Index;

use App\HttpController\BaseController;
use App\Model\Identity;

class BaseAuth extends BaseController
{
    //验证参数
    public $authRule = [];

    function onRequest(?string $action): ?bool
    {
        if(parent::onRequest($action)){
            //鉴权
            $check = $this->checkAuth($this->authRule);
            if(!$check){
                $this->withData(102, '访问权限不足');
            }
            return $check;
        }
        return false;
    }

    //权限验证
    protected function checkAuth($rule): bool
    {
        //判断
        if($rule){
            foreach($rule as $v){

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
                        //登录身份权限规则验证
                        case 'uri':
                            $sess = $this->session('user');
                            if(empty($sess)){
                                $check = false;
                            }else{
                                $check = Identity::check($val, $sess['id']);
                            }
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
