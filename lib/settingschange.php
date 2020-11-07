<?php
	$server_path = (str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = (str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]));
	include($server_path.'../lib/header.php');
	if ( $userID == "admin" || $userID == "jackie" ) {
		$conf_sql = "SELECT CONF_NAME, CONF_VALUE, REGDTIME FROM TOON_CONFIG WHERE CONF_NAME='".$_POST["CONF_NAME"]."';";
		$conf_result = $webtoonDB->query($conf_sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$conf_name = $conf["CONF_NAME"];
			$conf_value = $conf["CONF_VALUE"];
			if ( strlen($_POST["CONF_VALUE"]) == 0 ) {
				$userList = "DELETE FROM TOON_CONFIG WHERE CONF_NAME = '".$_POST["CONF_NAME"]."'; ";
				$webtoonDB->exec($userList)
?>
<script type="text/javascript">
	alert("설정 정보를 정상적으로 삭제하였습니다.");
	window.history.back();
</script>
<?php
			} else if ( $conf_value != $_POST["CONF_VALUE"] ) {
				$userList = "UPDATE TOON_CONFIG SET CONF_VALUE='".$_POST["CONF_VALUE"]."', REGDTIME='".date("YmdHis", time())."' WHERE CONF_NAME = '".$_POST["CONF_NAME"]."'; ";
				$webtoonDB->exec($userList)
?>
<script type="text/javascript">
	alert("설정 정보를 정상적으로 변경하였습니다.");
	window.history.back();
</script>
<?php
			}
		}
		if ( $conf_name != $_POST["CONF_NAME"] ) { 
			$userList = "INSERT INTO TOON_CONFIG (CONF_NAME, CONF_VALUE, REGDTIME) VALUES ('".$_POST["CONF_NAME"]."', '".$_POST["CONF_VALUE"]."', '".date("YmdHis", time())."'); ";
			$webtoonDB->exec($userList)
?>
<script type="text/javascript">
	alert("설정 정보를 정상적으로 추가하였습니다.");
	window.history.back();
</script>
<?php
		}
	}
?>
</body>
</html>
