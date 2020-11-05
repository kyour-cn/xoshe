<?php
namespace App\HttpController\Index;

use App\Common\Utils;
use App\Model\User;
use App\Model\Article as ArticleModel;

class Article extends BaseAuth
{

    public function test()
    {
        $sess = $this->session('user');
        $get = $this->input('get');

        $where = [];
        // if(!empty($get['name'])){
        //     $where['name'] = ['%'.$get['name'].'%', 'like'];
        // }

        $user = User::create()
            ->order('RAND()')
            ->all($where);

        $this->withData(0, 'success', ['list' => $user,'session' => $sess]);

    }

    public function info()
    {
        $article = ArticleModel::create()->get();

        $article->user;

        $this->withData(0, 'success', ['info' => $article]);

    }

}
