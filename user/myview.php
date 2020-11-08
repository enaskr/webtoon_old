<?php
	include($server_path.'../lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>내가 본 목록 (전체)</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php

	$base_url = $newtoki_url;
	$list_count = $config['list_count'];
	$siteid = $newtoki_siteid;

	$isAlreadyView = "SELECT A.USERID, A.TOONSITEID, (SELECT TOONSITENAME FROM TOON_SITE_INFO WHERE TOONSITEID = A.TOONSITEID LIMIT 1) AS TOONSITENAME, ";
	$isAlreadyView = $isAlreadyView." (SELECT PATHNAME FROM TOON_SITE_INFO WHERE TOONSITEID = A.TOONSITEID LIMIT 1) AS PATHNAME, A.TOONID, A.TOONTITLE, A.EPIID, A.EPIURL, A.EPITITLE, A.ADDPARAM, A.REGDTIME ";
	$isAlreadyView = $isAlreadyView." FROM USER_VIEW_TOON A, ";
	$isAlreadyView = $isAlreadyView." (SELECT USERID, TOONSITEID, TOONID, MAX(REGDTIME) AS REGDTIME ";
	$isAlreadyView = $isAlreadyView." FROM USER_VIEW_TOON GROUP BY USERID, TOONSITEID, TOONID) B ";
	$isAlreadyView = $isAlreadyView." WHERE A.USERID = '".$userID."' ";
	$isAlreadyView = $isAlreadyView." AND A.USERID=B.USERID AND A.TOONSITEID = B.TOONSITEID ";
	$isAlreadyView = $isAlreadyView." AND A.TOONID = B.TOONID AND A.REGDTIME = B.REGDTIME ";
	$isAlreadyView = $isAlreadyView." ORDER BY A.TOONSITEID, A.TOONTITLE, A.REGDTIME DESC;";
	//echo "SQL=".$isAlreadyView."<br>";
	$webtoonView = $webtoonDB->query($isAlreadyView);
	$viewDate = "";
	$alreadView = "";
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$toonsite = $row["TOONSITENAME"];
		$toonsiteid = $row["TOONSITEID"];
		$toonid = $row["TOONID"];
		$pathname = $row["PATHNAME"];
		$toontitle = $row["TOONTITLE"];
		$epiurl = $row["EPIURL"];
		$epiid = $row["EPIID"];
		if (lastIndexOf($epiurl, "=") > 20) {
			$type = "&type=".substr($epiurl, lastIndexOf($epiurl, "="));
		}
		$epititle = $row["EPITITLE"];
		$addparam = $row["ADDPARAM"];
		$regdtime = $row["REGDTIME"];

		echo "<tr style='background-color:#f8f8f8'><td style='width:65px;font-size:16px;color:#8000ff;' align=center valign=middle>";
		echo "<a style='margin:0px;padding:0px;' href='".$http_path."../".$pathname."/myview.php'>".$toonsite."</a></td>";
		echo "<td style='width:35px;font-size:16px;color:#8000ff;' align=center valign=middle><a style='margin:0px;padding:0px;' href='".$http_path."../".$pathname."/list.php?title=".urlencode($toontitle)."&wr_id=".urlencode($toonid).$type."'>목록</a></td> ";
		echo "<td style='word-wrap:break-word;height:50px;' valign=middle><a style='margin:0px;padding:0px;font-size:14px;' href='".$http_path."../".$pathname."/".$epiurl."'>".$epititle;
		echo "<br><span style='font-size:12px;'>(".$regdtime.")</span></a></td> ";
		echo "<td style='width:35px;word-wrap:break-word;height:50px;' valign=middle><a style='margin:0px;padding:0px;font-size:14px;' href='".$http_path."../lib/remove_view.php?title=".urlencode($toontitle)."&siteid=".$toonsiteid."&toonid=".urlencode($toonid).$type."'>삭제</a></td>";
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
