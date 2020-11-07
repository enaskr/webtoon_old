<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px"><br>
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>펀비 - 내가 본 목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
	$base_url = $funbe_url;
	$siteid = $funbe_siteid;

	$isAlreadyView = "SELECT A.USERID, A.TOONSITEID, A.TOONID, A.TOONTITLE, A.EPIID, A.EPIURL, A.EPITITLE, A.REGDTIME ";
	$isAlreadyView = $isAlreadyView." FROM USER_VIEW_TOON A, ";
	$isAlreadyView = $isAlreadyView." (SELECT USERID, TOONSITEID, TOONID, MAX(REGDTIME) AS REGDTIME ";
	$isAlreadyView = $isAlreadyView." FROM USER_VIEW_TOON GROUP BY USERID, TOONSITEID, TOONID) B ";
	$isAlreadyView = $isAlreadyView." WHERE A.USERID = '".$userID."' AND A.TOONSITEID = '".$siteid."' ";
	$isAlreadyView = $isAlreadyView." AND A.USERID=B.USERID AND A.TOONSITEID = B.TOONSITEID ";
	$isAlreadyView = $isAlreadyView." AND A.TOONID = B.TOONID AND A.REGDTIME = B.REGDTIME ";
	$isAlreadyView = $isAlreadyView." ORDER BY A.TOONTITLE, A.REGDTIME DESC;";
	$webtoonView = $webtoonDB->query($isAlreadyView);
	$viewDate = "";
	$alreadView = "";
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$toonid = $row["TOONID"];
		$toontitle = $row["TOONTITLE"];
		$ws_id = $row["EPIID"];
		$epiid = substr($ws_id,1);
		$epititle = $row["EPITITLE"];
		$regdtime = $row["REGDTIME"];
		$dbepiurl = $row["EPIURL"];

		echo "<tr style='background-color:#f8f8f8'><td style='width:40px;font-size:16px;color:#8000ff;' align=center valign=middle><a style='margin:0px;padding:0px;' href='list.php?title=".urlencode($toontitle)."&wr_id=".urlencode($toonid)."'>목록</a></td> ";
		echo "<td style='word-wrap:break-word;height:50px;' valign=middle><a style='margin:0px;padding:0px;font-size:14px;' href='view.php?title=".urlencode($toontitle)."&wr_id=".urlencode($toonid)."&ws_id=".urlencode($epiid)."'>".$epititle;
		echo "<br><span style='font-size:12px;'>(".$regdtime.")</span></a></td> ";
		echo "<td style='width:40px;font-size:16px;color=#ff3232;' align=center valign=middle><a style='margin:0px;padding:0px;' href='../lib/remove_view.php?siteid=".$siteid."&toonid=".urlencode($toonid)."'>삭제</a></td>";
		echo "</tr>\n";
	}
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
		<!--// item : E -->
	</div>
</body>
</html>
