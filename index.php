<?php
	$cookieMbrNo = $_COOKIE["MBRNO"];
?><!DOCTYPE html>
<html lang='ko'>
<head>
<meta charset='utf-8'>
<meta name='viewport' id='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0'>
<meta name='mobile-web-app-capable' content='yes'>
<title>:::WEBTOON::웹툰:::</title>
<link rel='stylesheet' href='lib/css/ui.css' type='text/css'>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script> <![endif]-->
<script src='lib/js/jquery-2.1.4.min.js'></script>
</head>
<body>
	<div id='container'>
		<div class='item'>
<?php
		if ( $cookieMbrNo!=null && strlen($cookieMbrNo) > 0 ) {
			//$cookieUserName = "jackie";
			define('KEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
			define('KEY_128', substr(KEY,0,128/8));
			define('KEY_256', substr(KEY,0,256/8));
			$mbr_no = openssl_decrypt($cookieMbrNo, 'AES-256-CBC', KEY_256, 0, KEY_128);
?>
			<dl>
				<dt>WEBTOON</dt>
				<dd>
					<div class="group">
					<a href="./newtoki/" target="_top">뉴토끼</a>
					<a href="./newtoki/?end=END" target="_top">뉴토끼 완결</a>
					<a href="./newtoki/update.php" target="_top">주소</a>
					</div>
					<div class="group">
					<a href="./toonkor/" target="_top">툰코</a>
					<a href="./toonkor/?end=END" target="_top">툰코 완결</a>
					<a href="./toonkor/update.php" target="_top">주소</a>
					</div>
					<div class="group">
					<a href="./funbe/" target="_top">펀비</a>
					<a href="./funbe/?end=END" target="_top">펀비 완결</a>
					<a href="./funbe/update.php" target="_top">주소</a>
					</div>
					<div class="group">
					<a href="./manatoki/?isnew=Y" target="_top">마나토끼</a>
					<a href="./manatoki/?end=END" target="_top">마나토끼 완결</a>
					<a href="./manatoki/update.php" target="_top">주소</a>
					</div>
					<div class="group">
					<a href="./11toon/?isnew=Y" target="_top">일일툰</a>
					<a href="./11toon/?end=END" target="_top">일일툰 완결</a>
					<a href="./11toon/update.php" target="_top">주소</a>
					</div>
				</dd>
			</dl>
			<dl>
				<dd>
					<div class="group">
						<a href="./lib/logout.php">로그아웃</a>
						<a href="./user/index.php">마이페이지</a>
					</div>
				</dd>
			</dl>
<?php
		} else {
?>
			<dl>
				<dt>WEBTOON</dt>
				<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<form name="userLogin" method="post" action="./lib/login.php">
						<tr style='background-color:#f8f8f8'>
							<td style='width:25%;font-size:16px;color:#8000ff;' align=center valign=middle>아이디</td>
							<td style='font-size:16px;color:#8000ff;' align=center valign=middle><input type="text" name="user" style='border:none; line-height:48px; width:100%;'></td>
							<td rowspan="2" style='width:25%;font-size:16px;color:#8000ff;' align=center valign=middle><input type="submit" name="submit" style='border:none; line-height:98px; width:100%;' value="로그인"></td>
						</tr>
						<tr style='background-color:#f8f8f8'>
							<td style='width:25%;font-size:16px;color:#8000ff;' align=center valign=middle>비밀번호</td>
							<td style='font-size:16px;color:#8000ff;' align=center valign=middle><input type="password" name="pass" style='border:none; line-height:48px; width:100%;'></td>
						</tr>
						<tr style='background-color:#f8f8f8'>
							<td colspan="3" style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><input type="button" name="submit" style='border:none; line-height:48px; width:100%;' value="회원가입" onClick="location.href='./user/userform.php';"></td>
						</tr>
					</form>
				</dd>
			</dl>
<?php
		}
?>
		</div>
	</div>
</body>
</html>
