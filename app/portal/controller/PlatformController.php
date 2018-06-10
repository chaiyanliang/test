<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;
use app\portal\model\AuctionProjectModel;


class PlatformController extends HomeBaseController
{
    public function index()
    {
        
        $AuctionProjectModel=new AuctionProjectModel();
        $ali = $AuctionProjectModel->alias('a')
        ->where('a.platform_id=1')
        ->count();
        
        $jd = $AuctionProjectModel->alias('a')
        ->where('a.platform_id=2')
        ->count();
        
        $gongpai = $AuctionProjectModel->alias('a')
        ->where('a.platform_id=3')
        ->count();
        
        $zhongpai = $AuctionProjectModel->alias('a')
        ->where('a.platform_id=4')
        ->count();
        
        $renmin = $AuctionProjectModel->alias('a')
        ->where('a.platform_id=5')
        ->count();
        
        $qita = $AuctionProjectModel->alias('a')
        ->where('a.platform_id=6')
        ->count();
        
        $this->assign('ali', $ali);
        $this->assign('jd', $jd);
        $this->assign('gongpai', $gongpai);
        $this->assign('zhongpai', $zhongpai);
        $this->assign('renmin', $renmin);
        $this->assign('qita', $qita);
        return $this->fetch("/platform");
    }
}
