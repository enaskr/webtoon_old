<?php
	include('config.php');
?>
<!DOCTYPE html>
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
		<!-- item : S -->
		<div class='item'>
			<dl>
				<dt>WEBTOON</dt>
				<dd>
<?php
	if ( $is_adult == "true" ) { 
?>
					<div class="group">
					<a href="./newtoki/" target="_top">뉴토끼</a>
					<a href="./newtoki/?end=END" target="_top">뉴토끼 완결</a>
					<a href="./newtoki/update.php" target="_top">주소</a>
					</div>
					<div class="group">
					<a href="./protoon/" target="_top">프로툰</a>
					<a href="./protoon/?end=END" target="_top">프로툰 완결</a>
					<a href="./protoon/update.php" target="_top">주소</a>
					</div>
<?php
	}
?>
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
					<a href="./spowiki/" target="_top">스포위키</a>
					<a href="./spowiki/?end=END" target="_top">스포위키 완결</a>
					<a href="./spowiki/update.php" target="_top">주소</a>
					</div>
				</dd>
			</dl>
		</div>
		<!--// item : E -->
	</div>
</body>
</html>
