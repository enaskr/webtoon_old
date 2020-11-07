<?php
	include('../lib/header.php');
?>
<script type="text/javascript">
	function deleteUser(frm) {
		if ( confirm("회원을 삭제하시겠습니까?") ) {
			frm.action = "./userdelete.php";
			frm.submit();
		}
	}
	function changePassword(frm) {
<?php
	if ( $userID != "admin" && $userID != "jackie" ) {
?>
		if ( frm.userpassword.value == "" ) {
			alert("이전비밀번호를 입력해주세요.");
			return false;
		}
<?php
	}
?>
		if ( frm.newuserpassword.value == "" ) {
			alert("비밀번호를 입력해주세요.");
			return false;
		} else {
			frm.uptMode.value = "PWD";
			frm.action = "./userupdate.php";
			frm.submit();
		}
	}
	function changeUserinfo(frm) {
		if ( frm.useremail.value == "" && frm.userphone.value == "" ) {
			alert("이메일과 전화번호 중 1개는 반드시 입력해야합니다.");
			return false;
		} else {
			frm.uptMode.value = "info";
			frm.action = "./userupdate.php";
			frm.submit();
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo $_GET["userid"]; ?>님 회원 정보</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<form name="userForm" method="post" action="./userupdate.php"><input type="hidden" name="userid" value="<?php echo $_GET["userid"];  ?>"><input type="hidden" name="uptMode" value="info">
<?php
	if ( $userID == "admin" ) {
		$userList = "SELECT MBR_NO, USERID, PASSWORD, USERNAME, EMAIL, PHONE, Memo, STATUS, REGDTIME, UPTDTIME FROM TOON_USER WHERE USERID = '".$_GET["userid"]."'; ";
	} else {
		$userList = "SELECT MBR_NO, USERID, PASSWORD, USERNAME, EMAIL, PHONE, Memo, STATUS, REGDTIME, UPTDTIME FROM TOON_USER WHERE USERID = '".$userID."'; ";
	}
		$webtoonView = $webtoonDB->query($userList);
		$viewDate = "";
		$alreadView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$mbr_no = $row["MBR_NO"];
			$memuserID = $row["USERID"];
			$memuserPass = $row["PASSWORD"];
			$memuserName = $row["USERNAME"];
			$memuserEmail = $row["EMAIL"];
			$memuserPhone = $row["PHONE"];
			$memuserStatus = $row["STATUS"];
			$memuserMemo = $row["Memo"];
			$memuserCreated = $row["REGDTIME"];
			$memuserUpdated = $row["UPTDTIME"];
			if ( $memuserUpdated == null || strlen($memuserUpdated) < 14 ) $memuserUpdated = $memuserCreated;
			$memuserCreated = substr($memuserCreated,0,4).".".substr($memuserCreated,4,2).".".substr($memuserCreated,6,2)." ".substr($memuserCreated,8,2).":".substr($memuserCreated,10,2).":".substr($memuserCreated,12,2);
			$memuserUpdated = substr($memuserUpdated,0,4).".".substr($memuserUpdated,4,2).".".substr($memuserUpdated,6,2)." ".substr($memuserUpdated,8,2).":".substr($memuserUpdated,10,2).":".substr($memuserUpdated,12,2);
			if ( $memuserStatus == "WAIT" ) $waitstr = "selected"; else $waitstr="";
			if ( $memuserStatus == "REJECT" ) $rejectstr = "selected"; else $rejectstr="";
			if ( $memuserStatus == "OK" ) $okstr = "selected"; else $okstr="";
			if ( $memuserStatus == "APPROVED" ) $approvedstr = "selected"; else $approvedstr="";

			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td width=30%>회원번호</td><td>".$mbr_no."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>아이디</td><td>".$memuserID."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>이전비번</td><td><input style='border:none; line-height:48px; width:100%;' type='password' name='userpassword' value=''></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>변경비번</td><td><input style='border:none; line-height:48px; width:70%;' type='password' name='newuserpassword' value=''><input style='border:none; line-height:48px; width:30%;' type='button' value='비번 변경' onClick='changePassword(this.form);'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>성명</td><td>".$memuserName."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>이메일</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=useremail value='".$memuserEmail."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>전화번호</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=userphone value='".$memuserPhone."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>상태</td><td>";
			echo "<select name='userstatus' style='border:none;background-color:#f8f8f8;width:100%;'><option value='WAIT' ".$approvedstr.">승인대기</option><option value='REJECT' ".$rejectstr.">승인거절</option><option value='OK' ".$okstr.">정상</option><option value='APPROVED' ".$approvedstr.">승인완료</option></select></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>가입일</td><td>".$memuserCreated."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>메모</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=usermemo value='".$memuserMemo."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>정보변경일</td><td>".$memuserUpdated."</td></tr>";

		}
?>
						<tr style='background-color:#f8f8f8' height='50'><td colspan="2" align="center" valign="middle"><input style="border:none;line-height:48px;width:70%;font-weight:900;color:#0000ff;" type="submit" value="회원정보 변경"><input style="border:none;line-height:48px;width:30%;font-weight:900;color:#0000ff;" type="button" value="회원삭제" onClick="deleteUser(this.form);"></td></tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
