<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | 法院管理
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\AdminBaseController;
use app\portal\model\AuctionSettledCourtModel;
use app\portal\service\CourtService;
use think\Db;
use app\admin\model\ThemeModel;

class AdminCourtController extends AdminBaseController
{
    /**
     * 法院列表
     * @adminMenu(
     *     'name'   => '法院管理',
     *     'parent' => 'portal/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '法院管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $param = $this->request->param();

       
        $CourtService = new CourtService();
       
        $data        = $CourtService->adminCourtList($param);

        $data->appends($param);
        
        $this->assign('courts', $data->items());
        $this->assign('provinces', $CourtService->getProvince());
        $city = $CourtService->getCity(1);
        $districts = $CourtService->getDistrict(1);
        $this->assign('citys', $city);
        $this->assign('districts', $districts);
        $this->assign('page', $data->render());
        return $this->fetch();
    }

    /**
     * 添加法院
     * @adminMenu(
     *     'name'   => '添加法院',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $themeModel        = new ThemeModel();
        $CourtService = new CourtService();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Court/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('provinces', $CourtService->getProvince());
        $city = $CourtService->getCity(1);
        $districts = $CourtService->getDistrict(1);
        $this->assign('citys', $city);
        $this->assign('districts', $districts);
        
        

        return $this->fetch();
    }

    /**
     * 添加文章提交
     * @adminMenu(
     *     'name'   => '添加文章提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $post   = $data['post'];
           

            $AuctionSettledCourtModel = new AuctionSettledCourtModel();

            $AuctionSettledCourtModel->adminAddCourt($post);
            
            
            $findCourtCount = Db::name("auction_nationwide_court")->where([
            		'name'  => $post["name"]
            ])->count();
            
            if ($findCourtCount > 0) {
            	Db::name("auction_nationwide_court")->where([
            				'name'  => $post["name"] 
            			])->update(["state" => 1]);
            }else{
            	Db::name("auction_nationwide_court")->insert([
            			'name'  => $post["name"],
            			'province' => $post["province"],
            			'city' => $post["city"],
            			'district' => $post["district"],
            			'state' => 1
            	]);
            }
            
            

//             $data['post']['id'] = $AuctionSettledCourtModel->id;
//             $hookParam          = [
//                 'is_add'  => true,
//                 'court' => $data['post']
//             ];
//             hook('portal_admin_after_save_court', $hookParam);


            $this->success('添加成功!', url('AdminCourt/edit', ['id' => $AuctionSettledCourtModel->id]));
        }

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

    /**
     * 编辑文章
     * @adminMenu(
     *     'name'   => '编辑文章',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        $AuctionSettledCourtModel = new AuctionSettledCourtModel();
        $post            = $AuctionSettledCourtModel->where('id', $id)->find();
//         $postCategories  = $post->categories()->alias('a')->column('a.name', 'a.id');
//         $postCategoryIds = implode(',', array_keys($postCategories));

        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Court/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('post', $post);
        
        $CourtService = new CourtService();
        $this->assign('provinces', $CourtService->getProvince());
        $city = $CourtService->getCity($post['province']);
        $this->assign('citys', $city);
        $districts = $CourtService->getDistrict($post['city']);
        $this->assign('districts', $districts);
         
//         $CourtService = new CourtService();
//         $this->assign('provinces', $CourtService->getProvince());
//         $city = $CourtService->getCity(1);
//         $this->assign('citys', $city);
//         $this->assign('post_categories', $postCategories);
//         $this->assign('post_category_ids', $postCategoryIds);

        return $this->fetch();
    }

    /**
     * 编辑文章提交
     * @adminMenu(
     *     'name'   => '编辑文章提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $post   = $data['post'];
           
            $AuctionSettledCourtModel = new AuctionSettledCourtModel();

            
            $AuctionSettledCourtModel->adminEditArticle($data['post']);

            $hookParam = [
                'is_add'  => false,
                'court' => $data['post']
            ];
            hook('portal_admin_after_save_court', $hookParam);
            
            $findCourtCount = Db::name("auction_nationwide_court")->where([
            		'name'  => $post["name"]
            ])->count();
            
            if ($findCourtCount > 0) {
            	Db::name("auction_nationwide_court")->where([
            			'name'  => $post["name"]
            	])->update(["state" => 1]);
            }else{
            	Db::name("auction_nationwide_court")->insert([
            			'name'  => $post["name"],
            			'province' => $post["province"],
            			'city' => $post["city"],
            			'district' => $post["district"],
            			'state' => 1
            	]);
            }
            

            $this->success('保存成功!');

        }
    }

    /**
     * 文章删除
     * @adminMenu(
     *     'name'   => '文章删除',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章删除',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $param           = $this->request->param();
       

        $intId = $this->request->param("id", 0, 'intval');
        
        if (empty($intId)) {
        	$this->error(lang("NO_ID"));
        }
        $AuctionSettledCourtModel = new AuctionSettledCourtModel();
        
        $AuctionSettledCourtModel->where(['id' => $intId])->delete();
        
        $findCourtCount = Db::name("auction_nationwide_court")->where([
        		'name'  => $param["name"]
        ])->count();
        
        if ($findCourtCount > 0) {
        	Db::name("auction_nationwide_court")->where([
        			'name'  => $param["name"]
        	])->update(["state" => 0]);
        }
        
        $this->success(lang("DELETE_SUCCESS"));
    }

    /**
     * 文章发布
     * @adminMenu(
     *     'name'   => '文章发布',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章发布',
     *     'param'  => ''
     * )
     */
    public function publish()
    {
        $param           = $this->request->param();
        $AuctionSettledCourtModel = new AuctionSettledCourtModel();

        
        $AuctionSettledCourtModel->adminAddCourt($param);
        
        Db::name('auction_court_registration')->where([
        		'id'  => $param['id'],
        ])->update(["status" => 1]);

        $this->success("入驻成功，请到法院管理中完善法院信息", url('AdminCourt/edit', ['id' => $AuctionSettledCourtModel->id]));
    }

    /**
     * 文章置顶
     * @adminMenu(
     *     'name'   => '文章置顶',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章置顶',
     *     'param'  => ''
     * )
     */
    public function top()
    {
        $param           = $this->request->param();
        $AuctionSettledCourtModel = new AuctionSettledCourtModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $AuctionSettledCourtModel->where(['id' => ['in', $ids]])->update(['is_top' => 1]);

            $this->success("置顶成功！", '');

        }

        if (isset($_POST['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $AuctionSettledCourtModel->where(['id' => ['in', $ids]])->update(['is_top' => 0]);

            $this->success("取消置顶成功！", '');
        }
    }

    /**
     * 文章推荐
     * @adminMenu(
     *     'name'   => '文章推荐',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章推荐',
     *     'param'  => ''
     * )
     */
    public function recommend()
    {
        $param           = $this->request->param();
        $AuctionSettledCourtModel = new AuctionSettledCourtModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $AuctionSettledCourtModel->where(['id' => ['in', $ids]])->update(['recommended' => 1]);

            $this->success("推荐成功！", '');

        }
        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $AuctionSettledCourtModel->where(['id' => ['in', $ids]])->update(['recommended' => 0]);

            $this->success("取消推荐成功！", '');

        }
    }

    /**
     * 文章排序
     * @adminMenu(
     *     'name'   => '文章排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        parent::listOrders(Db::name('portal_category_post'));
        $this->success("排序更新成功！", '');
    }

    public function move()
    {

    }

    public function copy()
    {

    }


}
