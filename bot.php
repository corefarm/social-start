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
$testing = 'Matext lawka';

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
											'text' => '!MaDeadCull',
											],
											[
											'type' => 'postback',
											'label' => 'ใข้อาหาร',
											'data' => 'action=buy&itemid=123',
											'text' => '!MaFeed',											
/* 											'type' => 'uri',
											'label' => 'Information',
											'uri' => 'https://en.wikipedia.org/wiki/Wiki', */
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
											'uri' => 'https://www.google.com/', 
											]
									)]											
							)													
						]
				];			
			}			 		
			
			// SABPAROD LANDING HERE
			if($text == '!MaDeadCull') {
				
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
								'text' => '!SelDateDe '.$today,
								],[
								'type' => 'postback',
								'label' => $yesterday,
								'data' => 'action=buy&itemid=123',	
								'text' => '!SelDateDe '.$yesterday,
								]
							)
						]
				];
				
				array_push($msg,$msgDate);
			}
			
			if(stristr($text,'!SelDateDe') ) {
				
				$STEP1_VALUE = str_replace('!SelDateDe ','',$text);
				
				// $sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						// SET  \"STEP_ACTION\"='INPUTDATE', \"STEP1_VALUE\"='$STEP1_VALUE'
						// WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				// writeData($sql);
				
				updateStep(['userId' => $userId, 'step' => 1, 'val' => $STEP1_VALUE, 'process' => 'DEADCULL']);
				
				$msgCv = retrieveMsgCv(['userId' => $userId]);
				
				if($msgCv['msgType'] == 'template') {
					array_push($msg,$msgCv['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 2, 'val' => $msgCv['msgVal']['val'], 'process' => 'DEADCULL']);
					
					array_push($msg,$msgCv['msgVal']);
					
					$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $msgCv['msgVal']['val']]);
					
					if($msgFarmOrg['msgType'] == 'template') {
						
						array_push($msg,$msgFarmOrg['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'process' => 'DEADCULL']);
						
						array_push($msg,$msgFarmOrg['msgVal']);
						
						$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
						
						if($msgSexStock['msgType'] == 'template') {
							
							error_log('>>>>>> msgSexStock <<<<<<');
							
							array_push($msg,$msgSexStock['msgVal']);
						}
						else {
							
							updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'process' => 'DEADCULL']);
							
							array_push($msg,$msgSexStock['msgVal']);
							
							$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
							
							//final
							array_push($msg,$msgDeadType['msgVal']);
						}
					}
				}
			}

			if(stristr($text,'!SelCvDe') ) {				
				
				$STEP2_VALUE = str_replace('!SelCvDe ','',$text);
				
				updateStep(['userId' => $userId, 'step' => 2, 'val' => $STEP2_VALUE, 'process' => 'DEADCULL']);
				
				$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userId, 'cvFarm' => $STEP2_VALUE ]);
				
				if($msgFarmOrg['msgType'] == 'template') {
					
					array_push($msg,$msgFarmOrg['msgVal']);
					
				}
				else {
				
					updateStep(['userId' => $userId, 'step' => 3, 'val' => $msgFarmOrg['msgVal']['val'], 'process' => 'DEADCULL']);
						
					array_push($msg,$msgFarmOrg['msgVal']);
						
					$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $msgFarmOrg['msgVal']['val']]);
					
					if($msgSexStock['msgType'] == 'template') {
						
						array_push($msg,$msgSexStock['msgVal']);
					}
					else {
						
						updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'process' => 'DEADCULL']);
						
						array_push($msg,$msgSexStock['msgVal']);
						
						$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
						
						//final
						array_push($msg,$msgDeadType['msgVal']);
					}
				}
			}
					
			if(stristr($text,'!SelFarmDe') ) {
				
				$STEP3_VALUE = str_replace('!SelFarmDe ','',$text);
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTFARMORG', \"STEP3_VALUE\"='$STEP3_VALUE'
						WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				writeData($sql);
				
				$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $STEP3_VALUE]);
				
				if($msgSexStock['msgType'] == 'template') {
					
					array_push($msg,$msgSexStock['msgVal']);
				}
				else {
					
					updateStep(['userId' => $userId, 'step' => 4, 'val' => $msgSexStock['msgVal']['val'], 'process' => 'DEADCULL']);
						
					array_push($msg,$msgSexStock['msgVal']);
					
					$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
					
					//final
					array_push($msg,$msgDeadType['msgVal']);
				}
			}
			
			if(stristr($text,'!SelSexDe') ) {
				
				$STEP4_VALUE = str_replace('!SelSexDe ','',$text);
				
				$sQty = explode(" qty :", $STEP4_VALUE);
				
				$STEP4_VALUE = $sQty[0].','.$sQty[1];
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTSEX', \"STEP4_VALUE\"='$STEP4_VALUE'
						WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				writeData($sql);
				
				$msgDeadType = retrieveMsgDeadType([ 'userId' => $userId]);
					
				array_push($msg,$msgDeadType['msgVal']);
				
			}
			
			if(stristr($text,'!SelDeadTypeDe')) {
				
				$STEP5_VALUE = explode(" ", $text);
				$STEP5_VALUE = $STEP5_VALUE[1];
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTDEADTYPE', \"STEP5_VALUE\"='$STEP5_VALUE'
						WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				writeData($sql);
				
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
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
					SET  \"STEP_ACTION\"='INPUTQTY', \"STEP6_VALUE\"='$text'
						WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";		
				writeData($sql);
				
				array_push($msg,[
						'type' => 'template',
						'altText' => 'this is a confirm  template',
						'template' => [
							'type' => 'confirm',
							'text' => 'สรุปข้อมูล'.
									'บันทึกตาย เล้า '.$row['STEP3_VALUE'].
									'เพศ'.$row['STEP4_VALUE'].
									'จำนวน  '.$text.
									'ยืนยันข้อมูล ? ',
							'actions' => array(
								[
								'type' => 'message',
								'label' => 'ยืนยัน',
								'text' => '!YesDead',
								],[
								'type' => 'message',
								'label' => 'ยกเลิก',
								'text' => '!NoDead',									
								]
							)
						]
					]);
				}
				else {
					if(stristr($text,'!SelDeadTypeDe')) {
						
					}
					else {
						array_push($msg,[
							'type' => 'text',
							'text' => 'ระบุตัวเลข เท่านั้น !  กรุณาระบุใหม่อีกครั้ง'
						]);
					}
				}
			}
			
			if ($text  == '!YesDead') {
				
				$sql = "select * from \"FR_DATA_COLLECTION\" where 
					\"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' and 
					\"STEP_ACTION\"='INPUTQTY' and \"PROCESS_STATUS\" <> 'COMPLETE'  " ;
					
				$result =  writeData($sql);
				
				while ($row = pg_fetch_assoc($result)) {
					
					if(retrieveGenDeadSwineResult([ 
						'userId' => $userId,
						'orgSel' => $row['STEP3_VALUE'],
						'deadType' => explode(",", $row['STEP5_VALUE'])[0],
						'sex' => explode(",", $row['STEP4_VALUE'])[0],
						'qty' => $row['STEP6_VALUE']])) {
								
						$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
							SET  \"STEP_ACTION\"='COMPLETE', \"STEP7_VALUE\"='$text', \"PROCESS_STATUS\"='COMPLETE'
							WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
								
						writeData($sql);
						
						array_push($msg,[
								'type' => 'text',
								'text' => 'บันทึกข้อมูลเรียบร้อย'
						]);
						
						array_push($msg,[
							'type' => 'sticker',
							'packageId' => '1',
							'stickerId' => 138
						]);
					}
				}
			}
			
			if ($text  == '!NoDead') {
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
					SET  \"STEP_ACTION\"='INCOMPLETE', \"STEP7_VALUE\"='$text', \"PROCESS_STATUS\"='INCOMPLETE'
					WHERE \"USER_ID\" = '$userId' and \"PROCESS_NAME\" = 'DEADCULL' ";
						
				writeData($sql);
				
				array_push($msg,[
						'type' => 'text',
						'text' => 'ยกเลิกเรียบร้อย'
				]);
				
				array_push($msg,[
					'type' => 'sticker',
					'packageId' => '1',
					'stickerId' => 139
				]);
			}

			// END SABPAROD LANDING HERE

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
	$process = $obj['process'];
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
			$stepAction = 'INPUTSEX';
		break;
		case 5:
			$stepAction = 'INPUTDEADTYPE';
		break;
		case 6:
			$stepAction = 'INPUTQTY';
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

	$arrData = retrieveServiceData([ 'service' => 'farm', 'userId' => $obj['userId']]);
	
	if(count($arrData) > 1) {
		
		$arrMessageDs = array(); 
		
		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Farm_Name'],
				'data' => 'action=buy&itemid=123',
				'text' => '!SelCvDe '.$val['Farm_Code'],
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
		$ret = [
			'msgType' => 'message',
			'msgVal' => [
				'type' => 'text',
				'text' => 'ฟาร์ม '.$arrData[0]['Farm_Name'],
				'val' => $arrData[0]['Farm_Code']
			]
		];
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
	
	$arrData = retrieveServiceData([ 'service' => 'farmorg', 'userId' => $obj['userId'],'cvFarm' => $obj['cvFarm']]);
	
	if(count($arrData) > 1) {
		$arrMessageDs = array();

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Farm_Org'],
				'data' => 'action=buy&itemid=123',
				'text' => '!SelFarmDe '.$val['Farm_Org'],
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
		$ret = [
		'msgType' => 'message',
		'msgVal' => [
			'type' => 'text',
			'text' => 'เล้า '.$arrData[1]['Farm_Org'],
			'val' => $arrData[1]['Farm_Org']
			]
		];
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
	
	$arrData = retrieveServiceData([ 
		'service' => 'getbdstock', 
		'userId' => $obj['userId'],
		'orgSel' => $obj['orgSel']
	]);
	
	if(count($arrData) > 1) {
		$arrMessageDs = array();

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Sex'].' qty :'.$val['Bd_Qty'],
				'data' => 'action=buy&itemid=123',
				'text' => '!SelSexDe '.$val['Sex'].' qty :'.$val['Bd_Qty'],
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
		$ret = [
				'msgType' => 'message',
				'msgVal' => [
					'type' => 'text',
					'text' => 'เพศ '.$arrData[1]['Sex'].' จำนวน '.$arrData[1]['Bd_Qty'],
					'val' => $arrData[1]['Sex']
			]
		];
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
	
	$arrData = retrieveServiceData([ 
		'service' => 'reasondead', 
		'userId' => $obj['userId']
	]);
	
	if(count($arrData) > 1) {
		$arrMessageDs = array(); 

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['Reason_Dead_Name'],
				'data' => 'action=buy&itemid=123',
				'text' => '!SelDeadTypeDe '.$val['Reason_Dead_Code'].','.$val['Reason_Dead_Name'],
			]);
		}
		
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
	}
	else {
		$ret = [
			'msgType' => 'message',
			'msgVal' => [
				'type' => 'text',
				'text' => '!SelDeadTypeDe '.$val['Reason_Dead_Code'].','.$val['Reason_Dead_Name']
			]
		];
	}
	
	
	return $ret;
}
function retrieveGenDeadSwineResult($obj){
	$arrData = retrieveServiceData([ 
		'service' => 'deadswine', 
		'userId' => $obj['userId'],
		'orgSel' => $obj['orgSel'],
		'deadType' => $obj['deadType'],
		'sex' => $obj['sex'],
		'qty' => $obj['qty']
	]);
	
	if($arrData[0]['Result_Flag'] == 'Y'){
		return true;
	}
	return false;
}

function retrieveServiceData($obj) {
	
	$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/';
	
	$keyValue = '';
	
	switch ($obj['service']) {
		case 'farm':
			$url = $url.'farm/'.$obj['userId'];
			$keyValue = 'GetFarmsResult';
			break;
		case 'farmorg':
			$url = $url.'farmorg/'.$obj['userId'].','.$obj['cvFarm'];
			$keyValue = 'GetFarmOrgsResult';
			break;
		case 'getbdstock':
			$url = $url.'getbdstock/'.$obj['userId'].','.$obj['orgSel'];
			$keyValue = 'GetBdStocksResult';
			break;
		case 'reasondead':
			$url = $url.'reasondead/'.$obj['userId'];
			$keyValue = 'GetReasonDeadsResult';
			break;
		case 'deadswine':
			$url = $url.'deadswine/'.$obj['userId'].','.$obj['orgSel'].','.$obj['deadType'].','.$obj['sex'].','.$obj['qty'];
			$keyValue = 'GenDeadSwineResult';
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
	
	return $result[$keyValue];
}

echo 'OK';

?>
