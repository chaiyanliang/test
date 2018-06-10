<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: chong
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;
use app\portal\service\CourtService;
use app\portal\model\AuctionProjectModel;
use think\Db;

class ListController extends HomeBaseController
{

    public function index()
    {
        $id = $this->request->param('id', 0, 'intval');
        $type = $this->request->param('type', 1, 'intval');
        $portalCategoryModel = new PortalCategoryModel();
        
        $category = $portalCategoryModel->where('id', $id)
            ->where('status', 1)
            ->find();
        $categoryList = $portalCategoryModel->select();
        
        $listTpl = empty($category['list_tpl']) ? 'list' : $category['list_tpl'];
        
        $CourtService = new CourtService();
        $courtList = $CourtService->adminCourtList('');
        $this->assign('courts', $courtList->items());
        $this->assign('provinces', $CourtService->getProvince());
        $city = $CourtService->getCity(1);
        $districts = $CourtService->getDistrict(1);
        $this->assign('citys', $city);
        $this->assign('districts', $districts);
        $result = Db::query('call au_getList()');        
        $new = array();
        foreach ($result[0] as $val){
            $new[] = $val['id'];
        }      
        $where = [
            'post.create_time' => [
                'egt',
                0
            ],
            'post.id'=>['in',$new]
        ];       
        
        
        $page = [
            'list_rows' => 10,
            'next' => '下一页',
            'prev' => '上一页'
        ];
        
        $order='category_id asc,start_time asc';
       
        $this->assign('sort', 3);
        $this->assign('sortType', 'down');
        $this->assign('order', $order);       
        $this->assign('type', $type);
        $this->assign('where', $where);
        $this->assign('page', $page);       
        $this->assign('category', $category);
        $this->assign('categoryList', $categoryList); 
        return $this->fetch('/' . $listTpl);
    }
    
    public function GetList()
    {
       $model=new AuctionProjectModel();
       $where = [
           'post.create_time' => ['egt',0],
           'category_id'=>['eq',1]
       ];
       $join = [
           ['__PORTAL_CATEGORY_POST__ p', 'a.id = p.post_id']
       ];
       $field = 'a.*,p.*';
       $articles        = $model->alias('a')->field($field)
       ->join($join)
       ->where($where)
       ->order('start_time', 'asc');
       
       Db::field('name')
       ->table('think_user_0')
       ->union('SELECT name FROM think_user_1',true)
       ->union('SELECT name FROM think_user_2',true)
       ->select();
       
    }

    public function GetCity($province)
    {
        $CourtService = new CourtService();
        $city = $CourtService->getCity($province);
        return $city;
    }
    
    public function GetDistrict($city)
    {
    	$CourtService = new CourtService();
    	$district = $CourtService->getDistrict($city);
    	return $district;
    }
    
    
    public function Search()
    {
    	$id = $this->request->param('id', 0, 'intval');
    	$portalCategoryModel = new PortalCategoryModel();
    	
    	$category = $portalCategoryModel->where('id', $id)
    	->where('status', 1)
    	->find();
    	$categoryList = $portalCategoryModel->select();
    	
    	$listTpl = empty($category['list_tpl']) ? 'list' : $category['list_tpl'];
    	
    	// keys data finding
    	
    	$CourtService = new CourtService();
    	$courtList = $CourtService->adminCourtList('');
    	$this->assign('courts', $courtList->items());
    	$this->assign('provinces', $CourtService->getProvince());
    	$city = $CourtService->getCity(1);
    	$districts = $CourtService->getDistrict(1);
    	$this->assign('citys', $city);
    	$this->assign('districts', $districts);
    	
    	// parameters start here
    	
    	$data   = $this->request->param();
    	$param   = $data['post'];
    	
    	$categoryId = $param['category'];
    	$cityId = $param['city'];
    	$pid = $param['province'];
    	$status = $param['status'];
    	$price1 = $param['startprice1'];
    	$price2 = $param['startprice2'];
    	$courtId = $param['court'];
    	$step = $param['step'];
    	$type=$param['type'];
    	$Sort=$param['sort'];
    	$SortType=$param['sortType'];
    	$where = [
    			'post.create_time' => [
    					'egt',
    					0
    			]
    	];
    	
    	if (! empty($pid)) {
    	    $where['post.province'] = [
    	        'eq',
    	        $pid
    	    ];
    	}
    	
    	if (! empty($cityId)) {
    		$where['post.city'] = [
    				'eq',
    				$cityId
    		];
    	}
    	if (! empty($status)) {
    		$where['post.status'] = [
    				'eq',
    				$status
    		];
    	}
    	if (! empty($price1)) {
    		$where['post.start_price'] = [
    				'egt',
    		    $price1
    		];
    	}
    	if (! empty($price2)) {
    	    $where['post.start_price'] = [
    	        'elt',
    	        $price2
    	    ];
    	}
    	if (! empty($courtId)) {
    		$where['post.courtid'] = [
    				'eq',
    				$courtId
    		];
    	}
    	if (! empty($step)) {
    		$where['post.auction_step'] = [
    				'eq',
    				$step
    		];
    	}
    	if (! empty($categoryId)) {
    		$where['category_post.category_id'] = [
    				'eq',
    				$categoryId
    		];
    	}
    	
    	$page = [
    			'list_rows' => 12,
    			'next' => '下一页',
    			'prev' => '上一页'
    	];    	
    	
    	$order='start_time asc'; 
    	$this->assign('sort', $Sort);
    	$this->assign('sortType', $SortType);
    	$this->assign('order', $order);
    	$this->assign('type', $type);
    	$this->assign('where', $where);
    	$this->assign('page', $page);
    	$this->assign('category', $category);
    	$this->assign('categoryList', $categoryList);
    	return $this->fetch('/' . $listTpl);
    }
    

    public function Search2()
    {
        $id = $this->request->param('id', 0, 'intval');
        $portalCategoryModel = new PortalCategoryModel();
        
        $category = $portalCategoryModel->where('id', $id)
            ->where('status', 1)
            ->find();
        $categoryList = $portalCategoryModel->select();
        
        $listTpl = empty($category['list_tpl']) ? 'list' : $category['list_tpl'];
        
        // keys data finding
        
        $CourtService = new CourtService();
        $courtList = $CourtService->adminCourtList('');
        $this->assign('courts', $courtList->items());
        $this->assign('provinces', $CourtService->getProvince());
        $city = $CourtService->getCity(1);
        $districts = $CourtService->getDistrict(1);
        $this->assign('citys', $city);
        $this->assign('districts', $districts);
        
        // parameters start here        
       
        $data   = $this->request->param();
        $param   = $data['post'];
        
        $categoryId = $param['category'];
       
        $key = $param['key'];
        
        $where = [
            'post.create_time' => [
                'egt',
                0
            ]
        ];
        
        
        if (! empty($key)) {
            $where['post.post_keywords'] = [
                'like',
                $key
            ];
        }
        if (! empty($categoryId) && $categoryId != 0) {
            $where['category_post.category_id'] = [
                'eq',
                $categoryId
            ];
        }
        
        $page = [
            'list_rows' => 12,
            'next' => '下一页',
            'prev' => '上一页'
        ];
        $order='start_time asc';
        $this->assign('sort', 3);
        $this->assign('sortType', "down");
        $this->assign('order', $order);
        $this->assign('type', 0);
        $this->assign('key', $key);
        $this->assign('where', $where);
        $this->assign('page', $page);
        $this->assign('category', $category);
        $this->assign('categoryList', $categoryList);
        return $this->fetch('/' . $listTpl);
    }
}
