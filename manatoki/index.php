<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px"><br>
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php
	include('../lib/simple_html_dom.php');

	$base_url = $newtoki_url;
	$list_count = $config['list_count'];

	if($_GET['keyword'] != null){
		$target = $base_url."comic/p1?bo_table=comic&stx=".$_GET['keyword'];

		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($target);
			}
		}
				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < $list_count ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
							$targeturl = str_replace("?stx=".$_GET['keyword'],"",$target_link);
							$targeturl = str_replace("?stx=".urlencode($_GET['keyword']),"",$targeturl);
							$targeturl = str_replace("&page=1","",$targeturl);
							break;
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}

							if($e->class == "in-lable trans-bg-black"){
								$subject = trim(strip_tags($e));
							}
							if($e->class == "list-artist trans-bg-yellow en"){
								$author = trim(strip_tags($e));
							}
							if($e->class == "list-publish trans-bg-blue en"){
								$publish = trim(strip_tags($e));
							}

						}
						$titleparse = explode('/' , $targeturl);
						$wr_id = $titleparse[4];

						echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."><img class='rounded-lg' src=".$img_link." style='float:left; margin-right:20px;' width='180px'></a><p style='height:100px;display: table-cell;vertical-align: middle;'><b><font size=3>";
						echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".str_replace("-"," ",$subject)."(".$wr_id.")</a></font><br>";
						echo "[작가:".$author."][발행주기:<font color=red>".$publish."</font>]</p></span></div>";
					} else {
						break;
					}
					$toonidx++;
				}
	} else {
		if ( $_GET["end"] != "END" ) {
			for($p = 1; $p <= 1; $p++) {
				$get_html_contents = file_get_html($base_url."comic/p".$p);
				for($html_c = 0; $html_c < $try_count; $html_c++){
					if(strlen($get_html_contents) > 50000){
						break;
					} else {
						$get_html_contents = file_get_html($base_url."comic/p".$p);
					}
				}
				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < $list_count ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
							$targeturl = str_replace("?page=1&toon=완결웹툰","",$target_link);
							$targeturl = str_replace("?page=1&toon=일반웹툰","",$targeturl);
							$targeturl = str_replace("?page=1","",$targeturl);
							break;
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}

							if($e->class == "in-lable trans-bg-black"){
								$subject = trim(strip_tags($e));
							}
							if($e->class == "list-artist trans-bg-yellow en"){
								$author = trim(strip_tags($e));
							}
							if($e->class == "list-publish trans-bg-blue en"){
								$publish = trim(strip_tags($e));
							}

						}
						$titleparse = explode('/' , $targeturl);
						$wr_id = $titleparse[4];

						echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."><img class='rounded-lg' src=".$img_link." style='float:left; margin-right:20px;' width='180px'></a><p style='height:100px;display: table-cell;vertical-align: middle;'><b><font size=3>";
						echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".str_replace("-"," ",$subject)."(".$wr_id.")</a></font><br>";
						echo "[작가:".$author."][발행주기:<font color=red>".$publish."</font>]</p></span></div>";
					} else {
						break;
					}
					$toonidx++;
				}
			}
		} else {
			for($p = 1; $p <= 7; $p++) {
				$get_html_contents = file_get_html($base_url."comic/p".$p."?publish=%EC%99%84%EA%B2%B0");
				for($html_c = 0; $html_c < $try_count; $html_c++){
					if(strlen($get_html_contents) > 50000){
						break;
					} else {
						$get_html_contents = file_get_html($base_url."comic/p".$p."?publish=%EC%99%84%EA%B2%B0");
					}
				}
				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < $list_count ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
							$targeturl = str_replace("?page=1&publish=%EC%99%84%EA%B2%B0","",$target_link);
							$targeturl = str_replace("?page=1&publish=완결","",$targeturl);
							$targeturl = str_replace("?page=1","",$targeturl);
							break;
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}

							if($e->class == "in-lable trans-bg-black"){
								$subject = trim(strip_tags($e));
							}
							if($e->class == "list-artist trans-bg-yellow en"){
								$author = trim(strip_tags($e));
							}
							$publish = "완결";
						}
						$titleparse = explode('/' , $targeturl);
						$wr_id = $titleparse[4];

						echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."><img class='rounded-lg' src=".$img_link." style='float:left; margin-right:20px;' width='180px'></a><p style='height:100px;display: table-cell;vertical-align: middle;'><b><font size=3>";
						echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".str_replace("-"," ",$subject)."(".$wr_id.")</a></font><br>";
						echo "[작가:".$author."][발행주기:<font color=red>".$publish."</font>].</p></span></div>";
					} else {
						break;
					}
					$toonidx++;
				}
			}
		}
	}
?>
</body>
</html>