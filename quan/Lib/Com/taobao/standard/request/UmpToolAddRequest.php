<?php
/**
 * TOP API: taobao.ump.tool.add request
 * 
 * @author auto create
 * @since 1.0, 2012-06-13 12:41:10
 */
class UmpToolAddRequest
{
	/** 
	 * 工具内容，由sdk里面的MarketingTool生成的json字符串
	 **/
	private $content;
	
	private $apiParas = array();
	
	public function setContent($content)
	{
		$this->content = $content;
		$this->apiParas["content"] = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getApiMethodName()
	{
		return "taobao.ump.tool.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->content,"content");
	}
}
