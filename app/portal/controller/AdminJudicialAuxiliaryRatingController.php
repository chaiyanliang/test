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
use app\portal\model\AuctionJudicialAuxiliaryRatingModel;
use app\portal\service\JudicialAuxiliaryRatingService;
use think\Db;
use app\admin\model\ThemeModel;
use app\portal\service\CourtService;

class AdminJudicialAuxiliaryRatingController extends AdminBaseController
{
    
    public function index()
    {
        $param = $this->request->param();

       
        $judicialAuxiliaryRatingService = new JudicialAuxiliaryRatingService();
       
        $data        = $judicialAuxiliaryRatingService->allJudicialAuxiliaryRatingList($param);

        $data->appends($param);
        
        $this->assign('list', $data->items());
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
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/JudicialAuxiliaryRating/index');
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
           
            $JudicialAuxiliaryRatingModel = new AuctionJudicialAuxiliaryRatingModel();

            $JudicialAuxiliaryRatingModel->adminAddJudicialAuxiliaryRating($post);
            

            $this->success('添加成功!', url('AdminJudicialAuxiliaryRating/edit', ['id' => $JudicialAuxiliaryRatingModel->id]));
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

        $JudicialAuxiliaryRatingModel = new AuctionJudicialAuxiliaryRatingModel();
        $post            = $JudicialAuxiliaryRatingModel->where('id', $id)->find();

        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/JudicialAuxiliaryRating/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('post', $post);
        

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
           
            $JudicialAuxiliaryRatingModel = new AuctionJudicialAuxiliaryRatingModel();

            
            $JudicialAuxiliaryRatingModel->adminEditJudicialAuxiliaryRating($data['post']);

            $hookParam = [
                'is_add'  => false,
                'judicialAuxiliaryRating' => $data['post']
            ];
            hook('portal_admin_after_save_judicialAuxiliaryRating', $hookParam);
            
            

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
        $JudicialAuxiliaryRatingModel = new AuctionJudicialAuxiliaryRatingModel();
        
        $JudicialAuxiliaryRatingModel->where(['id' => $intId])->delete();

        $this->success(lang("DELETE_SUCCESS"));
    }
}
