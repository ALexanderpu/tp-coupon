<?php
/**
 * TaoShopCategoryService.class.php
 * @copyright			copyright(c) 2011 - 2012 极好居
 * @author				anqiu xiao
 * @contact				QQ:89249294 E-mail:jihaoju@qq.com
 * @date				Sun Apr 08 01:47:10 CST 2012
 */
class TaoShopCategoryService
{
	public function info($id)
	{
		$cates = F('tao_shop_cates');
		if(! $cates){
			$cates = self::_createCaches();
		}
		return $cates['all'][$id];
	}
	
	public function getTree()
	{
		$result = array();
		$cates = F('tao_shop_cates');
		if(!$cates || empty($cates['tree'])){
			$cates = self::_createCaches();
		}
		return $result = $cates['tree'];
	}
	
	public function getAll()
	{
		$result = array();
		$cates = F('tao_shop_cates');
		if(!$cates || empty($cates['all'])){
			$cates = self::_createCaches();
		}
		return $result = $cates['all'];
	}
	
	public function clearCaches()
	{
		F('tao_shop_cates', null);
	}
	
	private static function _createCaches()
	{
        $treeObj = new Tree();
        $cccModel = M('tao_shop_category');
        $cates = $tree = array();
        $res = $cccModel->order('sort_order ASC')->select();
        foreach ($res as $rs){
        	$cates[$rs['id']] = $rs;
            $treeObj->setNode($rs['id'],
                              $rs['parent_id'],
                              $rs['name'],
                              $rs['sort_order']);
        }
        $category = $treeObj->getCateTree(0);
        if(! empty($category)){
            unset($category[0]);
        }
        foreach ($category as $c){
        	$c['cid'] = $cates[$c['id']]['cid'];
        	$tree[] = $c;
        }
        $result = array('tree'=>$tree,'all'=>$cates);
        F('tao_shop_cates', $result);
        return $result;
	}
}