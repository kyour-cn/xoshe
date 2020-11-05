<?php
namespace App\HttpController\Index;

use App\Common\Utils;
use App\Model\User;
use App\Model\Article;
use App\Model\Log;
use Gregwar\Image\Image;

/**
 * 上传发布相关
 */
class Release extends BaseAuth
{

    //验证参数
    public $authRule = [
        ['login' => 'user']
    ];

    public function formHandle()
    {
        $request = $this->request();
        $files = $request->getUploadedFiles();

        $sess = $this->session('user');
        $post = $this->input('post');

        if(!$sess){
            return $this->withData(101, '请先登录');
        }

        //储存的上传文件列表
        $fileItem = [];
        $file_err = false;

        //图像最大分辨率
        $msize = 1080;

        if($post['type'] == 'img'){
            //验证文件大小
            foreach($files as $name => $f){
                if($f->getSize() > 3 * 1024 * 1024){
                    return $this->withData(1,'单个文件不能超过3M');
                }
            }
            if(count($files) > 9){
                return $this->withData(1,'系统错误');
            }

            foreach($files as $f){
                echo "上传文件：".$f->getTempName()." 大小：".$f->getSize()." 类型：".$f->getClientMediaType()."\n";

                $tmpName = $f->getTempName();
                $size = getimagesize($tmpName);
                $fname = EASYSWOOLE_ROOT.'/Temp/'.md5($tmpName);

                if($size[0] > $msize or $size[1] > $msize){
                    //计算宽高比压缩
                    if($size[0] > $size[1]){
                        $size = [$msize, intval($msize * ($size[1] /$size[0]))];
                    }else{
                        $size = [intval($msize * ($size[0] /$size[1])), $msize];
                    }
                    Image::open($tmpName)
                        ->resize($size[0], $size[1])
                        ->save($fname, 'jpg', 80);
                }else{
                    $fname = $tmpName;
                }

                $f_path = 'posts/i/'.date('ymd').'/'.md5($tmpName.rand(1000,9999)).'.jpg';
                list($err, $ret) = Utils::saveFile($fname, $f_path, 'oss');

                if($ret){
                    $fileItem[] = $f_path;
                }else{
                    $file_err = true;
                }

                //删除文件
                unlink($fname);
            }
        }elseif($post['type'] == 'video'){

            if(isset($files['thumb']) and isset($files['video'])){

                //预览图上传
                $tmpName = $files['thumb']->getTempName();
                $f_path = 'posts/v/'.date('ymd').'/'.md5($tmpName.rand(1000,9999)).'.jpg';
                list($err, $ret) = Utils::saveFile($tmpName, $f_path, 'oss');

                if($ret){
                    $fileItem[] = $f_path;
                }else{
                    $file_err = true;
                }

                //视频上传
                $tmpName = $files['video']->getTempName();
                $f_path = 'posts/v/'.date('ymd').'/'.md5($tmpName.rand(1000,9999)).'.mp4';
                list($err, $ret) = Utils::saveFile($tmpName, $f_path, 'oss');

                if($ret){
                    $fileItem[] = $f_path;
                }else{
                    $file_err = true;
                }

            }else{
                echo "没有文件\n";
            }
        }

        //验证文件保存情况
        if($file_err){
            //记录日志
            Log::create([
                'type' => 'release',
                'data' => json_encode($fileItem)
            ])->save();
            return $this->withData(0, '文件上传发生错误', []);
        }

        //开始保存帖子
        $check = Article::create([
            'uid' => $sess['id'],
            'title' => $post['content'],
            'content' => $post['content'],
            'type' => $post['type'],
            'resource' => implode(',',$fileItem),
            'class' => $post['class']
        ])->save();

        if($check){
            $this->withData(0, '发布成功', [
                'id' => $check
            ]);
        }else{
            //记录日志
            Log::create([
                'type' => 'release',
                'data' => json_encode($fileItem),
                'content' => '发布出错'
            ])->save();
            return $this->withData(0, '帖子保存出错');
        }

    }

}
