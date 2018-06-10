<?php
namespace app\portal\model;

use app\admin\model\RouteModel;
use think\Model;
use think\Db;

class AuctionCourtRegistrationModel extends Model
{
	
	protected $type = [
			'more' => 'array',
	];
	
	// 开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	
	/**
	 * 关联 user表
	 * @return $this
	 */
	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id')->setEagerlyType(1);
	}
	
	/**
	 * 关联城市表
	 */
	public function city()
	{
		return $this->belongsToMany('CityModel', 'city', 'cid', 'id');
	}
	
	
	/**
	 * post_content 自动转化
	 * @param $value
	 * @return string
	 */
	public function getPostContentAttr($value)
	{
		return cmf_replace_content_file_url(htmlspecialchars_decode($value));
	}
	
	/**
	 * post_content 自动转化
	 * @param $value
	 * @return string
	 */
	public function setPostContentAttr($value)
	{
		return htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($value), true));
	}
	
	/**
	 * published_time 自动完成
	 * @param $value
	 * @return false|int
	 */
	public function setPublishedTimeAttr($value)
	{
		return strtotime($value);
	}
	
	/**
	 * 后台管理添加文章
	 * @param array $data 文章数据
	 * @param array|string $categories 文章分类 id
	 * @return $this
	 */
	public function adminAddCourtRegistration($data)
	{
		$data['user_id'] = cmf_get_current_admin_id();
		
		// 		if (!empty($data['more']['thumbnail'])) {
		// 			$data['more']['thumbnail'] = cmf_asset_relative_url($data['more']['thumbnail']);
		// 		}
		
		$this->allowField(true)->data($data, true)->isUpdate(false)->save();
		
		// 		if (is_string($categories)) {
		// 			$categories = explode(',', $categories);
		// 		}
		
		// 		$this->categories()->save($categories);
		
		// 		$data['post_keywords'] = str_replace('，', ',', $data['post_keywords']);
		
		// 		$keywords = explode(',', $data['post_keywords']);
		
		// 		$this->addTags($keywords, $this->id);
		
		return $this;
		
	}
	
	public function adminCourtRegistrationPublished($data){
		
	}
}
