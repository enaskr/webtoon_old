<?php
	include('../lib/header.php');
	if ( $userID == "admin" ) {
		$conf_sql = "SELECT TOONSITEID, TOONSITENAME, PATHNAME FROM TOON_SITE_INFO WHERE TOONSITEID='".$_POST["TOONSITEID"]."';";
		$conf_result = $webtoonDB->query($conf_sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$toonsiteid = $conf["TOONSITEID"];
			$toonsitename = $conf["TOONSITENAME"];
			$pathname = $conf["PATHNAME"];
			if ( strlen($_POST["PATHNAME"]) == 0 ) {
				$userList = "DELETE FROM TOON_SITE_INFO WHERE TOONSITEID='".$_POST["TOONSITEID"]."'; ";
				$webtoonDB->exec($userList)
?>
<script type="text/javascript">
	alert("사이트 정보를 정상적으로 삭제하였습니다.");
	window.history.back();
</script>
<?php
			} else if ( $conf_value != $_POST["PATHNAME"] ) {
				$userList = "UPDATE TOON_SITE_INFO SET PATHNAME='".$_POST["PATHNAME"]."', UPTDTIME='".date("YmdHis", time())."' WHERE TOONSITEID = '".$_POST["TOONSITEID"]."'; ";
				$query = $webtoonDB->exec($userList);
				if ( $query ) {
				if ( $webtoonDB->changes() > 0 ) {
?>
<script type="text/javascript">
	alert("사이트 정보를 정상적으로 변경하였습니다.");
	window.history.back();
</script>
<?php
				} else {
?>
<script type="text/javascript">
	alert("사이트 정보를 변경하지 못하였습니다.");
	window.history.back();
</script>
<?php
				}
				} else {
?>
<script type="text/javascript">
	alert("사이트 정보를 변경하지 못하였습니다.");
	window.history.back();
</script>
<?php
				}
			}
		}
	}
?>
</body>
</html>
