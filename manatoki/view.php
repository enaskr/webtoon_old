<?php
	include('../lib/header.php');
?>
<?php
include('../lib/simple_html_dom.php');

	$base_url = $manatoki_url;
	$target_episode = $base_url."comic/".$_GET["ws_id"];
	$title = urldecode($_GET['title']);

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

	echo "<font size=4><b><a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a></b></font> <a href='".$target_episode."'><img src='logo.png' height='25px'></a><br>\n";

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
		echo "이미지를 불러올 수 없습니다. <a href='list.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."'>".$title."</a> 리스트 보기";
		exit();
	}
	$err_arr = array();

	if(strpos($prev_url, "http") !== false){
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&target=".urlencode($prevurl)."'>이전화 보기</a></b> | </font>";
	} else {
		echo "이전화없음 | ";
	}
	if(strpos($next_url, "http") !== false){
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&target=".urlencode($nexturl)."'>다음화 보기</a></b></font>";
	} else {
		echo "다음화없음";
	}
	echo " <br> ";

	foreach($get_images as $images){
		if ( substr($images, 0,16) == "https://manatoki" ) {
			$images = str_replace(substr($images, 0,16).substr($images, 16,2).".net/", $base_url, $images);
		}
		if ( substr($images, 0,15) == "https://newtoki" ) {
			$images = str_replace(substr($images, 0,15).substr($images, 15,2).".com/", $base_url, $images);
		}
		echo "<img src='".$images."' width='100%'><br>";
	}

	if(strpos($prev_url, "http") !== false){
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&target=".urlencode($prevurl)."'>이전화 보기</a></b> | </font>";
	} else {
		echo "이전화없음 | ";
	}
	if(strpos($next_url, "http") !== false){
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&target=".urlencode($nexturl)."'>다음화 보기</a></b></font>";
	} else {
		echo "다음화없음";
	}
?>