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
use app\portal\service\JudicialAuxiliaryRatingService;
use think\Validate;
use think\Db;

class JaratingController extends HomeBaseController
{
    public function index()
    {
    	$param = $this->request->param();
    	
    	
    	$judicialAuxiliaryRatingService = new JudicialAuxiliaryRatingService();
    	
    	$data        = $judicialAuxiliaryRatingService->allJudicialAuxiliaryRatingList($param);
    	
    	$data->appends($param);
    	
    	$this->assign('list', $data->items());
    	$this->assign('page', $data->render());
    	
        return $this->fetch("/jaRating");
    }
}
