<?php
function pg_connection_string_from_database_url() {
    extract(parse_url($_ENV["DATABASE_URL"]));
    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
}

function writeData($sql) {
    # This function reads your DATABASE_URL config var and returns a connection
    # string suitable for pg_connect. Put this in your app.

    # Here we establish the connection. Yes, that's all.
    $pg_conn = pg_connect(pg_connection_string_from_database_url());

	$result = pg_query($pg_conn, $sql);
    Return $result;

}

$access_token = 'ki/sALGeAGtfPJsCbQY+Ama0bBSByknlDdsU32D1fnAGwt2/L9KqasU/HxA9ojgPHNcSaItAV2cJEasYBZj1qQ+dZOEt7ZKaTz/OG7ZZNISFHh4NWE/P5Mg7hX84D+AZtaYHVjv2VS9oQiObD6Kl+QdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');

$content_sql = $content;

$sql = " INSERT INTO \"Fr_User_Log\"(
	\"Request\", \"ReplyConfirm\", \"CreateDate\")
	VALUES ('$content_sql', 'N', now())";

writeData($sql);

$events = json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		
		//$userId = $event['source']['userid'];
		$userId = '123456789';
		
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			
			error_log('$text >>>>>>>>>'.$text.'<<<<<<<< $text');
	
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
            
			$messages = 'X';
			$messages_2 = 'X';
			
			$msg = array();
			
			if (strtolower($text)  == 'im') {
				$messages = [
						'type' => 'image',
						'originalContentUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
						'previewImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig_pre.jpg'
				];			
			}	

			if (strtolower($text)  == 'st') {
				$messages = [
						'type' => 'sticker',
						'packageId' => '1',
						'stickerId' => '15'
				];			
			}	
			
			if (strtolower($text)  == 'cf') {
				$messages = [
						'type' => 'template',
						'altText' => 'this is a confirm  template',
						'template' => [
							'type' => 'confirm',
							'text' => 'ยืนยันยอดสั่งซื้อ ?',
							'actions' => array(
								[
								'type' => 'message',
								'label' => 'Yes',
								'text' => 'Yes.',
								],[
								'type' => 'message',
								'label' => 'No',
								'text' => 'No.',									
								]
							)
						]
				];			
			}			
            
			if (strtolower($text)  == 'con') {
				$messages = [
						'type' => 'template',
						'altText' => 'this is a buttons template',
						'template' => [
							'type' => 'carousel',
							'columns' => array (
									[
										'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
										'title' => 'Record Data',
										'text' => 'บันทึกข้อมูล',
										'actions' => array(
											[
												'type' => 'postback',
												'label' => 'ตาย/คัดทิ้ง',
												'data' => 'action=buy&itemid=123',
												'text' => '<บันทึกตาย>',
											],
											[
												'type' => 'postback',
												'label' => 'ใข้อาหาร',
												'data' => 'action=buy&itemid=123',
												'text' => '<กำลังบันทึกใช้อาหาร>',
											]
									)],							
/* 									[
										'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/logostockcf.png',
										'title' => 'Stock',
										'text' => 'รายงานคงเหลือ ',
										'actions' => array(
											[
											'type' => 'postback',
											'label' => 'ต้องการบันทึก',
											'data' => 'action=buy&itemid=123',
											],[
											'type' => 'uri',
											'label' => 'View detail',
											'uri' => 'https://www.google.com/',							
											]
									)],	
									[
										'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/po.jpg',
										'title' => 'Order',
										'text' => 'สั่งซื้อ ชิ้นส่วนสุกร ',
										'actions' => array(
											[
											'type' => 'postback',
											'label' => 'สั่งซื้อ',
											'data' => 'action=buy&itemid=123',
											],
											[
											'type' => 'postback',
											'label' => 'ยกเลิกสั่งซื้อ',
											'data' => 'action=buy&itemid=123',
											]
									)], */
									[
										'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/StatusReport.png',
										'title' => 'Report',
										'text' => 'Daily Farm',
										'actions' => array(
											[
 											'type' => 'uri',
											'label' => 'สุกรคงเหลือ',
											'uri' => 'https://en.wikipedia.org/wiki/Wiki', 
											],
											[
 											'type' => 'uri',
											'label' => 'อาหารคงเหลือ',
											'uri' => 'https://shielded-dawn-30361.herokuapp.com/report/report.php', 
											]
									)]											
							)													
						]
				];			
			}			 		
			
			/*
			if($text == '<บันทึกตาย>') {
				
				$sqlDelete = "DELETE FROM \"FR_DATA_COLLECTION\" WHERE \"USER_ID\" = '$userId' ";
				
				writeData($sqlDelete);
				
				$sql = "INSERT INTO \"FR_DATA_COLLECTION\"(
				\"USER_ID\", \"PROCESS_NAME\", \"STEP_ACTION\", \"CREATE_DATE\", \"PROCESS_STATUS\")
				VALUES ('$userId', 'DEADCULL', 'MENUSELECT', now(), 'KEYING') ";
				
				writeData($sql);
				
				$today = date('d/m/Y');
				$yesterday = date('d/m/Y', strtotime(' -1 day'));
				
				$msgDate = [
						'type' => 'template',
						'altText' => 'this is a buttons template',
						'template' => [
							'type' => 'buttons',
							//'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
							'title' => 'กรุณาเลือกวันที่',
							'text' => 'Please select date.',
							'actions' => array(
								[
								'type' => 'postback',
								'label' => $today,
								'data' => 'action=buy&itemid=123',
								'text' => '<วันที่> '.$today,
								],[
								'type' => 'postback',
								'label' => $yesterday,
								'data' => 'action=buy&itemid=123',	
								'text' => '<วันที่> '.$yesterday,
								]
							)
						]
				];
				
				array_push($msg,$msgDate);
			}
			
			if(stristr($text,'<วันที่>') ) {
				
				$STEP1_VALUE = str_replace('<วันที่> ','',$text);
				
				updateStep(['userId' => $userId, 'step' => 1, 'val' => $STEP1_VALUE, 'menu' => 'dead']);
				
				$msgCv = retrieveMsgCv(['userId' => $userId, 'menu' => 'dead']);
				
				if($msgCv['msgType'] == 'template') {
					array_push($msg,$msgCv['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 2, 'val' => $msgCv['msgVal']['val'], 'menu' => 'dead']);
					
					array_push($msg,$msgCv['msgVal']);
					
					$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $msgCv['msgVal']['val'], 'menu' => 'dead']);
					
					if($msgFarmOrg['msgType'] == 'template') {
						
						array_push($msg,$msgFarmOrg['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'menu' => 'dead']);
						
						array_push($msg,$msgFarmOrg['msgVal']);
						
						$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
						
						if($msgSexStock['msgType'] == 'template') {
							
							array_push($msg,$msgSexStock['msgVal']);
						}
						else {
							
							updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'menu' => 'dead']);
							
							array_push($msg,$msgSexStock['msgVal']);
							
							if($msgSexStock['msgVal']['val']) {
								$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
							
								//final
								array_push($msg,$msgDeadType['msgVal']);
								
							}
						}
					}
				}
			}
			*/
			
			if(stristr($text,'<บันทึกตาย>') ) {
				
				$sqlDelete = "DELETE FROM \"FR_DATA_COLLECTION\" WHERE \"USER_ID\" = '$userId' ";
				
				writeData($sqlDelete);
				
				$sql = "INSERT INTO \"FR_DATA_COLLECTION\"(
				\"USER_ID\", \"PROCESS_NAME\", \"STEP_ACTION\", \"CREATE_DATE\", \"PROCESS_STATUS\")
				VALUES ('$userId', 'DEADCULL', 'MENUSELECT', now(), 'KEYING') ";
				
				writeData($sql);
				
				$STEP1_VALUE = date('d/m/Y');
				
				array_push($msg,[
						'type' => 'text',
						'text' => '<วันที่>'.$STEP1_VALUE
				]);
				
				updateStep(['userId' => $userId, 'step' => 1, 'val' => $STEP1_VALUE, 'menu' => 'dead']);
				
				$msgCv = retrieveMsgCv(['userId' => $userId, 'menu' => 'dead']);
				
				if($msgCv['msgType'] == 'template') {
					array_push($msg,$msgCv['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 2, 'val' => $msgCv['msgVal']['val'], 'menu' => 'dead']);
					
					array_push($msg,$msgCv['msgVal']);
					
					$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $msgCv['msgVal']['val'], 'menu' => 'dead']);
					
					if($msgFarmOrg['msgType'] == 'template') {
						
						array_push($msg,$msgFarmOrg['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'menu' => 'dead']);
						
						array_push($msg,$msgFarmOrg['msgVal']);
						
						$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
						
						if($msgSexStock['msgType'] == 'template') {
							
							array_push($msg,$msgSexStock['msgVal']);
						}
						else {
							
							updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'menu' => 'dead']);
							
							array_push($msg,$msgSexStock['msgVal']);
							
							if($msgSexStock['msgVal']['val']) {
								$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
							
								//final
								array_push($msg,$msgDeadType['msgVal']);
								
							}
						}
					}
				}
			}
			
			if(stristr($text,'<ฟาร์ม>') ) {				
				
				$STEP2_VALUE = str_replace('<ฟาร์ม> ','',$text);
				
				updateStep(['userId' => $userId, 'step' => 2, 'val' => $STEP2_VALUE, 'menu' => 'dead']);
				
				$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $STEP2_VALUE, 'menu' => 'dead' ]);
				
				if($msgFarmOrg['msgType'] == 'template') {
					
					array_push($msg,$msgFarmOrg['msgVal']);
					
				}
				else {
				
					updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'menu' => 'dead']);
						
					array_push($msg,$msgFarmOrg['msgVal']);
						
					$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
					
					if($msgSexStock['msgType'] == 'template') {
						
						array_push($msg,$msgSexStock['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'menu' => 'dead']);
						
						array_push($msg,$msgSexStock['msgVal']);
						
						if($msgSexStock['msgVal']['val']) {
							
							$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
						
							//final
							array_push($msg,$msgDeadType['msgVal']);
							
						}
					}
				}
			}
					
			if(stristr($text,'<หลัง>') ) {
				
				$STEP3_VALUE = str_replace('<หลัง>','',$text);
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTFARMORG', \"STEP3_VALUE\"='$STEP3_VALUE'
						WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				writeData($sql);
				
				$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $STEP3_VALUE]);
				
				if($msgSexStock['msgType'] == 'template') {
					
					array_push($msg,$msgSexStock['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'menu' => 'dead']);
					
					array_push($msg,$msgSexStock['msgVal']);
					
					if($msgSexStock['msgVal']['val']) {
						
						$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
					
						//final
						array_push($msg,$msgDeadType['msgVal']);
						
					}
				}
			}
			
			if(stristr($text,'<เพศ>') ) {
				
				$STEP4_VALUE = str_replace('<เพศ> ','',$text);
				
				$sQty = explode(" qty :", $STEP4_VALUE);
				
				$STEP4_VALUE = $sQty[0].','.$sQty[1];
                
				updateStep(['userId' => $userId, 'step' => 4, 'val' => $STEP4_VALUE, 'menu' => 'dead']);
				
				$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
					
				array_push($msg,$msgDeadType['msgVal']);
				
			}
			
			if(stristr($text,'<สาเหตุ>')) {
				
				$STEP5_VALUE = explode(" ", $text);
				$STEP5_VALUE = $STEP5_VALUE[1];
				
				updateStep(['userId' => $userId, 'step' => 5, 'val' => $STEP5_VALUE, 'menu' => 'dead']);
				
				array_push($msg,[
						'type' => 'text',
						'text' => 'ระบุจำนวนตาย'
				]);
			}
			
			/*input qty */
			$sql = "select * from \"FR_DATA_COLLECTION\" where 
			\"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' and 
			\"STEP_ACTION\"='INPUTDEADTYPE' AND \"PROCESS_STATUS\" <> 'COMPLETE'  " ;
			
			$result =  writeData($sql);
			
			while ($row = pg_fetch_assoc($result)) {
				if (is_numeric($text)) {
				
				updateStep(['userId' => $userId, 'step' => 6, 'val' => $text, 'menu' => 'dead']);
				
				array_push($msg,[
						'type' => 'template',
						'altText' => 'this is a confirm  template',
						'template' => [
							'type' => 'confirm',
							'text' => 'สรุปข้อมูล  '.
									'  บันทึกตาย เล้า '.$row['STEP3_VALUE'].
									'  เพศ '.$row['STEP4_VALUE'].
									'  จำนวน  '.$text.
									'  ยืนยันข้อมูล ? ',
							'actions' => array(
								[
								'type' => 'message',
								'label' => 'ยืนยัน',
								'text' => '<กำลังบันทึก>',
								],[
								'type' => 'message',
								'label' => 'ยกเลิก',
								'text' => '<กำลังยกเลิก>',									
								]
							)
						]
					]);
				}
				else {
					if(stristr($text,'<สาเหตุ>')) {
						
					}
					else {
						array_push($msg,[
							'type' => 'text',
							'text' => 'ระบุตัวเลข เท่านั้น !  กรุณาระบุใหม่อีกครั้ง'
						]);
					}
				}
			}
			
			if ($text  == '<กำลังบันทึก>') {
				
				$sql = "select * from \"FR_DATA_COLLECTION\" where 
					\"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' and 
					\"STEP_ACTION\"='INPUTQTY' and \"PROCESS_STATUS\" <> 'COMPLETE'  " ;
					
				$result =  writeData($sql);
				
				while ($row = pg_fetch_assoc($result)) {
					
					$dt = explode("/", $row['STEP1_VALUE']);
					
					$msgDeadSw = retrieveGenDeadSwineResult([ 
						'userId' => $userId,
						'orgSel' => $row['STEP3_VALUE'],
						'deadType' => explode(",", $row['STEP5_VALUE'])[0],
						'sex' => explode(",", $row['STEP4_VALUE'])[0],
						'qty' => $row['STEP6_VALUE'],
						'date' => $dt[2].$dt[1].$dt[0]
					]);
					
					if($msgDeadSw[0]['Result_Flag'] == 'Y') {
						updateStep(['userId' => $userId, 'step' => 7, 'val' => $text, 'menu' => 'dead']);
					
						array_push($msg,[
								'type' => 'text',
								'text' => 'บันทึกข้อมูลเรียบร้อย'
						]);
						
						$completeSticker = array("114","138","125","13","137","407");
						
						array_push($msg,[
							'type' => 'sticker',
							'packageId' => '1',
							'stickerId' => $completeSticker[rand(0, 5)]
						]);
					}
					else {
						array_push($msg,[
								'type' => 'text',
								'text' => $msgDeadSw[0]['Result_Message']
						]);
					}
				}
			}
			
			if ($text  == '<กำลังยกเลิก>') {
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
					SET  \"STEP_ACTION\"='INCOMPLETE', \"STEP7_VALUE\"='$text', \"PROCESS_STATUS\"='INCOMPLETE'
					WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
						
				writeData($sql);
				
				array_push($msg,[
						'type' => 'text',
						'text' => 'ยกเลิกเรียบร้อย'
				]);
				
				$cancelSticker = array("9","16","111","123","135","403");
				
				array_push($msg,[
					'type' => 'sticker',
					'packageId' => '1',
					'stickerId' => $cancelSticker[rand(0, 5)]
				]);
			}
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= FEED USAGE =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=//
			
			if($text == '<กำลังบันทึกใช้อาหาร>') {
				
				$sqlDelete = "DELETE FROM \"FR_DATA_COLLECTION\" WHERE \"USER_ID\" = '$userId' ";
				
				writeData($sqlDelete);
				
				$sql = "INSERT INTO \"FR_DATA_COLLECTION\"(
				\"USER_ID\", \"PROCESS_NAME\", \"STEP_ACTION\", \"CREATE_DATE\", \"PROCESS_STATUS\")
				VALUES ('$userId', 'FEEDUSAGE', 'MENUSELECT', now(), 'KEYING') ";
				
				writeData($sql);
				
				$today = date('d/m/Y');   
				$yesterday = date('d/m/Y', strtotime(' -1 day'));
				
				$msgDate = [
						'type' => 'template',
						'altText' => 'this is a buttons template',
						'template' => [
							'type' => 'buttons',
							'title' => 'กรุณาเลือกวันที่',
							'text' => 'Please select date.',
							'actions' => array(
								[
									'type' => 'postback',
									'label' => $today,
									'data' => 'action=buy&itemid=123',
									'text' => '<วันที่เบิกอาหาร>'.$today,
								],[
									'type' => 'postback',
									'label' => $yesterday,
									'data' => 'action=buy&itemid=123',	
									'text' => '<วันที่เบิกอาหาร>'.$yesterday,
								])
						]
				];
				
				array_push($msg,$msgDate);
			}
			
			if(stristr($text,'<วันที่เบิกอาหาร>') ) {
				
				$STEP1_VALUE = str_replace('<วันที่เบิกอาหาร>','',$text);
				
				updateStep(['userId' => $userId, 'step' => 1, 'val' => $STEP1_VALUE, 'menu' => 'feed']);
				
				$msgCv = retrieveMsgCv(['userId' => $userId, 'menu' => 'feed']);
				
				if($msgCv['msgType'] == 'template') {
					array_push($msg,$msgCv['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 2, 'val' => $msgCv['msgVal']['val'], 'menu' => 'feed']);
					
					array_push($msg,$msgCv['msgVal']);
					
					$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $msgCv['msgVal']['val'], 'menu' => 'feed']);
					
					if($msgFarmOrg['msgType'] == 'template') {
						
						array_push($msg,$msgFarmOrg['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'menu' => 'feed']);
						
						array_push($msg,$msgFarmOrg['msgVal']);
						
						$msgProduct = retrieveMsgProduct(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
						
						if($msgProduct['msgType'] == 'template') {
							
							array_push($msg,$msgProduct['msgVal']);
						}
						else {
							
							updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgProduct['msgVal']['val'], 'menu' => 'feed']);
							
							array_push($msg,$msgProduct['msgVal']);
							
							array_push($msg,[
								'msgType' => 'message',
								'msgVal' => [
									'type' => 'text',
									'text' => 'กรุณากรอกจำนวนอาหาร'
								]
							]);
						}
					}
				}
			}

			if(stristr($text,'<ฟาร์มเบิกอาหาร>') ) {				
				
				$STEP2_VALUE = str_replace('<ฟาร์มเบิกอาหาร> ','',$text);
				
				updateStep(['userId' => $userId, 'step' => 2, 'val' => $STEP2_VALUE, 'menu' => 'feed']);
				
				$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $STEP2_VALUE, 'menu' => 'feed' ]);
				
				if($msgFarmOrg['msgType'] == 'template') {
					
					array_push($msg,$msgFarmOrg['msgVal']);
					
				}
				else {
				
					updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'menu' => 'feed']);
						
					array_push($msg,$msgFarmOrg['msgVal']);
						
					$msgProduct = retrieveMsgProduct(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
					
					if($msgProduct['msgType'] == 'template') {
						
						array_push($msg,$msgProduct['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgProduct['msgVal']['val'], 'menu' => 'feed']);
						
						array_push($msg,$msgProduct['msgVal']);
						
						array_push($msg,[
								'msgType' => 'message',
								'msgVal' => [
									'type' => 'text',
									'text' => 'กรุณากรอกจำนวนอาหาร'
								]
						]);
					}
				}
			}
			
			if(stristr($text,'<เล้าเบิกอาหาร>') ) {
				
				$STEP3_VALUE = str_replace('<เล้าเบิกอาหาร>','',$text);
				
				updateStep(['userId' => $userId, 'step' => 3, 'val' => $STEP3_VALUE, 'menu' => 'feed']);
				
				$msgProduct = retrieveMsgProduct(['userId' => $userId , 'orgSel' => $STEP3_VALUE]);
				
				if($msgProduct['msgType'] == 'template') {
					
					array_push($msg,$msgProduct['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgProduct['msgVal']['val'], 'menu' => 'feed']);
					
					array_push($msg,$msgProduct['msgVal']);
					
					array_push($msg,[
							'msgType' => 'message',
							'msgVal' => [
								'type' => 'text',
								'text' => 'กรุณากรอกจำนวนอาหาร'
							]
					]);
				}
			}
			
			if(stristr($text,'<เบอร์อาหาร>') ) {
				
				$STEP4_VALUE = str_replace('<เบอร์อาหาร>','',$text);
				$temp = explode(',',$STEP4_VALUE)[0].','.explode(',',$STEP4_VALUE)[1];
				$STEP4_VALUE = $temp;
				
				//$qtyText = str_replace('<เบอร์อาหาร>','',$text);
				
				error_log('STEP 4 LOG'.$STEP4_VALUE.'<<<<<<<');
				
				updateStep(['userId' => $userId, 'step' => 4, 'val' => $STEP4_VALUE, 'menu' => 'feed']);
				
				// array_push($msg,[
							// 'type' => 'text',
							// 'text' => explode(',',$qtyText)[0].' จำนวน '.explode(',',$qtyText)[2]
				// ]);
				
				array_push($msg,[
							'type' => 'text',
							'text' => 'กรุณาระบุจำนวน'
				]);
			}
			
			/*input qty */
			$sql = "select * from \"FR_DATA_COLLECTION\" where 
			\"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'FEEDUSAGE' and 
			\"STEP_ACTION\"='INPUTPRODUCT' AND \"PROCESS_STATUS\" <> 'COMPLETE'  " ;
			
			$result =  writeData($sql);
			
			while ($row = pg_fetch_assoc($result)) {
				if (is_numeric($text)) {
				
				updateStep(['userId' => $userId, 'step' => 5, 'val' => $text, 'menu' => 'feed']);
				
				array_push($msg,[
						'type' => 'template',
						'altText' => 'this is a confirm  template',
						'template' => [
							'type' => 'confirm',
							'text' => 'สรุปข้อมูล  '.
									'  บันทึกเบิก เล้า '.$row['STEP3_VALUE'].
									'  '.explode(',',$row['STEP4_VALUE'])[0].
									'  จำนวน  '.$text.
									'  ยืนยันข้อมูล ? ',
							'actions' => array(
								[
								'type' => 'message',
								'label' => 'ยืนยัน',
								'text' => '<กำลังบันทึกเบิกอาหาร>',
								],[
								'type' => 'message',
								'label' => 'ยกเลิก',
								'text' => '<กำลังยกเลิกเบิกอาหาร>',									
								]
							)
						]
					]);
				}
				else {
					if(stristr($text,'<เบอร์อาหาร>')) {
						
					}
					else {
						array_push($msg,[
							'type' => 'text',
							'text' => 'ระบุตัวเลข เท่านั้น !  กรุณาระบุใหม่อีกครั้ง'
						]);
					}
				}
			}
			
			if(stristr($text,'<กำลังบันทึกเบิกอาหาร>') ) {
				
				$sql = "select * from \"FR_DATA_COLLECTION\" where 
					\"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'FEEDUSAGE' and 
					\"STEP_ACTION\"='INPUTFEEDQTY' and \"PROCESS_STATUS\" <> 'COMPLETE'  " ;
					
				$result =  writeData($sql);
				
				while ($row = pg_fetch_assoc($result)) {
					
					$dt = explode("/", $row['STEP1_VALUE']);
					
					$msgFeedUse = retrieveGenSWFeedUseResult([ 
						'userId' => $userId,
						'orgSel' => $row['STEP3_VALUE'],
						'product' => explode(',',$row['STEP4_VALUE'])[1],
						'qty' => $row['STEP5_VALUE'],
						'date' => $dt[2].$dt[1].$dt[0]
					]);
					
					if($msgFeedUse[0]['Result_Flag'] == 'Y') {
						
						updateStep(['userId' => $userId, 'step' => 6, 'val' => $text, 'menu' => 'dead']);
					
						array_push($msg,[
								'type' => 'text',
								'text' => 'บันทึกข้อมูลเรียบร้อย'
						]);
						
						$completeSticker = array("114","138","125","13","137","407");
						
						array_push($msg,[
							'type' => 'sticker',
							'packageId' => '1',
							'stickerId' => $completeSticker[rand(0, 5)]
						]);
					}
					else {
						array_push($msg,[
								'type' => 'text',
								'text' => $msgFeedUse[0]['Result_Message']
						]);
					}	
				}
			}
			
			if(stristr($text,'<กำลังยกเลิกเบิกอาหาร>') ) {
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
					SET  \"STEP_ACTION\"='INCOMPLETE', \"STEP7_VALUE\"='$text', \"PROCESS_STATUS\"='INCOMPLETE'
					WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'FEEDUSAGE' ";
						
				writeData($sql);
				
				array_push($msg,[
						'type' => 'text',
						'text' => 'ยกเลิกเรียบร้อย'
				]);
				
				$cancelSticker = array("9","16","111","123","135","403");
				
				array_push($msg,[
					'type' => 'sticker',
					'packageId' => '1',
					'stickerId' => $cancelSticker[rand(0, 5)]
				]);
				
			}
			
			if($text == '<รายงาน>' ) {
				
				$today = date('Ymd');  
				
				$msgCv = retrieveMsgCv(['userId' => $userId, 'menu' => 'report']);
				
				if($msgCv['msgType'] == 'template') {
					array_push($msg,$msgCv['msgVal']);
				}
				else {
					array_push($msg,$msgCv['msgVal']);
					
					error_log('CV FARM VAL <<<<<<'.$msgCv['msgVal']['val']);
					
					$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $msgCv['msgVal']['val'], 'menu' => 'report' ]);
					
					if($msgFarmOrg['msgType'] == 'template') {
						array_push($msg,$msgFarmOrg['msgVal']);
					}
					else {
						array_push($msg,$msgFarmOrg['msgVal']);
						
						$msgReport = retrieveMsgReport(['userId' => $userId, 'cvFarm' => $msgFarmOrg['msgVal']['val'], 'date' => $today]);
					
						array_push($msg,$msgReport['msgVal']);
					}
				}
			}
			
			if(stristr($text,'<รายงานเล้า>') ) {
				
				$today = date('Ymd');  
				
				$val = str_replace('<รายงานเล้า>','',$text);
				
				$msgReport = retrieveMsgReport(['userId' => $userId, 'cvFarm' => $val, 'date' => $today]);
					
				array_push($msg,$msgReport['msgVal']);
				
			}
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => $msg,
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			
		}
	}
}

function updateStep($obj) {
	
	$stepAction = '';
	$val = $obj['val'];
	$id = $obj['userId'];
	$process = ($obj['menu'] == 'dead' ? 'DEADCULL' : 'FEEDUSAGE');
	$step = $obj['step'];
	
	switch ($step) {
		case 1:
			$stepAction = 'INPUTDATE';
		break;
		case 2:
			$stepAction = 'INPUTCV';
		break;
		case 3:
			$stepAction = 'INPUTFARMORG';
		break;
		case 4:
			$stepAction = ($obj['menu'] == 'dead' ? 'INPUTSEX' : 'INPUTPRODUCT');
		break;
		case 5:
			$stepAction = ($obj['menu'] == 'dead' ? 'INPUTDEADTYPE' : 'INPUTFEEDQTY');
		break;
		case 6:
			$stepAction = ($obj['menu'] == 'dead' ? 'INPUTQTY' : 'COMPLETE');
		break;
		case 7:
			$stepAction = 'COMPLETE';
		break;
	}
	
	$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
			SET  \"STEP_ACTION\"='$stepAction', \"STEP".$step."_VALUE\"='$val'
			WHERE \"USER_ID\" = '$id' and \"PROCESS_NAME\" = '$process' ";
	 
	writeData($sql);
}

function retrieveMsgCv($obj) {

	$arrData = retrieveServiceData([ 'service' => 'GetFarmsResult', 'userId' => $obj['userId']]);
	
	$textRep = '';
			
	if($obj['menu'] == 'dead') {
		$textRep = '<ฟาร์ม>';
	}
	elseif($obj['menu'] == 'report') {
		$textRep = '<รายงานฟาร์ม>';
	}
	else {
		$textRep = '<ฟาร์มเบิกอาหาร>';
	}
			
	if(count($arrData) > 1) {
		
		$arrMessageDs = array(); 
		
		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Farm_Name'],
				'data' => 'action=buy&itemid=123',
				'text' => $textRep.$val['Farm_Code'],
			]);
		}
		
		$ret = [
			'msgType' => 'template',
			'msgVal' => [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => [
					'type' => 'buttons',
					'title' => 'กรุณาเลือกเล้า',
					'text' => 'Please select pen.',
					'actions' => $arrMessageDs
				]
			]
		];
	}
	else {
		if(count($arrData) == 1) {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => $textRep.$arrData[0]['Farm_Name'],
					'val' => $arrData[0]['Farm_Code']
				]
			];
		}
		else {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => 'ไม่มีฟาร์มที่เลี้ยงอยู่ กรุณาเลือกเมนูอีกครั้ง',
					'val' => false
				]
			];
		}
		
	}
	
	// $ret = [
			// 'msgType' => 'message',
			// 'msgVal' => [
				// 'type' => 'text',
				// 'text' => 'ฟาร์ม '.$arrData[0]['Farm_Name'],
				// 'val' => $arrData[0]['Farm_Code']
			// ]
		// ];
	
	return $ret;
}
function retrieveMsgFarmOrg($obj) {
	
	$arrData = retrieveServiceData([ 'service' => 'GetFarmOrgsResult', 'userId' => $obj['userId'],'cvFarm' => $obj['cvFarm']]);
	
	$textRep = '';
			
	if($obj['menu'] == 'dead') {
		$textRep = '<หลัง>';
	}
	elseif($obj['menu'] == 'report') {
		$textRep = '<รายงานเล้า>';
	}
	else {
		$textRep = '<เล้าเบิกอาหาร>';
	}
	
	if(count($arrData) > 1) {
		$arrMessageDs = array();

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Farm_Org'],
				'data' => 'action=buy&itemid=123',
				'text' => $textRep.$val['Farm_Org'],
			]);
		}
		
		$ret = [
			'msgType' => 'template',
			'msgVal' => [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => [
					'type' => 'buttons',
					'title' => 'กรุณาเลือกเล้า',
					'text' => 'Please select pen.',
					'actions' => $arrMessageDs
				]
			]
		];
	}
	else {		
		if(count($arrData) == 1) {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => $textRep.$arrData[0]['Farm_Org'],
					'val' => $arrData[0]['Farm_Org']
				]
			];	
		}
		else {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => 'ไม่มีเล้าที่กำลังเลี้ยงอยู่ กรุณาเลือกเมนูอีกครั้ง',
					'val' => false
				]
			];	
		}
	}
	
	// $ret = [
			// 'msgType' => 'message',
			// 'msgVal' => [
				// 'type' => 'text',
				// 'text' => 'เล้า '.$arrData[1]['Farm_Org'],
				// 'val' => $arrData[1]['Farm_Org']
			// ]
		// ];
	
	return $ret;
}
function retrieveMsgSexStock($obj){
	
	$arrData = retrieveServiceData([ 'service' => 'GetBdStocksResult', 'userId' => $obj['userId'], 'orgSel' => $obj['orgSel']]);
	
	if(count($arrData) > 1) {
		$arrMessageDs = array();

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Sex'].' qty :'.$val['Bd_Qty'],
				'data' => 'action=buy&itemid=123',
				'text' => '<เพศ> '.$val['Sex'].' qty :'.$val['Bd_Qty'],
			]);
		}
		
		$ret = [
			'msgType' => 'template',
			'msgVal' => [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => [
					'type' => 'buttons',
					'title' => 'กรุณาเลือกพศ',
					'text' => 'Please select gender.',
					'actions' => $arrMessageDs
				]
			]
		];
	}
	else {
		if(count($arrData) == 1) {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => 'เพศ '.$arrData[0]['Sex'].' จำนวน '.$arrData[0]['Bd_Qty'],
					'val' => $arrData[0]['Sex']
				]
			];
		}
		else {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => 'ไม่มีจำนวนคงเหลือ กรุณาเลือกเมนูอีกครั้ง',
					'val' => false
				]
			];
		}
	}
	
	// $ret = [
			// 'msgType' => 'message',
			// 'msgVal' => [
				// 'type' => 'text',
				// 'text' => 'เพศ '.$arrData[1]['Sex'].' จำนวน '.$arrData[1]['Bd_Qty'],
				// 'val' => $arrData[1]['Sex']
			// ]
		// ];
	
	
	return $ret;
}
function retrieveMsgDeadType($obj) {
	
	$arrData = retrieveServiceData([ 'service' => 'GetReasonDeadsResult', 'userId' => $obj['userId']]);
	
	$ret = array(); 
	
	if(count($arrData) > 1) {
		$arrMessageDs = array(); 

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Reason_Dead_Name'],
				'data' => 'action=buy&itemid=123',
				'text' => '<สาเหตุ> '.$val['Reason_Dead_Code'].','.$val['Reason_Dead_Name'],
			]);
		}
		/*
		$ret = [
			'msgType' => 'template',
			'msgVal' => [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => [
					'type' => 'buttons',
					'title' => 'กรุณาเลือกเหตูผล',
					'text' => 'Please select reason.',
					'actions' => $arrMessageDs
				]
			]
		];
		*/
		
		$arrMessageDs = array_merge($arrMessageDs, $arrMessageDs);
		
		if(count($arrMessageDs) > 4) {
			
			$carousColumn = intval(count($arrMessageDs) / 4);
			
			$countData = count($arrMessageDs);
			
			error_log('[[[[[data >>>>>>>>>[['.$countData.']] <<<<<< col >>>>>>>'.$carousColumn);
			
			$crsDs = array();
			
			for ($i = 0; $i < count($arrMessageDs); $i++) {
				
				if($i % 4 == 0) {
					
					$crsCol = array(); 
					
					error_log('THIS COL INDEX >> '.$i);
					
					$crsDet = array(); 
					
					for($j = $i; $j < count($arrMessageDs) && $j < $i + 4; $j++) {
						
						error_log('CAROUSEL DETAIL INDEX >> '.$j.' -=-=-=-=-=-'.$arrMessageDs[$j] );
						
						array_push($crsDet,[
							'type' => 'postback',
							'label' => $arrMessageDs[$j]['Reason_Dead_Name'],
							'data' => 'action=buy&itemid=123',
							'text' => '<สาเหตุ> '.$arrMessageDs[$j]['Reason_Dead_Code'].','.$arrMessageDs[$j]['Reason_Dead_Name'],
						]);
					}
					
					$crscol = [
						'title' => 'สาเหตุ',
						'text' => ' ',
						'actions' => $crsdet
					];
					
					array_push($crsDs,$crscol);
				}
			}
			
			$ret = [
				'msgType' => 'template',
				'msgVal' => [
							'type' => 'template',
							'altText' => 'this is a buttons template',
							'template' => [
								'type' => 'carousel',
								'columns' => $crsDs
							]
					]
			];
			
		}
		
		/*
		$ret = [
			'msgType' => 'template',
			'msgVal' => [
						'type' => 'template',
						'altText' => 'this is a buttons template',
						'template' => [
							'type' => 'carousel',
							'columns' => array (
									[
										'title' => 'สาเหตุ',
										'text' => ' ',
										'actions' => array(
											[
											'type' => 'postback',
											'label' => 'ตาย/คัดทิ้ง',
											'data' => 'action=buy&itemid=123',
											'text' => '!MaDeadCull',
											],
											[
											'type' => 'postback',
											'label' => 'ใข้อาหาร',
											'data' => 'action=buy&itemid=123',
											'text' => '!MaFeed',											

											]
										)
									]
							)													
						]
				]
		];
		
		*/
	}
	else {
		$ret = [
			'msgType' => 'message',
			'msgVal' => [
				'type' => 'text',
				'text' => '<สาเหตุ> '.$val['Reason_Dead_Code'].','.$val['Reason_Dead_Name']
			]
		];
	}
	
	error_log(json_encode($ret));
	
	return $ret;
}
function retrieveGenDeadSwineResult($obj){
	$arrData = retrieveServiceData([ 
		'service' => 'GenDeadSwineResult', 
		'userId' => $obj['userId'],
		'orgSel' => $obj['orgSel'],
		'deadType' => $obj['deadType'],
		'sex' => $obj['sex'],
		'qty' => $obj['qty'],
		'date' => $obj['date']
	]);
	
	return $arrData;
}

function retrieveMsgProduct($obj) {
	
	$arrData = retrieveServiceData([ 'service' => 'GetSWFeedStocksResult', 'userId' => $obj['userId'], 'orgSel' => $obj['orgSel']]);
	
	if(count($arrData) > 1) {
		
		$arrMessageDs = array(); 
		
		
		
		foreach ($arrData as $val) {
			
			$unit = ($val['Cal_Method'] == 'Q' ? 'ถุง' : 'กก.');
			
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => str_replace('อาหารหมู', '',str_replace(' ','',$val['Product_Name'])).'('.$val['Stock_Qty'].' '.$unit.')',
				//'label' => str_replace(' ','',$val['Product_Name']),
				'data' => 'action=buy&itemid=123',
				'text' => '<เบอร์อาหาร>'.str_replace(' ','',$val['Product_Name']).','.$val['Product_Code']
			]);
		}
		
		$ret = [
			'msgType' => 'template',
			'msgVal' => [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => [
					'type' => 'buttons',
					'title' => 'กรุณาเลือกเบอร์อาหาร',
					'text' => 'Please select pen.',
					'actions' => $arrMessageDs
				]
			]
		];						
	}
	else {
		if(count($arrData) == 1) {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => '<เบอร์อาหาร>'.$arrData[0]['Product_Code'].' จำนวน '.$arrData[0]['Stock_Qty'],
					'val' => $arrData[0]['Product_Code']
				]
			];
		}
		else {
			$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => 'ไม่มีอาหารเหลืออยู่ กรุณาเลือกเมนูอีกครั้ง',
					'val' => false
				]
			];
		}
		
	}
	
	return $ret;
}

function retrieveMsgReport($obj) {

	$ret = [
			'msgType' => 'template',
			'msgVal' => [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => [
					'type' => 'buttons',
					'title' => 'report',
					'text' => 'Please select',
					'actions' => array(
						[
							'type' => 'uri',
							'label' => 'รายงานสุกรคงเหลือ',
							'uri' => 'https://shielded-dawn-30361.herokuapp.com/report/report.php?type=getreportswstock&id='.$obj['userId'].'&cv='.$obj['cvFarm'].'&date='.$obj['date']
						],
						[
							'type' => 'uri',
							'label' => 'รายงานอาหาร',
							'uri' => 'https://shielded-dawn-30361.herokuapp.com/report/report.php?type=getreportfeedstock&id='.$obj['userId'].'&cv='.$obj['cvFarm'].'&date='.$obj['date']
						]
					)
				]
			]
		];
	
	return $ret;
	
}
function retrieveGenSWFeedUseResult($obj) {
	
	$arrData = retrieveServiceData([ 
		'service' => 'GenSWFeedUseResult', 
		'userId' => $obj['userId'],
		'orgSel' => $obj['orgSel'],
		'product' => $obj['product'],
		'qty' => $obj['qty'],
		'date' => $obj['date']
	]);
	
	return $arrData;
}

function retrieveServiceData($obj) {
	
	$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/';
	
	switch ($obj['service']) {
		case 'GetFarmsResult':
			$url = $url.'farm/'.$obj['userId'];
			break;
		case 'GetFarmOrgsResult':
			$url = $url.'farmorg/'.$obj['userId'].','.$obj['cvFarm'];
			break;
		case 'GetBdStocksResult':
			$url = $url.'getbdstock/'.$obj['userId'].','.$obj['orgSel'];
			break;
		case 'GetReasonDeadsResult':
			$url = $url.'reasondead/'.$obj['userId'];
			break;
		case 'GenDeadSwineResult':
			$url = $url.'deadswine/'.$obj['userId'].','.$obj['orgSel'].','.$obj['deadType'].','.$obj['sex'].','.$obj['qty'].','.$obj['date'];
			break;
		case 'GetSWFeedStocksResult':
			$url = $url.'getswfeedstock/'.$obj['userId'].','.$obj['orgSel'];
			break;
		case 'GenSWFeedUseResult':
			$url = $url.'genswfeeduse/'.$obj['userId'].','.$obj['orgSel'].','.$obj['product'].','.$obj['qty'].','.$obj['date'];
			break;
		default:
			break;
	}
	
	error_log('LOG URL SERVICE >>>>>>>>>'.$url.'<<<<<<<< LOG URL SERVICE');
	
	$arrContextOptions = array(
						'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						),); 
	$content = file_get_contents($url,false, stream_context_create($arrContextOptions));
	$result = json_decode($content, true);
	
	return $result[$obj['service']];
}

echo 'OK';

?>
