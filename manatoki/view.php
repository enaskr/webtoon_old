<?php
	include('../lib/header.php');
?>
<?php
	$base_url = $manatoki_url;
	$target_episode = $base_url."comic/".$_GET["ws_id"];
	$title = urldecode($_GET['title']);
	$siteid = $manatoki_siteid;
	$toonid = $_GET["wr_id"];
	$toonurl = "/comic/".$toonid;
	$epiurl = str_replace($base_url,"/",$target_episode);
	$get_images = array();

	$url = $target_episode; //주소셋팅
	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36');
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($result) > 50000){
			break;
		} else {
			$result = curl_exec($ch);
		}
	}
	curl_close ($ch); // curl 종료

	$html_arr = explode("<title>", $result);
	$get_html_contents = str_get_html($result);

	foreach($get_html_contents->find('meta') as $e){
		if($e->name == "subject"){
			$epititle = $e->content;
		}
	}
	foreach($get_html_contents->find('a#goPrevBtn') as $e){
		if($e->alt == "이전화"){
			$prev_url = $e->href;
			$prevparse = explode('/' , $prev_url);
			$prevurl = $prevparse[4];
		}
	}
	foreach($get_html_contents->find('a#goNextBtn') as $e){
		if($e->alt == "다음화"){
			$next_url = $e->href;
			$nextparse = explode('/' , $next_url);
			$nexturl = $nextparse[4];
		}
	}


	$selector_arr = explode("data_attribute: '", $result);
	$selector = substr($selector_arr[1],0,11);
	$html_arr = explode("var html_data='';", $result);
	$html_arr = explode("html_encoder(html_data);", $html_arr[1]);
	$img_html = $html_arr[0];
	$img_html = str_replace("\n","",$img_html);
	$img_html = str_replace("\r","",$img_html);
	$img_html = str_replace("';","",$img_html);
	$img_html = str_replace("html_data+='","",$img_html);
	$img_html = "%".str_replace(".","%",$img_html);
	$img_html = urldecode($img_html);
	$get_img_dom = str_get_html($img_html);

	foreach($get_img_dom->find('p') as $e){
		$data_selector = $e->class;
	}

	$img_html = str_replace($selector, "src", $img_html);
	$img_html = str_replace("content=", "data-original=", $img_html);
	$get_img_dom = str_get_html($img_html);

	foreach($get_img_dom->find('div') as $e){
		if($e->class == "separator"){
			foreach($e->find('img') as $f){
				if($f->getAttribute("data-src") != null){
					$get_images[] = $f->getAttribute("data-src");
				}
			}
		}
	}

	if(count($get_images) < 1){
		foreach($get_img_dom->find('img') as $e){
			if(strpos($e->class, "page") !== false){
				if($e->getAttribute("data-src") != null){
					$get_images[] = $e->getAttribute("data-src");
				}
			}
		}
	}

	if(count($get_images) < 1){
		foreach($get_img_dom->find('img') as $e){
			if($e->getAttribute("data-src") == $e->src){
			} else {
				if($e->getAttribute("data-src") != null){
					$get_images[] = $e->getAttribute("data-src");
				}
			}
		}
	}

	if(count($get_images) < 1){
		foreach($get_img_dom->find('img') as $e){
			if($e->getAttribute("data-original") != null){
				$get_images[] = $e->getAttribute("data-original");
			}
		}
	}

?>
<script type="text/javascript">
		function view(mode) {
			if ( mode=="pageView") {
				document.getElementById("closeDiv1").style.display = "";
				document.getElementById("closeDiv2").style.display = "";
				document.getElementById("prevDiv").style.display = "";
				document.getElementById("nextDiv").style.display = "";
				document.getElementById("listview").style.display = "";
				document.getElementById("container").style.display = "none";
			} else {
				document.getElementById("closeDiv1").style.display = "none";
				document.getElementById("closeDiv2").style.display = "none";
				document.getElementById("prevDiv").style.display = "none";
				document.getElementById("nextDiv").style.display = "none";
				document.getElementById("listview").style.display = "none";
				document.getElementById("container").style.display = "";
			}
		}

		function prev() {
			if ( imageIndex > 0 ) {
				document.getElementById("imgview").src=img_list[imageIndex-1];
				imageIndex = imageIndex-1;
			} else {
				document.getElementById("imgview").src=img_list[0];
				imageIndex = 0;
			}
		}
		function next() {
			if ( imageIndex+1 >= imageSize ) {
				document.getElementById("imgview").src=img_list[imageSize-1];
				imageIndex = imageSize-1;
			} else {
				document.getElementById("imgview").src=img_list[imageIndex+1];
				imageIndex = imageIndex+1;
			}
		}
</script>

<div id="closeDiv1" style="display:none;top:5%;right:10px;position:absolute;" onClick="view('listView');"><img src="../lib/img/close.png" width="30" height="30"></div>
<div id="prevDiv" style="display:none;top:10%;left:10px;height:80%;width:45%;position:absolute;" valign="middle" onClick="prev();"></div>
<div id="nextDiv" style="display:none;top:10%;right:10px;height:80%;width:45%;position:absolute;" valign="middle" onClick="next();"></div>
<div id="closeDiv2" style="display:none;top:90%;left:10px;height:10%;width:95%;position:absolute;" onClick="view('listView');"></div>
<table id="listview" style="display:none;line-height:1.5;border-color:#ffffff;height:100%;" border=1 width="100%" cellspacing=0 cellpadding=0>
<tr style='background-color:#f8f8f8'>
	<td colspan="5" style='width:100%;height:100%;font-size:16px;color:#8000ff;' align=center valign=middle>
		<img id='imgview' src='' style='max-height:100%;max-width:100%;'>
		<script type="text/javascript">
			var imageIndex = 0;
<?php
	$imgidx = 0;
	echo "		var imageSize = ".sizeof($get_images).";\n";
	echo "		var img_list = new Array(";
	foreach($get_images as $images){
		if ( substr($images, 0,16) == "https://manatoki" && ( substr($images, 19,3) == "com" || substr($images, 19,3) == "net" )) {
			$images = str_replace(substr($images, 0,23), $base_url, $images);
		}
		if ( substr($images, 0,15) == "https://newtoki"  && ( substr($images, 18,3) == "com" || substr($images, 18,3) == "net" )) {
			$images = str_replace(substr($images, 0,22), $base_url, $images);
		}
		if ( sizeof($get_images) == $imgidx+1 ) {
			echo '"'.$images.'");';
		} else echo '"'.$images.'", ';
		$imgidx++;
	}
?>

		document.getElementById("imgview").src=img_list[0];

		function prev() {
			if ( imageIndex > 0 ) {
				document.getElementById("imgview").src=img_list[imageIndex-1];
				imageIndex = imageIndex-1;
			} else {
				document.getElementById("imgview").src=img_list[0];
				imageIndex = 0;
			}
		}
		function next() {
			if ( imageIndex+1 >= imageSize ) {
				document.getElementById("imgview").src=img_list[imageSize-1];
				imageIndex = imageSize-1;
			} else {
				document.getElementById("imgview").src=img_list[imageIndex+1];
				imageIndex = imageIndex+1;
			}
		}
		</script>
	</td>
</tr>
</table>

<div id='container' style="display:">
	<div class='item'>
		<dl>
			<dt><?php echo "<a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a> <a href='".$target_episode."'><img src='logo.png' height='25px'></a>"; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php

	if ( $isLogin ) {
		$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
		$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$toonid."' AND EPIID = '".$epiurl."' ";
		$isAlreadyView = $isAlreadyView." LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["REGDTIME"];
		}
		if ( strlen($viewDate) == 0 ) {
			$sql_view = "INSERT INTO 'USER_VIEW_TOON' ('USERID', 'TOONSITEID', 'TOONID', 'TOONTITLE', 'TOONURL', 'EPIID', 'EPITITLE', 'EPIURL', 'REGDTIME')";
			$sql_view = $sql_view." VALUES ('".$userID."','".$siteid."','".$toonid."','".$title."','".$toonurl."','".$epiurl."','".$epititle."', '".$this_url."','".$thisTime."'); ";
			$webtoonDB->exec($sql_view);
		} else {
			$sql_view = "UPDATE 'USER_VIEW_TOON' SET REGDTIME = '".$thisTime."' ";
			$sql_view = $sql_view."WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$toonid."' AND EPIID = '".$epiurl."';";
			$webtoonDB->exec($sql_view);
		}
	}

	if(count($get_images) < 1 || $get_images[0] == ""){
		echo "<tr style='background-color:#f8f8f8'><td style='width:10%;height:200px;font-size:16px;color:#8000ff;' align=center valign=middle>이미지를 불러올 수 없습니다.</td></tr>";
		exit();
	}
	$err_arr = array();
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prevurl)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($nexturl)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle onClick="view('pageView');">&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prevurl)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($nexturl)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
<?php

	foreach($get_images as $images){
		if ( substr($images, 0,16) == "https://manatoki" && ( substr($images, 19,3) == "com" || substr($images, 19,3) == "net" )) {
			$images = str_replace(substr($images, 0,23), $base_url, $images);
		}
		if ( substr($images, 0,15) == "https://newtoki"  && ( substr($images, 18,3) == "com" || substr($images, 18,3) == "net" )) {
			$images = str_replace(substr($images, 0,22), $base_url, $images);
		}
		echo "<img src='".$images."' width='100%'><br>";
	}
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prevurl)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($nexturl)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle onClick="view('pageView');">&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prevurl)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($nexturl)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</div>
</body>
</html>
