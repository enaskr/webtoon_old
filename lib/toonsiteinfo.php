<?php
	include('../lib/header.php');
?>
<script type="text/javascript">
	function saveSetting(frm) {
		if ( frm.PATHNAME.value == "" ) {
			if ( confirm(frm.TOONSITENAME.value+"을 삭제하시겠습니까?") ) {
				frm.action = "./toonsiteupdate.php";
				frm.submit();
			} else {
				return false;
			}
		} else {
			frm.action = "./toonsiteupdate.php";
			frm.submit();
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>웹툰 경로 목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<tr>
							<td align="center" style='width:15%;height:25px;font-size:25px;font-weight:bold;color:#000000;'>ID</td><td align="center" style='width:20%;height:25px;font-size:25px;font-weight:bold;color:#000000;'>사이트명</td><td align="center" style='width:20%;height:25px;font-size:25px;font-weight:bold;color:#000000;'>폴더명</td><td align="center" style='width:15%;height:25px;font-size:25px;font-weight:bold;color:#000000;'>저장</td>
						</tr>
<?php
	if ( $userID == "admin" ) {
		$toonsiteList = "SELECT TOONSITEID, TOONSITENAME, BASEURL, SEARCHURI, RECENTURI, ENDURI, PATHNAME, UPTDTIME FROM TOON_SITE_INFO ORDER BY TOONSITEID; ";

		$webtoonView = $webtoonDB->query($toonsiteList);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$toonsiteid = $row["TOONSITEID"];
			$toonsitename = $row["TOONSITENAME"];
			$baseurl = $row["BASEURL"];
			$searchuri = $row["SEARCHURI"];
			$recenturi = $row["RECENTURI"];
			$enduri = $row["ENDURI"];
			$pathname = $row["PATHNAME"];
			$uptdtime = $row["UPTDTIME"];
			$uptdtime = substr($uptdtime,0,4).".".substr($uptdtime,4,2).".".substr($uptdtime,6,2);

			echo "<tr style='background-color:#f8f8f8;height:30px;'><form method='post' action='./toonsiteupdate.php'>";
			echo "<td style='width:15%; font-size:20px;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' name='TOONSITEID' value='".$toonsiteid."' readonly></td>";
			echo "<td style='width:30%; font-size:20px;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;color:#3300ff;background-color: #f8f8f8;' name='TOONSITENAME' value='".$toonsitename."' readonly></td>";
			echo "<td style='width:30%; font-size:20px;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' name='PATHNAME' value='".$pathname."'></td>";
			echo "<td style='width:15%; font-size:20px;' align=center valign=middle><input type='button' name='savebtn' value='저장' style='border:none;line-height:30px;width:100%;font-weight:bold;background-color: #f8f8f8;' onClick='saveSetting(this.form);'></td>";
			echo "</form></tr>\n";
		}
	}


?>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
