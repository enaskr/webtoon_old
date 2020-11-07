<?php
	include($server_path.'../lib/header.php');

	$usersql = "INSERT INTO 'TOON_USER' ('MBR_NO', 'USERID', 'USERNAME', 'EMAIL', 'PHONE', 'PASSWORD', 'Memo', 'STATUS', 'REGDTIME') ";
	if ( $userID == "admin" ) {
		$usersql = $usersql." VALUES ('".$_POST["mbr_no"]."', '".$_POST["userid"]."','".$_POST["username"]."','".$_POST["useremail"]."','".$_POST["userphone"]."','".strtoupper(hash("sha256", $_POST["userpassword"]))."','".$_POST["usermemo"]."','".$_POST["userstatus"]."','".date("YmdHis", time())."');";
	} else {
		$usersql = $usersql." VALUES ('".$_POST["mbr_no"]."', '".$_POST["userid"]."','".$_POST["username"]."','".$_POST["useremail"]."','".$_POST["userphone"]."','".strtoupper(hash("sha256", $_POST["userpassword"]))."','".$_POST["usermemo"]."','WAIT','".date("YmdHis", time())."');";
	}
		$usersql = $usersql." VALUES ('".$_POST["mbr_no"]."', '".$_POST["userid"]."','".$_POST["username"]."','".$_POST["useremail"]."','".$_POST["userphone"]."','".strtoupper(hash("sha256", $_POST["userpassword"]))."','".$_POST["usermemo"]."','".$_POST["userstatus"]."','".date("YmdHis", time())."');";
		$webtoonDB->exec($usersql);
?>
<script type="text/javascript">
	alert("회원정보를 정상적으로 등록하였습니다.");
	location.replace("<?php echo $http_path; ?>");
</script>
</body>
</html>
