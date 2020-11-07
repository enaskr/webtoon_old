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
	$webtoonDB->exec($sql_view);
?><script type='text/javascript'>
 window.history.back();
</script>
</body>
</html>