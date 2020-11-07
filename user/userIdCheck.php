<?php
	$server_path = (str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = (str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]));
	include($server_path.'../lib/dbconn.php');
	$cookieMbrNo = $_COOKIE["MBRNO"];
	$webtoonDB = new SQLite3($server_path.'../lib/webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
		$userList = "SELECT MBR_NO, USERID, PASSWORD, USERNAME, EMAIL, PHONE, Memo, STATUS, REGDTIME, UPTDTIME FROM TOON_USER WHERE USERID = '".$_GET["userid"]."'; ";
		$webtoonView = $webtoonDB->query($userList);
		$viewDate = "";
		$alreadView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$memmbr_no = $row["MBR_NO"];
			$memuserID = $row["USERID"];
		}
	}
	if ( $memmbr_no != null && strlen($memmbr_no) > 0 ) {
		echo "Y";
	} else {
		echo "N";
	}
?>