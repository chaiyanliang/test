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


class IndexController extends HomeBaseController
{
    public function index()
    {
    	$portalCategoryModel = new PortalCategoryModel();
    	$categoryList = $portalCategoryModel->select();
    	$this->assign('categoryList', $categoryList);
    	
    	$whereNew = [
    	    'a.start_time' => ['>=', date('Y-m')]
    	];
    	
    	$whereDeal = [
    	    'a.deal_time' => ['>=', date('Y-m')]
    	];
    	
    	$where = [
    	    'a.start_time' => ['>=', date('Y-m')]
    	];
    	
    	 
    	$whereing = [    	    
    	    'post.status'=>['=',1]    	    
    	];    	
    	
    	$date=date('Y-m-d');
    	$date_week = date('Y-m-d',strtotime("$date + 1 week")); 
    	$whereSoon = [
    	    'post.start_time'=>['between',[$date,$date_week]],    	    
    	    'post.status'=>['=',0]
    	];    	
    	 
    	
    	
    	$AuctionProjectModel=new AuctionProjectModel();
    	
    	$new = $AuctionProjectModel->alias('a')
    	->where($whereNew)
    	->count();
    	
    	$deal = $AuctionProjectModel->alias('a')
    	->where($whereDeal)
    	->count();
    	
    	$overRate = $AuctionProjectModel->alias('a')
    	->where($where)
    	->count();
    	
    	$this->assign('whereing', $whereing);
    	$this->assign('whereSoon', $whereSoon);
    	$this->assign('new', $new);
    	$this->assign('deal', $deal);
    	$this->assign('rate', $overRate);    	
    	
        return $this->fetch(':index');
    }
}
