<?php declare(strict_types=1);
/**
 * 管理后台
 */
namespace App\HttpController\Admin;

use App\Model\Setting;
// use App\Common\Utils;
use App\Model\User as UserModel;
use EasySwoole\Validate\Validate;

class Index extends BaseAuth
{

    //默认首页
    public function index()
    {
        if(!$this->checkAuth([['uri' => 'admin']])){
            return $this->response()->redirect("/admin/login");
        }
        $sess = $this->session('user');

        $site = Setting::fetch('site');

        //读取静态文件返回
        $this->fetch('admin/index', [
            'site' => $site,
            'user' => $sess
        ]);
    }

    //获取前端配置
    public function config()
    {
        $site = Setting::fetch('site');
        //读取静态文件返回
        $this->fetch('admin/config', ['site' => $site]);
    }

    // 登录接口
    public function loginApi()
    {
        $post = $this->input();

        $valitor = new Validate();
        $valitor->addColumn('username')
            ->required('账号不能为空')
            ->integer('账号必须为整数')
            ->lengthMin(5, '最小长度不小于5位')
            ->lengthMax(11, '最大长度不大于11位');

        if(!$valitor->validate($post)){
            $msg = $valitor->getError()->__toString();
            return $this->withData(1, $msg, $post);
        }

        if($post['imgcode'] != $this->session('img_code')){
            return $this->withData(1, '图形验证码不正确', $post);
        }

        //查询登录
        $check = UserModel::create()
            ->where([
                'num'   => [$post['username'], '='],
                'phone' => [$post['username'], '=', 'or']
            ])
            // ->field('id,nickname,num,phone,avatar,register_time,credit,level,sign,info,status')
            ->get([
                'password' => md5($post['password'])
            ]);

        if($check){
            //登录成功
            $user = $check->toArray();
            //避免暴露密码风险
            unset($user['password']);

            // 保存session
            $this->session('user', $user);

            $token = $this->sessionId();
            UserModel::create()->update(['token' => ''],['token' => $token]);

            //更新登录时间
            $check->update([
                'token' => $token,
                'login_time' => time()
            ]);

            return $this->withData(0, '登录成功');
        }else{
            return $this->withData(1, '账号或密码不正确');
        }
    }

}
