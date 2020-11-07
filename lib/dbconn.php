<?php
	$cookieMbrNo = $_COOKIE["MBRNO"];
	$isLogin = false;
	$webtoonDB = new SQLite3($server_path.'../lib/webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
			$conf_result = $webtoonDB->query("SELECT CONF_NAME, CONF_VALUE, REGDTIME FROM TOON_CONFIG;");
			while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){         
				if ( $conf["CONF_NAME"] == "newtoki_url" ) $newtoki_url = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "manatoki_url" ) $manatoki_url = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "toonkor_url" ) $toonkor_url = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "funbe_url" ) $funbe_url = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "11toon_url" ) $toon11_url = $conf['CONF_VALUE'];

				if ( $conf["CONF_NAME"] == "list_count" ) $list_countdb = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "try_count" ) $try_countdb = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "is_adult" ) $is_adultdb = $conf['CONF_VALUE'];
				if ( $conf["CONF_NAME"] == "login_view" ) $login_viewdb = $conf['CONF_VALUE'];

				$list_count = (int) $list_countdb;
				$try_count = (int) $try_countdb;

				if ( $is_adultdb != "true" ) $is_adult = false;
				else $is_adult = true;
				if ( $login_viewdb != "true" ) $login_view = false;
				else $login_view = true;
			}

			$conf_result = $webtoonDB->query("SELECT TOONSITEID, PATHNAME FROM TOON_SITE_INFO;");
			while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){         
				if ( $conf["CONF_NAME"] == "newtoki_url" ) {
					$newtoki_path = $conf['PATHNAME'];
					$newtoki_siteid = $conf['TOONSITEID'];
				}
				if ( $conf["CONF_NAME"] == "manatoki_url" ) {
					$manatoki_path = $conf['PATHNAME'];
					$manatoki_siteid = $conf['TOONSITEID'];
				}
				if ( $conf["CONF_NAME"] == "toonkor_url" ) {
					$toonkor_path = $conf['PATHNAME'];
					$toonkor_siteid = $conf['TOONSITEID'];
				}
				if ( $conf["CONF_NAME"] == "funbe_url" ) {
					$funbe_path = $conf['PATHNAME'];
					$funbe_siteid = $conf['TOONSITEID'];
				}
				if ( $conf["CONF_NAME"] == "11toon_url" ) {
					$toon11_path = $conf['PATHNAME'];
					$toon11_siteid = $conf['TOONSITEID'];
				}
			}

		if ( $cookieMbrNo != null && strlen($cookieMbrNo) > 0 ) {
			define('KEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
			define('KEY_128', substr(KEY,0,128/8));
			define('KEY_256', substr(KEY,0,256/8));
			$mbr_no = openssl_decrypt($cookieMbrNo, 'AES-256-CBC', KEY_256, 0, KEY_128);

			$result = $webtoonDB->query("SELECT MBR_NO, USERID, USERNAME, EMAIL, PHONE, REGDTIME FROM TOON_USER WHERE MBR_NO = '".$mbr_no."' AND STATUS IN ('OK','APPROVED') LIMIT 1;");
			while($row = $result->fetchArray(SQLITE3_ASSOC)){         
				$userMbrno = $row["MBR_NO"];
				$userID = $row["USERID"];
				$userName = $row["USERNAME"];
				$userEmail = $row["EMAIL"];
				$userPhone = $row["PHONE"];
				$userCreated = $row["REGDTIME"];
				$isLogin = true;

				define('KEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
				define('KEY_128', substr(KEY,0,128/8));
				define('KEY_256', substr(KEY,0,256/8));
				$mbr_no = openssl_encrypt($userMbrno, 'AES-256-CBC', KEY_256, 0, KEY_128);
				setcookie("MBRNO", $mbr_no, time()+14400, "/");
			}
		}
	} else {
		echo "Database connection failed";
		echo $webtoonDB->lastErrorMsg();
	}
?>