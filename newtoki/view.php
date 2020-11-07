<?php
	include('../lib/header.php');

	$base_url = $newtoki_url;
	$target_episode = $base_url."webtoon/".$_GET["ws_id"];
	$title = urldecode($_GET['title']);

	$siteid = $newtoki_siteid;
	$toonid = $_GET["wr_id"];
	$toonurl = "/webtoon/".$toonid;
	$epiurl = str_replace($base_url,"/",$target_episode);

	$get_images = array();
	$url = $target_episode; //주소셋팅
	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36');
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	for($html_c = 0; $html_c < 3; $html_c++){
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
			$tempparse = explode('?', $prevparse[4]);
			$prevurl = $tempparse[0];
			$prev_url = str_replace($base_url,"/",$prev_url);
		}
	}
	foreach($get_html_contents->find('a#goNextBtn') as $e){
		if($e->alt == "다음화"){
			$next_url = $e->href;
			$nextparse = explode('/' , $next_url);
			$tempparse = explode('?', $nextparse[4]);
			$nexturl = $tempparse[0];
			$next_url = str_replace($base_url,"/",$next_url);
		}
	}
?>
<div id='container'>
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
			$sql_view = $sql_view." VALUES ('".$userID."','".$siteid."','".$toonid."','".$title."','".$toonurl."','".$epiurl."','".$epititle."', '".$this_url."', '".$thisTime."'); ";
			$webtoonDB->exec($sql_view);
		} else {
			$sql_view = "UPDATE 'USER_VIEW_TOON' SET UPTDTIME = '".$thisTime."' ";
			$sql_view = $sql_view."WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$toonid."' AND EPIID = '".$epiurl."';";
			$webtoonDB->exec($sql_view);
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
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevurl."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nexturl."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevurl."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nexturl."'>▶</a>";
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
?>
						<?php echo "<img src='".$images."' width='100%'><br>"; ?>
<?php
	}
?>
						</td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevurl."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nexturl."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevurl != null && strlen($prevurl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevurl."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nexturl != null && strlen($nexturl) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nexturl."'>▶</a>";
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
