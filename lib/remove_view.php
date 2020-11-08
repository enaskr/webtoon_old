<?php
	include('header.php');

	$siteid = $_GET["siteid"];
	$toonid = $_GET["toonid"];
	$epiurl = $_GET["epiurl"];

	if ( $epiurl != null && strlen($epiurl)>0 ) {
		$sql_view = "DELETE FROM 'USER_VIEW_TOON' WHERE USERID='".$userID."' AND TOONSITEID='".$siteid."' AND TOONID='".$toonid."' AND EPIURL='".$epiurl."';";
	} else {
		$sql_view = "DELETE FROM 'USER_VIEW_TOON' WHERE USERID='".$userID."' AND TOONSITEID='".$siteid."' AND TOONID='".$toonid."';";
	}
	$cnt = $webtoonDB->exec($sql_view);
	if ( $cnt > 0 ) {
?>
<script type="text/javascript">
	alert("목록을 삭제했습니다.");
	window.history.back();
</script>
<?php
	} else {
?>
<script type="text/javascript">
	alert("삭제된 목록이 없습니다.");
	window.history.back();
</script>
<?php
	}
?></body>
</html>