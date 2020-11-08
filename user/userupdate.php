<?php
	include('../lib/header.php');
	$isSuccess = true;

	if ( $userID == "admin" ) {
		if ( $_POST["uptMode"] == "PWD" ) {
			$userList = "UPDATE TOON_USER SET PASSWORD='".strtoupper(hash("sha256", $_POST["newuserpassword"]))."', UPTDTIME='".date("YmdHis", time())."' WHERE USERID = '".$_POST["userid"]."'; ";
		} else {
			$userList = "UPDATE TOON_USER SET EMAIL='".$_POST["useremail"]."', PHONE='".$_POST["userphone"]."', STATUS='".$_POST["userstatus"]."', Memo='".$_POST["usermemo"]."', UPTDTIME='".date("YmdHis", time())."' WHERE USERID = '".$_POST["userid"]."'; ";
		}
	} else {
		if ( $_POST["uptMode"] == "PWD" ) {
			$userPass = "SELECT MBR_NO, USERID, PASSWORD, USERNAME, EMAIL, PHONE, Memo, STATUS, REGDTIME, UPTDTIME FROM TOON_USER WHERE USERID = '".$userID."' AND PASSWORD = '".strtoupper(hash("sha256", $_POST["userpassword"]))."'; ";
			$passView = $webtoonDB->query($userPass);
			while($row = $passView->fetchArray(SQLITE3_ASSOC)){
				$selUserid = $row["USERID"];
				$selPasswd = $row["PASSWORD"];
			}
			if ( $selUserid == null || strlen($selUserid) == 0 ) {
				$isSuccess = false;
			} else {
				$userList = "UPDATE TOON_USER SET PASSWORD='".strtoupper(hash("sha256", $_POST["newuserpassword"]))."', UPTDTIME='".date("YmdHis", time())."' WHERE USERID = '".$userID."'  AND PASSWORD = '".strtoupper(hash("sha256", $_POST["userpassword"]))."'; ";
			}
		} else {
			$userList = "UPDATE TOON_USER SET EMAIL='".$_POST["useremail"]."', PHONE='".$_POST["userphone"]."', Memo='".$_POST["usermemo"]."', UPTDTIME='".date("YmdHis", time())."' WHERE USERID = '".$userID."'; ";
		}
	}
		if ( $isSuccess ) {
			$cnt = $webtoonDB->exec($userList);
			if ( $cnt == 1 ) {
?>
<script type="text/javascript">
	alert("회원정보를 정상적으로 변경하였습니다.");
	window.history.back();
</script>
<?php
			} else {
?>
<script type="text/javascript">
	alert("회원정보를 변경하지 못하였습니다.");
	window.history.back();
</script>
<?php
			}
		} else {
?>
<script type="text/javascript">
	alert("이전 비밀번호가 다릅니다. 다시 입력해주세요.");
	window.history.back();
</script>
<?php
		}
?>
</body>
</html>
