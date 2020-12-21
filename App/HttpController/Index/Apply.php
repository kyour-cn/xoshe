<?php declare(strict_types=1);
namespace App\HttpController\Index;

use App\Common\Utils;
use App\Model\User;
use Gregwar\Image\Image;
use App\Model\Apply as ApplyModel;

class Apply extends BaseAuth
{

    //验证参数
    public $authRule = [
        ['login' => 'user']
    ];

    //创建话题申请
    public function createClass()
    {
        $sess = $this->session('user');
        $post = $this->input();

        $type = 10; //话题申请ID

        if(ApplyModel::create()->where('uid', $sess['id'])->where('status', 0)->count() > 0){
            return $this->withData(1,'您还有未审核的申请，请等待上次审核完成再提交。');
        }

        //验证提交数据合理性
        if(empty($post['avatar']) or empty($post['className']) or empty($post['content'])) return $this->withData(1,'参数错误');

        $fName = EASYSWOOLE_ROOT. '/Temp/avatar_'.md5((string) time());

        $ret = Utils::base64Image($post['avatar'], $fName);
        if($ret){
            $fName2 = $fName.'.tmp';

            //缩放
            Image::open($fName)
                ->resize(200, 200)
                ->save($fName2, 'png', 80);

            //取出图片资源
            $base64 = Utils::imageBase64($fName2);

            //删除本地文件
            @unlink($fName);
            @unlink($fName2);

            $data = [
                'uid' => $sess['id'],
                'data' => [
                    'content' => $post['content'],
                    'avatar' => $base64,
                    'name' => $post['className'],
                    'pid' => $post['pid']
                ],
                'title' => '分类创建:'.$post['className'],
                'type' => $type,
                'content' => $post['content']
            ];
            $check = ApplyModel::create($data)->save();
            if($check) {
                return $this->withData(0, '上传成功');
            }
        }
        $this->withData(1, '发生错误');
    }

}
