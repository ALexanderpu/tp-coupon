<? if (!class_exists('template')) die('Access Denied');$template->getInstance()->check('step_3.html', 'aa54d1e2088809f3a36ae6cf4c9a9bda', 1336493717);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TP-COUPON 安装向导</title>
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<script type="text/javascript">
	function $(id) {
		return document.getElementById(id);
	}

	function showmessage(message) {
		$('notice').value += message + "\r\n";
	}
</script>
<meta content="Comsenz Inc." name="Copyright" />
</head>
<div class="container">
	<div class="header">
		<h1>TP-COUPON 安装向导</h1>
		<span>V1.6.0 简体中文 UTF8 版 20110501</span>	<div class="setup step3">
		<h2>安装数据库</h2>
		<p>正在执行数据库安装</p>
	</div>
	<div class="stepstat">
		<ul>
			<li class="">1</li>
			<li class="">2</li>
			<li class="current">3</li>
			<li class="unactivated last">4</li>
		</ul>
		<div class="stepstatbg stepstat1"></div>
	</div>
</div>
<div class="main"><script type="text/javascript">
function showmessage(message) {
	document.getElementById('notice').value += message + "\r\n";
}
function initinput() {
	window.location='index.php?step=3';
}
</script>
	<div class="main">
		<div class="btnbox"><textarea name="notice" style="width: 80%;"  readonly="readonly" id="notice"></textarea></div>
		<div class="btnbox marginbot">
	<input type="button" name="submit" value="正在安装..." disabled style="height: 25" id="laststep" onclick="initinput()">
	</div>
<script type="text/javascript">showmessage('建立数据表 uc_applications ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_members ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_memberfields ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_newpm ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_friends ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_tags ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_sqlcache ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_settings ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_badwords ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_notelist ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_domains ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_feeds ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_admins ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_failedlogins ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_protectedmembers ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_mergemembers ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_vars ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_mailqueue ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_members ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_lists ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_indexes ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_0 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_1 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_2 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_3 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_4 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_5 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_6 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_7 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_8 ... 成功  ');</script>
<script type="text/javascript">showmessage('建立数据表 uc_pm_messages_9 ... 成功  ');</script>
<script type="text/javascript">document.getElementById("laststep").disabled=false;document.getElementById("laststep").value = '安装用户中心成功，点击进入下一步';</script>
		<div class="footer">&copy;2001 - 2011 <a href="http://www.comsenz.com/">Comsenz</a> Inc.</div>
	</div>
</div>
</body>
</html>