<?php
/**
 * CouponCodeAction.class.php
 * @copyright			copyright(c) 2011 - 2012 极好居
 * @author				anqiu xiao
 * @contact				QQ:89249294 E-mail:jihaoju@qq.com
 * @date				Sun Apr 08 01:06:50 CST 2012
 */
/**
 * 网购优惠码
 *
 */
class CouponCodeAction extends AdminCommonAction
{
	private $_code_type_conf;
	private $_code_price_conf;
	
	protected function _initialize()
    {
    	parent::_initialize();
    	$this->_code_type_conf = array(
    								1	=>	'减免券',
    								2	=>	'代金券',
    								);
    	$this->_code_price_conf = array(
    								1	=>	'免费',
    								2	=>	'付费',
    								3	=>	'积分',
    								);
    	$this->_code_expiry_conf = array(
    								1	=>	'有限制',
    								2	=>	'无限制',
    								);
    }
    
	public function index()
	{
		$page = isset($_REQUEST['page']) && $_REQUEST['page'] >= 1 ? $_REQUEST['page'] : 1;
    	$pageLimit = 15;
    	$localTimeObj = LocalTime::getInstance();
    	$ccModel = D('CouponCode');
    	$params = array(
    					'kw'		=>	isset($_REQUEST['kw']) && $_REQUEST['kw'] ? $_REQUEST['kw'] : ''
    					);
    	$keys = array();
    	$res = $ccModel->getAll($keys, $params, array('begin'=>($page-1)*$pageLimit, 'offset'=>$pageLimit));
    	$codes = array();
    	foreach ($res['data'] as $rs){
    		if($rs['expiry_type'] == 1){
    			$rs['expiry'] = $localTimeObj->local_date($this->_CFG['date_format'], $rs['expiry']);
    		}
    		$codes[] = $rs;
    	}
    	$this->assign('codes', $codes);
    	$page_url = "?g=".GROUP_NAME."&m=".MODULE_NAME."&a=".ACTION_NAME."&page=[page]";
    	foreach ($params as $key => $val){
    		$page_url .= "&$key=$val";
    	}
    	$p=new Page($page,
    			$pageLimit,
    			$res['count'],
    			$page_url,
    			5,
    			5);
    	$pagelink=$p->showStyle(3);
    	$this->assign('pagelink', $pagelink);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 优惠券列表');
		$this->display();
	}
	
	public function add()
	{
		if($this->isPost()){
			if(C('TOKEN_ON') && ! checkFormToken($_REQUEST, 'hash')){
				die('hack attemp.');
			}
			if((! $_REQUEST['m_name'] || ! $_REQUEST['m_id'] || ! $_REQUEST['c_type'] || ! $_REQUEST['expiry_type']
			 || ! $_REQUEST['fetch_limit'] || ! $_REQUEST['price_type'])
			 || ($_REQUEST['c_type'] == 1 && (! $_REQUEST['money_max'] || ! $_REQUEST['money_reduce']))
			 || ($_REQUEST['c_type'] == 2 && ! $_REQUEST['money_amount'])
			 || ($_REQUEST['expiry_type'] == 1 && ! $_REQUEST['expiry'])
			 || ($_REQUEST['price_type'] == 2 && ! $_REQUEST['price_2'])
			 || ($_REQUEST['price_type'] == 3 && ! $_REQUEST['price_3'])){
				$this->error('请填写所有的必填项');
			}
			$localTimeObj = LocalTime::getInstance();
			$data = array(
						'm_id'			=>	intval($_REQUEST['m_id']),
						'm_name'		=>	$_REQUEST['m_name'],
						'c_type'		=>	$_REQUEST['c_type'],
						'expiry_type'	=>	$_REQUEST['expiry_type'],
						'price_type'	=>	$_REQUEST['price_type'],
						'addtime'		=>	$localTimeObj->gmtime()
						);
			if($_REQUEST['c_type'] == 1){
				$data['money_max'] = floatval($_REQUEST['money_max']);
				$data['money_reduce'] = floatval($_REQUEST['money_reduce']);
			}elseif($_REQUEST['c_type'] == 2){
				$data['money_amount'] = floatval($_REQUEST['money_amount']);
			}
			if($_REQUEST['expiry_type'] == 1){
				$data['expiry'] = $localTimeObj->local_strtotime($_REQUEST['expiry'] . ' 23:59:59');
			}else if($_REQUEST['expiry_type'] == 2){
				$data['expiry'] = $localTimeObj->local_strtotime('2029-12-31 23:59:59');
			}
			if($_REQUEST['price_type'] == 2){
				$data['price'] = floatval($_REQUEST['price_2']);
			}elseif($_REQUEST['price_type'] == 3){
				$data['price'] = floatval($_REQUEST['price_3']);
			}
			$ccModel = D('CouponCode');
			$c_id = 0;
			if($c_id = $ccModel->_add($data)){
				//插入附属表数据
				$data = array(
							'c_id'			=>	$c_id,
							'fetch_limit'	=>	$_REQUEST['fetch_limit'],
							'directions'	=>	$_REQUEST['directions'],
							'prompt'		=>	$_REQUEST['prompt'],
							);
				$ccdModel = D('CouponCodeData');
				$ccdModel->_add($data);
				$this->assign('jumpUrl', '?g='.GROUP_NAME.'&m='.MODULE_NAME);
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}
		$this->assign('code_type_conf', $this->_code_type_conf);
		$this->assign('code_price_conf', $this->_code_price_conf);
		$this->assign('code_expiry_conf', $this->_code_expiry_conf);
		$this->assign('fetch_limit_conf', CouponCodeConf::fetch_limit_conf());
		$this->assign('ur_href', '优惠券管理 &gt; 添加优惠券');
		$this->assign('_hash_', buildFormToken('hash'));
		$this->display('post');
	}
	
	public function edit()
	{
		$c_id = intval($_REQUEST['c_id']);
		$ccModel = D('CouponCode');
		$code = $ccModel->info($c_id);
		$code or die('id invalid.');
		$localTimeObj = LocalTime::getInstance();
		if($this->isPost()){
			if(C('TOKEN_ON') && ! checkFormToken($_REQUEST, 'hash')){
				die('hack attemp.');
			}
			if((! $_REQUEST['m_name'] || ! $_REQUEST['m_id'] || ! $_REQUEST['c_type'] || ! $_REQUEST['expiry_type']
			 || ! $_REQUEST['fetch_limit'] || ! $_REQUEST['price_type'])
			 || ($_REQUEST['c_type'] == 1 && (! $_REQUEST['money_max'] || ! $_REQUEST['money_reduce']))
			 || ($_REQUEST['c_type'] == 2 && ! $_REQUEST['money_amount'])
			 || ($_REQUEST['expiry_type'] == 1 && ! $_REQUEST['expiry'])
			 || ($_REQUEST['price_type'] == 2 && ! $_REQUEST['price_2'])
			 || ($_REQUEST['price_type'] == 3 && ! $_REQUEST['price_3'])){
				$this->error('请填写所有的必填项');
			}
			$data = array(
						'm_id'			=>	intval($_REQUEST['m_id']),
						'm_name'		=>	$_REQUEST['m_name'],
						'c_type'		=>	$_REQUEST['c_type'],
						'expiry_type'	=>	$_REQUEST['expiry_type'],
						'price_type'	=>	$_REQUEST['price_type'],
						);
			if($_REQUEST['c_type'] == 1){
				$data['money_max'] = floatval($_REQUEST['money_max']);
				$data['money_reduce'] = floatval($_REQUEST['money_reduce']);
			}elseif($_REQUEST['c_type'] == 2){
				$data['money_amount'] = floatval($_REQUEST['money_amount']);
			}
			if($_REQUEST['expiry_type'] == 1){
				$data['expiry'] = $localTimeObj->local_strtotime($_REQUEST['expiry'] . ' 23:59:59');
			}else{
				$data['expiry'] = 0;
			}
			if($_REQUEST['price_type'] == 2){
				$data['price'] = floatval($_REQUEST['price_2']);
			}elseif($_REQUEST['price_type'] == 3){
				$data['price'] = floatval($_REQUEST['price_3']);
			}
			if($ccModel->_edit($c_id, $data)){
				//插入附属表数据
				$data = array(
							'fetch_limit'	=>	$_REQUEST['fetch_limit'],
							'directions'	=>	$_REQUEST['directions'],
							'prompt'		=>	$_REQUEST['prompt'],
							);
				$ccdModel = D('CouponCodeData');
				$ccdModel->_edit($c_id, $data);
				$this->assign('jumpUrl', '?g='.GROUP_NAME.'&m='.MODULE_NAME);
				$this->success('编辑成功');
			}else{
				$this->error('编辑失败');
			}
		}
		if($code['expiry_type'] == 1){
    		$code['expiry'] = $localTimeObj->local_date($this->_CFG['date_format'], $code['expiry']);
    	}
    	$this->assign('code', $code);
		$this->assign('code_type_conf', $this->_code_type_conf);
		$this->assign('code_price_conf', $this->_code_price_conf);
		$this->assign('code_expiry_conf', $this->_code_expiry_conf);
		$this->assign('fetch_limit_conf', CouponCodeConf::fetch_limit_conf());
		$this->assign('ur_href', '优惠券管理 &gt; 编辑优惠券');
		$this->assign('_hash_', buildFormToken('hash'));
		$this->display('post');
	}
	
	public function del()
	{
		if($this->isAjax()){
			$c_id = intval($_REQUEST['id']);
			M('coupon_code')->where("c_id='$c_id'")->delete();
			M('coupon_code_best')->where("c_id='$c_id'")->delete();
			M('coupon_code_codes')->where("c_id='$c_id'")->delete();
			M('coupon_code_data')->where("c_id='$c_id'")->delete();
			$this->ajaxReturn('', '删除成功' ,1);
		}
	}
	
	public function view()
	{
		$c_id = intval($_REQUEST['c_id']);
		$ccModel = D('CouponCode');
		$code = $ccModel->info($c_id);
		$code or die('id invalid.');
		import('@.Com.Util.Ubb');
		$localTimeObj = LocalTime::getInstance();
		if($code['expiry_type'] == 1){
    		$code['expiry'] = $localTimeObj->local_date($this->_CFG['date_format'], $code['expiry']);
    	}
    	$fetch_limit_conf = CouponCodeConf::fetch_limit_conf();
    	$code['fetch_limit'] = $fetch_limit_conf[$code['data']['fetch_limit']];
    	$code['data']['directions'] = Ubb::ubb2html($code['data']['directions']);
    	$code['data']['prompt'] = Ubb::ubb2html($code['data']['prompt']);
    	$this->assign('code', $code);
		$this->assign('ur_href', '优惠券管理 &gt; 优惠券详情');
		$this->display();
	}
	
	public function best()
	{
		$page = isset($_REQUEST['page']) && $_REQUEST['page'] >= 1 ? $_REQUEST['page'] : 1;
    	$pageLimit = 15;
    	$localTimeObj = LocalTime::getInstance();
    	$ccbModel = D('CouponCodeBest');
    	$res = $ccbModel->getAll(array('begin'=>($page-1)*$pageLimit, 'offset'=>$pageLimit));
    	$codes = array();
    	foreach ($res['data'] as $rs){
    		$rs['expiry'] = $localTimeObj->local_date($this->_CFG['date_format'], $rs['expiry']);
    		$codes[] = $rs;
    	}
    	$this->assign('codes', $codes);
    	$page_url = "?g=".GROUP_NAME."&m=".MODULE_NAME."&a=".ACTION_NAME."&page=[page]";
    	$p=new Page($page,
    			$pageLimit,
    			$res['count'],
    			$page_url,
    			5,
    			5);
    	$pagelink=$p->showStyle(3);
    	$this->assign('pagelink', $pagelink);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 每日精选优惠券');
		$this->display();
	}
	
	public function set_best()
	{
		$c_id = intval($_REQUEST['id']);
		$ccbModel = D('CouponCodeBest');
		if($ccbModel->info($c_id, array('c_id'))){
			$this->error('该优惠券已被设置为精选');
		}
		if($this->isPost()){
			if(! $_REQUEST['expiry'] || ! $_REQUEST['sort_order']){
				exit('data invalid.');
			}
			$localTimeObj = LocalTime::getInstance();
			$expiry = $localTimeObj->local_strtotime($_REQUEST['expiry'] . ' 23:59:59');
			$sort_order = intval($_REQUEST['sort_order']);
			$data = array(
						'c_id'		=>	$c_id,
						'expiry'	=>	$expiry,
						'sort_order'=>	$sort_order
						);
			
			if($ccbModel->_add($data)){
				$this->assign('jumpUrl', '?g='.GROUP_NAME.'&m='.MODULE_NAME.'&a=best');
				$this->success('设置成功');
			}else{
				$this->error('设置失败');
			}
		}
		$ccModel = D('CouponCode');
		$code = $ccModel->info($c_id);
		$code or die('id invalid.');
		$this->assign('code', $code);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 设为每日精选');
		$this->display();
	}
	
	public function edit_best()
	{
		$c_id = intval($_REQUEST['id']);
		$ccbModel = D('CouponCodeBest');
		$best = $ccbModel->info($c_id);
		$best or die('id invalid.');
		$localTimeObj = LocalTime::getInstance();
		if($this->isPost()){
			if(! $_REQUEST['expiry'] || ! $_REQUEST['sort_order']){
				exit('data invalid.');
			}
			$expiry = $localTimeObj->local_strtotime($_REQUEST['expiry'] . ' 23:59:59');
			$sort_order = intval($_REQUEST['sort_order']);
			$data = array(
						'expiry'	=>	$expiry,
						'sort_order'=>	$sort_order
						);
			
			if($ccbModel->update($c_id, $data)){
				$this->assign('jumpUrl', '?g='.GROUP_NAME.'&m='.MODULE_NAME.'&a=best');
				$this->success('设置成功');
			}else{
				$this->error('设置失败');
			}
		}
		$best['expiry'] = $localTimeObj->local_date($this->_CFG['date_format'], $best['expiry']);
		$ccModel = D('CouponCode');
		$code = $ccModel->info($c_id);
		$code or die('id invalid.');
		$this->assign('code', $code);
		$this->assign('best', $best);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 设为每日精选');
		$this->display('set_best');
	}
	
	public function unbest()
	{
		if($this->isAjax()){
			$c_id = intval($_REQUEST['id']);
			$ccbModel = D('CouponCodeBest');
			if($ccbModel->_delete($c_id)){
				$this->ajaxReturn('',buildFormToken(),1);
			}else{
				$this->ajaxReturn('','操作失败',0);
			}
		}
	}
	
	public function code()
	{
		$c_id = intval($_REQUEST['c_id']);
		$ccModel = D('CouponCode');
		$code = $ccModel->info($c_id);
		$page = isset($_REQUEST['page']) && $_REQUEST['page'] >= 1 ? $_REQUEST['page'] : 1;
    	$pageLimit = 20;
    	$localTimeObj = LocalTime::getInstance();
    	$codesModel = D('CouponCodeCodes');
    	$res = $codesModel->getAll($c_id, array('begin'=>($page-1)*$pageLimit, 'offset'=>$pageLimit));
    	$codes = array();
    	foreach ($res['data'] as $rs){
    		$rs['fetch_time'] = $rs['fetch_time']
    							? $localTimeObj->local_date($this->_CFG['time_format'], $rs['fetch_time'])
    							: '';
    		$codes[] = $rs;
    	}
    	$this->assign('codes', $codes);
    	$this->assign('code', $code);
    	$page_url = "?g=".GROUP_NAME."&m=".MODULE_NAME."&a=".ACTION_NAME."&page=[page]&c_id=$c_id";
    	$p=new Page($page,
    			$pageLimit,
    			$res['count'],
    			$page_url,
    			5,
    			5);
    	$pagelink=$p->showStyle(3);
    	$this->assign('pagelink', $pagelink);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 优惠码管理');
		$this->display();
	}
	
	public function add_code()
	{
		$c_id = intval($_REQUEST['c_id']);
		if($this->isPost()){
			if(C('TOKEN_ON') && ! checkFormToken($_REQUEST)){
				die('hack attemp.');
			}
			if(empty($_REQUEST['codes'])){
				$this->error('请输入优惠码');
			}
			$codesModel = D('CouponCodeCodes');
			$codes = explode("\r\n", $_REQUEST['codes']);
			foreach ($codes as $code){
				$data = array(
							'c_id'			=>	$c_id,
							'code'			=>	$code,
							);
				$codesModel->_add($data);
			}
			//更新数量
			$codeModel = D('CouponCode');
			$data = array(
						'amount' => $codesModel->where("c_id='$c_id'")->count()
						);
			$codeModel->_edit($c_id, $data);
			$this->assign('jumpUrl', '?g='.GROUP_NAME.'&m='.MODULE_NAME.'&a=code&c_id='.$c_id);
			$this->success('添加成功');
		}
		$this->assign('c_id', $c_id);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 优惠码管理 &gt; 添加');
		$this->display();
	}
	
	//TODO:批量导入
	public function import()
	{
		$c_id = intval($_REQUEST['c_id']);
		if($this->isPost()){
			if(C('TOKEN_ON') && ! checkFormToken($_REQUEST)){
				die('hack attemp.');
			}
			if(empty($_REQUEST['codes'])){
				$this->error('请输入优惠券');
			}
			$codesModel = D('CouponCodeCodes');
			$codes = explode("\r\n", $_REQUEST['codes']);
			foreach ($codes as $code){
				$data = array(
							'c_id'			=>	$c_id,
							'code'			=>	$code,
							);
				$codesModel->_add($data);
			}
			//更新数量
			$codeModel = D('CouponCode');
			$data = array(
						'amount' => $codesModel->where("c_id='$c_id'")->count()
						);
			$codeModel->_edit($c_id, $data);
			$this->assign('jumpUrl', '?g='.GROUP_NAME.'&m='.MODULE_NAME.'&a=code&c_id='.$c_id);
			$this->success('导入成功');
		}
		$this->assign('c_id', $c_id);
		$this->assign('_hash_', buildFormToken());
		$this->assign('ur_href', '优惠券管理 &gt; 优惠券列表 &gt; 批量导入');
		$this->display();
	}
	
	function del_code()
	{
		if($this->isAjax()){
			if(C('TOKEN_ON') && ! checkFormToken($_REQUEST)){
				die('hack attemp.');
			}
			$id = intval($_REQUEST['id']);
			$codesModel = D('CouponCodeCodes');
			$code = $codesModel->info($id);
			if($code['user_id']){
				$this->ajaxReturn('', '该优惠码已被领取，不可删除', 0);
			}
			if($codesModel->_delete($id)){
				$this->ajaxReturn('', '', 1);
			}else{
				$this->ajaxReturn('', '操作失败', 0);
			}
		}
	}
}