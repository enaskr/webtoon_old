<?php
	include('config.php');
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
	if ( strpos($uri, "/protoon/") == true ) {
	 $strprotoon = '<b><font color="purple">프로툰</font></b>';
	 $strtitle = "프로툰";
	 $favicon = '<link rel="icon" href="favicon.ico"><link rel="shortcut icon" href="favicon.ico">';
	} else {
	 $strprotoon = '프로툰';
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
	if ( strpos($uri, "/spowiki/") == true ) {
	 $strspowiki = '<b><font color="purple">스포위키</font></b>';
	 $strtitle = "스포위키";
	 $favicon = '<link rel="icon" href="favicon.png"><link rel="shortcut icon" href="favicon.png">';
	} else {
	 $strspowiki = '스포위키';
	}
	if ( strpos($uri, "/manatoki/") == true ) {
	 $strmanatoki = '<b><font color="purple">마나토끼</font></b>';
	 $strtitle = "마나토끼";
	 $favicon = '<link rel="icon" href="favicon.ico"><link rel="shortcut icon" href="favicon.ico">';
	} else {
	 $strmanatoki = '마나토끼';
	}
?><html>
<head>
	<title><?php echo $strtitle; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php echo $favicon; ?>
	<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Nanum+Gothic&amp;subset=korean">
	<script type="text/javascript" src="//code.jquery.com/jquery-3.5.1.min.js"></script>
	<style type="text/css">
		body {
			font-family: 'Nanum Gothic', sans-serif;
			font-size: smaller;
		}
		a:link {text-decoration: none;}
		a:visited {text-decoration: none;}
		a:active {text-decoration: none;}
		a:hover {text-decoration: none;}
	</style>
</head>
<body>
<font size="3"><a href="../">초기화면</a> | <a href="../newtoki/<?php echo $end; ?>"><?php echo $strnewtoki; ?></a> | <a href="../protoon/<?php echo $end; ?>"><?php echo $strprotoon; ?></a> | <a href="../toonkor/<?php echo $end; ?>"><?php echo $strtoonkor; ?></a> | <a href="../funbe/<?php echo $end; ?>"><?php echo $strfunbe; ?></a> | <a href="../spowiki/<?php echo $end; ?>"><?php echo $strspowiki; ?></a><?php if ( $manga_view == "true" ) { ?> | <a href="../manatoki/?<?php echo $end; ?>&isnew=Y"><?php echo $strmanatoki; ?></a></font><br>
