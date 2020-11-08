<!DOCTYPE html>
<html lang='ko'>
<head>
<meta charset='utf-8'>
<meta name='viewport' id='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0'>
<meta name='mobile-web-app-capable' content='yes'>
<title>:::WEBTOON::웹툰:::</title>
<link rel='stylesheet' href='css/ui.css' type='text/css'>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script> <![endif]-->
<script src='js/jquery-2.1.4.min.js'></script>
</head>
<body>
<?php
	$userid = $_POST["user"];
	$userpw = $_POST["pass"];
	$webtoonDB = new SQLite3('./webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
		if ( $userid !=null && strlen($userid) > 3 && $userpw !=null && strlen($userpw) > 3 ) {
			$userpassword = strtoupper(hash("sha256", $userpw));
			 //echo "SELECT MBR_NO, USERID, USERNAME, EMAIL, PHONE, REGDTIME FROM TOON_USER WHERE USERID = '".$userid."' AND PASSWORD = '".$userpassword."' AND STATUS IN ('OK','APPROVED') LIMIT 1;";
			// SELECT
			$result = $webtoonDB->query("SELECT MBR_NO, USERID, USERNAME, EMAIL, PHONE, REGDTIME FROM TOON_USER WHERE USERID = '".$userid."' AND PASSWORD = '".$userpassword."' AND STATUS IN ('OK','APPROVED') LIMIT 1;");
			while($row = $result->fetchArray(SQLITE3_ASSOC)){         
				$userMbrno = $row["MBR_NO"];
				$userID = $row["USERID"];
				$userName = $row["USERNAME"];
				$userEmail = $row["EMAIL"];
				$userPhone = $row["PHONE"];
				$userCreated = $row["REGDTIME"];

				define('KEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
				define('KEY_128', substr(KEY,0,128/8));
				define('KEY_256', substr(KEY,0,256/8));
				$mbr_no = openssl_encrypt($userMbrno, 'AES-256-CBC', KEY_256, 0, KEY_128);
				setcookie("MBRNO", $mbr_no, time()+14400, "/");
				Header("Location:../"); 
			}
			if ( $userMbrno==null || strlen($userMbrno) == 0) {
?>
<script type="text/javascript">
	alert("아이디와 비밀번호를 정확하게 입력해주세요.");
	window.history.back();
</script>
<?php
			}
		} else {
?>
<script type="text/javascript">
	alert("아이디와 비밀번호를 정확하게 입력해주세요");
	window.history.back();
</script>
<?php
		}
	} else {
?>
<script type="text/javascript">
	alert("DB연결시 오류가 발생했습니다.");
	window.history.back();
</script>
<?php
		echo $webtoonDB->lastErrorMsg();
	}
?></body>
</html>
