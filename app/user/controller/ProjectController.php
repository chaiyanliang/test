<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\UserBaseController;
use app\user\model\AuctionMyProjectModel;
use app\user\service\UserService;
use think\Db;

class ProjectController extends UserBaseController
{

    /**
     * 个人中心我的收藏列表
     */
	public function index()
	{
		$param = $this->request->param();
		
		$user              = cmf_get_current_user();
		$userService = new UserService();
		
		$data        = $userService->userProjectList($param);
		
		$data->appends($param);
		$this->assign($user);
		$this->assign('lists', $data->items());
		$this->assign('page', $data->render());
		return $this->fetch();
	}

    /**
     * 用户取消收藏
     */
    public function delete()
    {
        $id                = $this->request->param("id", 0, "intval");
        $myProjectModel = new AuctionMyProjectModel();
        $data              = $myProjectModel->deleteProject($id);
        if ($data) {
            $this->success("取消拍品成功！");
        } else {
            $this->error("取消拍品失败！");
        }
    }

    /**
     * 用户收藏
     */
    public function add()
    {
    	$data   = $this->request->param();
    	$id    = $this->request->param('id', 0, 'intval');
    	
    	
    	$findFavoriteCount = Db::name("auction_my_project")->where([
    			'project_id'  => $id,
    			'user_id'    => cmf_get_current_user_id()
    	])->count();
    	
    	if ($findFavoriteCount > 0) {
    		$this->error("您已报过名啦");
    	}
    	
    	Db::name("auction_my_project")->insert([
    			'user_id'     => cmf_get_current_user_id(),
    			'project_id'       => $id,
    			'created_time' => time()
    	]);
    	
    	$this->success('报名成功');
    	
    }
}