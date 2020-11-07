<?php
	include($server_path.'../lib/config.php');
	include($server_path.'../lib/dbconn.php');
	include($server_path.'../lib/simple_html_dom.php');

	$ends = $_GET["end"];
	if ( $ends != null && $ends == "END" ) $end = "?end=END";

	$strtitle = "";
	$uri= $_SERVER['REQUEST_URI']; //uri를 구합니다.
	if ( strpos($uri, "/newtoki/") == true ) {
	 $strnewtoki = '<b><font color="purple">뉴토끼</font></b>';
	 $strtitle = "뉴토끼";
	 $favicon = '<link rel="icon" href="favicon.ico"><link rel="shortcut icon" href="favicon.ico">';
	} else {
	 $strnewtoki = '뉴토끼';
	}
	if ( strpos($uri, "/toonkor/") == true ) {
	 $strtoonkor = '<b><font color="purple">툰코</font></b>';
	 $strtitle = "툰코";
	} else {
	 $strtoonkor = '툰코';
	}
	if ( strpos($uri, "/funbe/") == true ) {
	 $strfunbe = '<b><font color="purple">펀비</font></b>';
	 $strtitle = "펀비";
	 $favicon = '<link rel="icon" href="favicon.png"><link rel="shortcut icon" href="favicon.png">';
	} else {
	 $strfunbe = '펀비';
	}
	if ( strpos($uri, "/manatoki/") == true ) {
	 $strmanatoki = '<b><font color="purple">마나토끼</font></b>';
	 $strtitle = "마나토끼";
	 $favicon = '<link rel="icon" href="favicon.ico"><link rel="shortcut icon" href="favicon.ico">';
	} else {
	 $strmanatoki = '마나토끼';
	}
	if ( strpos($uri, "/11toon/") == true ) {
	 $str11toon = '<b><font color="purple">일일툰</font></b>';
	 $strtitle = "일일툰";
	} else {
	 $str11toon = '일일툰';
	}
	if ( strpos($uri, "/user/") == true || strpos($uri, "/lib/") == true ) {
		$strUser = " | <a href='".$http_path."../user/'><b>마이페이지</b></a>";
		$strtitle = "마이페이지";
	} else {
		$strUser = " | <a href='".$http_path."../user/'>마이페이지</a>";
	}

	if ( $login_view == true && basename($_SERVER["PHP_SELF"]) != "userform.php" ) {
		if ( $isLogin != true ) {
			Header("Location:../"); 
		}
	}

?><!DOCTYPE html>
<html lang='ko'>
<head>
<meta charset='utf-8'>
<meta name='viewport' id='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0'>
<meta name='mobile-web-app-capable' content='yes'>
<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
<?php echo $favicon; ?>
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Nanum+Gothic&amp;subset=korean">
<title>:::WEBTOON::웹툰:::</title>
<link rel='stylesheet' href='<?php echo $http_path; ?>../lib/css/ui.css' type='text/css'>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script> <![endif]-->
<script src='<?php echo $http_path; ?>../lib/js/jquery-2.1.4.min.js'></script>
</head>
<body>
<font size="3"><a href="<?php echo $http_path; ?>../">HOME </a><?php echo $strUser; ?> | <a href="<?php echo $http_path; ?>../newtoki/<?php echo $end; ?>"><?php echo $strnewtoki; ?></a> | <a href="<?php echo $http_path; ?>../toonkor/<?php echo $end; ?>"><?php echo $strtoonkor; ?></a> | <a href="<?php echo $http_path; ?>../funbe/<?php echo $end; ?>"><?php echo $strfunbe; ?></a> | <a href="<?php echo $http_path; ?>../manatoki/?<?php echo $end; ?>"><?php echo $strmanatoki; ?></a> | <a href="<?php echo $http_path; ?>../11toon/<?php echo $end; ?>"><?php echo $str11toon; ?></a></font><br>