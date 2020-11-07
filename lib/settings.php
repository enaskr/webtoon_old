<?php
	$server_path = (str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = (str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]));
	include($server_path.'../lib/header.php');
?>
<script type="text/javascript">
	function saveSetting(frm) {
		if ( frm.CONF_NAME.value == "" ) {
			alert("conf_name을 입력해주세요.");
			return false;
		}
		if ( frm.CONF_VALUE.value == "" ) {
			if ( confirm(frm.CONF_NAME.value+"을 삭제하시겠습니까?") ) {
				frm.action = "./settingschange.php";
				frm.submit();
			} else {
				return false;
			}
		} else {
			frm.action = "./settingschange.php";
			frm.submit();
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>설정 목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<tr>
							<td width="110" align="center" style='font-size:15px;color:#000000;width:110px;'>Config Name</td><td align="center" style='font-size:15px;color:#000000;'>Value</td>
						</tr>
<?php

	if ( $userID != "admin" && $userID != "jackie" ) {
		$readonly = " readonly ";
	} else $readonly = "";
	$conf_result = $webtoonDB->query("SELECT CONF_NAME, CONF_VALUE, REGDTIME FROM TOON_CONFIG;");
	while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
		echo "<tr style='background-color:#f8f8f8'>\n";
		echo "<td style='font-size:15px;color:#8000ff;width:110px;' align=center valign=middle>".$conf["CONF_NAME"]."</td>\n";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle>";
		echo "<form method='post'><input type='hidden' name='CONF_NAME' value='".$conf["CONF_NAME"]."'>";
		if ( strpos($conf["CONF_NAME"], "_url") == true ) {
			echo "<input type='text' style='border:none; line-height:30px; width:80%;' name='CONF_VALUE' value='".$conf['CONF_VALUE']."' ".$readonly.">";
			echo "<img src='./shortcuts.png' style='padding:0;margin:0;max-height:30px;width:10%;' onClick='location.href=\"".urldecode($conf['CONF_VALUE'])."\";'>";
		} else {
			echo "<input type='text' style='border:none; line-height:30px; width:90%;' name='CONF_VALUE' value='".$conf['CONF_VALUE']."' ".$readonly.">";
		}
		echo "<input type='button' name='savebtn' value='S' style='border:none;line-height:30px;width:10%;' onClick='saveSetting(this.form);'></form></td>\n";
		echo "</tr>\n";
	}
	if ( $userID == "admin" || $userID == "jackie" ) {
		echo "<tr style='background-color:#f8f8f8'><form method='post'>";
		echo "<td style='font-size:15px;color:#8000ff;width:110px;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:110px;' name='CONF_NAME' value=''></td>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:90%;' name='CONF_VALUE' value=''><input type='button' name='savebtn' value='＋' style='border:none;line-height:30px;width:10%;' onClick='saveSetting(this.form);'></td></form>";
		echo "</tr>\n";
	}
?>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
