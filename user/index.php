<?php
	include('../lib/header.php');
?>
<script type="text/javascript">
	var isFold = false;
	function chkFold() {
		if ( isFold ) {
			document.getElementById("subTable").style.display = "none";
			document.getElementById("fold").innerHTML = "▼ ▼ ▼ ▼ 펼치기 ▼ ▼ ▼ ▼";
			isFold = false;
		} else {
			document.getElementById("subTable").style.display = "";
			document.getElementById("fold").innerHTML = "▲ ▲ ▲ ▲ 접기 ▲ ▲ ▲ ▲";
			isFold = true;
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>회원 목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table border=0 width="100%" cellspacing=0 cellpadding=0>
					<tr><td>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<tr>
							<td rowspan="2" width="70" align="center" style='font-size:15px;color:#000000;'>회원번호</td><td width="60" align="center" style='font-size:15px;color:#000000;'>아이디</td><td align="center" style='font-size:15px;color:#000000;'>이메일</td><td width="80" align="center" style='font-size:15px;color:#000000;'>상태</td>
						</tr>
						<tr>
							<td align="center" style='font-size:15px;color:#000000;'>성명</td><td align="center" style='font-size:15px;color:#000000;'>전화번호</td><td align="center" style='font-size:15px;color:#000000;'>가입일</td>
						</tr>
<?php
	if ( $userID == "admin" || $userID == "jackie" ) {
		$userList = "SELECT MBR_NO, USERID, USERNAME, EMAIL, PHONE, CASE WHEN STATUS='OK' THEN '정상' WHEN STATUS='WAIT' THEN '승인대기' WHEN STATUS='REJECT' THEN '승인거절' WHEN STATUS='APPROVED' THEN '승인완료' END AS STATUS, REGDTIME FROM TOON_USER ORDER BY REGDTIME DESC, USERID; ";
	} else {
		$userList = "SELECT MBR_NO, USERID, USERNAME, EMAIL, PHONE, CASE WHEN STATUS='OK' THEN '정상' WHEN STATUS='WAIT' THEN '승인대기' WHEN STATUS='REJECT' THEN '승인거절' WHEN STATUS='APPROVED' THEN '승인완료' END AS STATUS, REGDTIME FROM TOON_USER WHERE USERID='".$userID."' ORDER BY REGDTIME DESC, USERID; ";
	}

	$webtoonView = $webtoonDB->query($userList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$memmbr_no = $row["MBR_NO"];
		$memuserID = $row["USERID"];
		$memuserName = $row["USERNAME"];
		$memuserEmail = $row["EMAIL"];
		$memuserPhone = $row["PHONE"];
		$memuserStatus = $row["STATUS"];
		$memuserCreated = $row["REGDTIME"];
		$memuserCreated = substr($memuserCreated,0,4).".".substr($memuserCreated,4,2).".".substr($memuserCreated,6,2);

		echo "<tr style='background-color:#f8f8f8' onClick=\"location.href='./userdetail.php?userid=".$memuserID."';\">";
		echo "<td rowspan='2' style='font-size:15px;color:#8000ff;' align=center valign=middle><a href='./userdetail.php?userid=".$memuserID."'>".substr($memmbr_no,8)."</a></td>";
		echo "<td style='height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserID."</td>";
		echo "<td style='height:30px;font-size:13px;color:#8000ff;' align=center valign=middle>".$memuserEmail."</td>";
		echo "<td style=;height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserStatus."</td>";
		echo "</tr>\n";
		echo "<tr style='background-color:#f8f8f8' onClick=\"location.href='./userdetail.php?userid=".$memuserID."';\">";
		echo "<td style='height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserName."</td>";
		echo "<td style='height:30px;font-size:13px;color:#8000ff;' align=center valign=middle>".$memuserPhone."</td>";
		echo "<td style='height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserCreated."</td>";
		echo "</tr>\n";

	}
?>
						<tr style='background-color:#f8f8f8' height='5'><td colspan="4" width="100%" align="center" valign="middle"></td></tr>
						<tr style='background-color:#f8f8f8' height='50'><td colspan="4" width="100%" align="center" valign="middle"><input style="border:none;line-height:48px;width:100%;font-weight:900;color:#0000ff;" type="button" value="내가 본 웹툰(전체)" onClick="location.href='./myview.php';"></td></tr>
					</table>

<?php
	if ( $userID == "admin" ) {
?>
					<table style="line-height:1.5;border-color:#ffffff;" border=0 width="100%" cellspacing=0 cellpadding=0>
						<tr style='height:30px;background-color:#f8f8f8' width="100%" height='10'><td width="100%" align="center" valign="middle" id="fold" onClick="chkFold();">▼ ▼ ▼ ▼ 펼치기 ▼ ▼ ▼ ▼</td></tr>
					</table>
					<table id="subTable" style="display:none;width:100%;line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<tr style='background-color:#f8f8f8' height='50' width="100%"><td width="100%" align="center" valign="middle"><input style="border:none;line-height:48px;width:100%;font-weight:900;color:#0000ff;" type="button" value="회원 등록" onClick="location.href='./userform.php';"></td></tr>
						<tr style='background-color:#f8f8f8' height='5'><td width="100%" align="center" valign="middle"></td></tr>
						<tr style='background-color:#f8f8f8' height='50'><td width="100%" align="center" valign="middle"><input style="border:none;line-height:48px;width:100%;font-weight:900;color:#0000ff;" type="button" value="서버설정 변경" onClick="location.href='../lib/settings.php';"></td></tr>
						<tr style='background-color:#f8f8f8' height='5'><td width="100%" align="center" valign="middle"></td></tr>
						<tr style='background-color:#f8f8f8' height='50'><td width="100%" align="center" valign="middle"><input style="border:none;line-height:48px;width:100%;font-weight:900;color:#0000ff;" type="button" value="웹툰 경로 정보" onClick="location.href='../lib/toonsiteinfo.php';"></td></tr>
					</table>
<?php
	}
?>
					</table>
					</td></tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
