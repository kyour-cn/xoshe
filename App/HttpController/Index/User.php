<?php declare(strict_types=1);
/**
 * 用户相关操作控制器
 */
namespace App\HttpController\Index;

use App\Common\Utils;
use App\Model\User as UserModel;
use EasySwoole\Validate\Validate;
use Gregwar\Image\Image;

class User extends BaseAuth
{
    public $authRule = [];

    //获取用户信息
    public function getInfo()
    {
        $sess = $this->session('user');
        if(!$sess){
            return $this->withData(101, '未登录');
        }
        return  $this->withData(0, 'success',['info' => $sess]);
    }

    //刷新session数据
    public function updateSession()
    {
        $sess = $this->session('user');
        //查询登录
        $check = UserModel::create()->get($sess['id']);
        if(!$check) return false;

        $data = $check->toArray();
        //避免暴露密码风险
        unset($data['password']);
        $this->session('user', $data);

        return true;
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

        //查询登录
        $check = UserModel::create()
        ->where([
            'num'   => [$post['username'], '='],
            'phone' => [$post['username'], '=', 'or'],
            'password' => md5($post['password'])
        ])
        // ->field('id,nickname,num,phone,avatar,register_time,credit,level,sign,info,status')
        ->get();
        if($check){
            //登录成功
            $user = $check->toArray();
            //避免暴露密码风险
            unset($user['password']);

            // 保存session
            $token = $this->sessionId();
            $user['token'] = $token;
            $this->session('user', $user);

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

    // 注册接口
    public function registApi()
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

        //查询是否已注册
        $check = UserModel::create()
        ->get([
            'phone' => $post['username']
        ]);
        if($check){
            //已注册
            return $this->withData(1, '该手机号已注册');
        }

        //查出用户号最大值
        $last_u = UserModel::create()
        ->order('num', 'DESC')
        ->get();
        $last_num = $last_u['num']+ 1;

        $add = UserModel::create([
            'phone' => $post['username'],
            'num' => $last_num,
            'nickname' => '用户_'. $last_num,
            'password' => md5($post['password']),
            'avatar' => 'avatar/default.png',
            'register_time' => time(),
            'sign' => '该用户没有签名',
            'info' => '{"fans": 0, "star": 0, "posts": 0, "follow": 1, "appreciate": 0}'
        ])
        ->save();

        if($add){
            return $this->withData(0, '注册成功');
        }else{
            return $this->withData(1, '账号或密码不正确');
        }
    }

    //修改资料
    public function updateInfo()
    {
        $sess = $this->session('user');
        $post = $this->input();

        $check_name = UserModel::create()->get(['nickname' => $post['nickname']]);
        if($check_name and $sess['id'] != $check_name['id']){
            return $this->withData(1, '该昵称已存在，请换个别的');
        }

        //修改昵称检测
        if($check_name['nickname'] != $post['nickname'] and mb_substr($post['nickname'], 0, 3) == '用户_'){
            return $this->withData(1, '昵称不能以 "用户_" 开头');
        }

        $check = UserModel::create()
        ->update($post, ['id' => $sess['id']]);

        $this->updateSession();
        $this->withData($check?0:1, $check?'修改成功':'发生错误');
    }

    //上传头像
    public function upAvatar()
    {
        $sess = $this->session('user');
        if(!$sess) return $this->withData(101,'暂未登录');
        $post = $this->input();

        if(empty($post['avatar'])) return $this->withData(1,'参数错误');

        $user = UserModel::create()->get($sess['id']);

        $fName = EASYSWOOLE_ROOT. '/Temp/avatar_'.md5((string) time());

        $ret = Utils::base64Image($post['avatar'], $fName);
        if($ret){

            $fName2 = $fName.'.tmp';

            //缩放
            Image::open($fName)
                ->resize(200, 200)
                ->save($fName2, 'png', 80);

            //资源完整路径
            $fPath = 'avatar/u/'.substr(md5((string) $user['id']), 0, 2)
                .'/'. date('mdHis_'). $user['id']. '.png';

            //开始上传
            list($err, $ret) = Utils::saveFile($fName2, $fPath);
            if(!$ret){
                return $this->withData(1, '图片保存失败');
            }

            //删除本地文件
            @unlink($fName);
            @unlink($fName2);

            //删除原文件
            Utils::delFile($user['avatar']);

            $check = UserModel::create()->update(['avatar' => $fPath], ['id'=>$sess['id']]);
            if($check) {
                $this->updateSession();
                return $this->withData(0, '上传成功');
            }
        }
        $this->withData(1, '发生错误');
    }

    // 注销登录
    public function logout()
    {
        $sess = $this->session('user');
        if($sess){
            $this->session('user', null);
            if(!empty($sess['id'])){
                UserModel::create()->update(['token' => ''],['id' => $sess['id']]);
            }
        }
        return $this->withData(0, '注销成功');
    }

}