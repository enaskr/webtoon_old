<?php
	$cookieUserName = $_COOKIE["username"];
	$cookieUserID = $_COOKIE["userid"];
	$cookieUserEmail = $_COOKIE["useremail"];
	$cookieUserCreate = $_COOKIE["usercreate"];

	$webtoonDB = new SQLite3('../config/webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
//		echo "Database connection succeed!";
/*
		$sql_Client	= "CREATE TABLE IF NOT EXISTS  'TOON_USER' ( USERID TEXT NOT NULL PRIMARY KEY , USERNAME TEXT NOT NULL , MobilePhoneNumber TEXT , EMAIL TEXT , PASSWORD TEXT , Memo TEXT , REGDTIME TEXT , UPTDTIME TEXT ); ";
		$webtoonDB->query($sql_Client);   // 회원정보 : 아이디, 성명, 휴대전화, 이메일, 비밀번호, 메모, 가입일, 수정일
		echo "Database [TOON_USER] Created Succeed!";

		$sql_Client	= "CREATE TABLE IF NOT EXISTS  'TOON_SITE_INFO' ( TOONSITEID INTEGER NOT NULL PRIMARY KEY, TOONSITENAME TEXT NOT NULL, BASEURL TEXT NOT NULL, SEARCHURI TEXT, RECENTURI TEXT, ENDURI TEXT, UPTDTIME TEXT); ";
		$webtoonDB->query($sql_Client);   // 웹툰사이트정보 : 사이트ID, 사이트명, 주소, 검색URI, 최근URI, 완결URI, 수정일
		echo "Database [TOON_SITE_INFO] Created Succeed!";

		$sql_Client	= "CREATE TABLE IF NOT EXISTS  'USER_FAV_TOON' (FAVID INTEGER NOT NULL PRIMARY KEY, USERID TEXT NOT NULL, TOONSITEID INTEGER NOT NULL, TOONID TEXT NOT NULL, TOONTITLE TEXT, TOONURL TEXT, LASTTOON TEXT, RECENTTOONURL TEXT, REGDTIME TEXT, VIEWDTIME TEXT); ";
		$webtoonDB->query($sql_Client);   // 회원별웹툰 : 아이디, 사이트ID, 웹툰ID, 웹툰제목, 웹툰URL, 마지막회차, 마지막회차URL, 등록일, 조회일
		echo "Database [USER_FAV_TOON] Created Succeed!";
*/
/*
		// 인덱스 생성
		$sql_index_check		= "PRAGMA INDEX_LIST('StockLedger'); ";
		$results				= $webtoonDB->query($sql_index_check);
		$indexRow				= $results->fetchArray();
		if($indexRow[1] ==""){
			$sql_		=	"CREATE INDEX IDX_Date ON [StockLedger] ( Date )";
			$webtoonDB->query($sql_);
		}
*/
/*
		// INSERT, UPDATE
		$webtoonDB->exec("INSERT INTO 'TOON_USER' ('USERID', 'USERNAME', 'EMAIL', 'PASSWORD', 'Memo', 'REGDTIME') VALUES ('admin','관리자','help@enas.kr','thdns25thdns','관리자','20201024000000');");
		$webtoonDB->exec("INSERT INTO 'TOON_USER' ('USERID', 'USERNAME', 'MobilePhoneNumber', 'EMAIL', 'PASSWORD', 'Memo', 'REGDTIME') VALUES ('jackie','최창기','010-6426-3930','choi@changki.com','thdns25thdns','최창기','20201024000000');");
		$webtoonDB->exec("INSERT INTO 'TOON_USER' ('USERID', 'USERNAME', 'MobilePhoneNumber', 'EMAIL', 'PASSWORD', 'Memo', 'REGDTIME') VALUES ('sunny','최소운','010-5555-9577','sounchoi05@gmail.com','thdns0125','최소운','20201024000000');");
		echo "Database INSERT TOON_USER succeed!";
*/

		if ( $cookieUserName==null || strlen($cookieUserName) == 0 || ( strlen($cookieUserID)>0 and strlen($_GET['user'])>0 and $cookieUserID != $_GET['user']) ) {
			// SELECT
			$result = $webtoonDB->query("SELECT USERID, USERNAME, EMAIL, MobilePhoneNumber, REGDTIME FROM TOON_USER WHERE USERID = '".$_GET['user']."' and PASSWORD = '".$_GET['pass']."' LIMIT 1;");
			//echo "SQL="."SELECT USERID, USERNAME, EMAIL, MobilePhoneNumber, REGDTIME FROM TOON_USER WHERE USERID = '".$_GET['user']."' and PASSWORD = '".$_GET['pass']."';";
			while($row = $result->fetchArray(SQLITE3_ASSOC)){         
				$userID = $row["USERID"];
				$userName = $row["USERNAME"];
				$userEmail = $row["EMAIL"];
				$userPhone = $row["MobilePhoneNumber"];
				$userCreated = $row["REGDTIME"];

				setcookie("userid", $userID, time()+14400, "/");
				setcookie("username", $userName, time()+14400, "/");
				setcookie("useremail", $userEmail, time()+14400, "/");
				setcookie("usercreate", $userCreated, time()+14400, "/");
			}
		} else {
			$userID = $cookieUserID;
			$userName = $cookieUserName;
			$userEmail = $cookieUserEmail;
			$userCreated = $cookieUserCreate;
		}
/*
		// 첫행 1개 조회
		SELECT * FROM student ORDER BY ROWID LIMIT 1;
		// 마지막행 1개 조회
		SELECT * FROM student ORDER BY ROWID DESC LIMIT 1;
		// 임의의 행 1개 조회
		SELECT * FROM student ORDER BY RANDOM() LIMIT 1;
		// 테이블에 컬럼추가
		ALTER TABLE 테이블명 ADD COLUMN 컬럼명 데이터타입;
		// 테이블 삭제
		DROP TABLE 테이블명;
*/
	} else {
		echo "Database connection failed";
		echo $webtoonDB->lastErrorMsg();
	}
?>