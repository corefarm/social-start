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

//$content_sql   = str_replace('"', '\"', $content_sql);


$sql = " INSERT INTO \"Fr_User_Log\"(
	\"Request\", \"ReplyConfirm\", \"CreateDate\")
	VALUES ('$content_sql', 'N', now())";

//	echo $sql . '\r\n';
writeData($sql);
// Parse JSON
$events = json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		
		//$userid = $event['source']['userId'];
		$userid = '123456789';
		
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
            
			$sql = "select * from \"FR_DATA_COLLECTION\" where \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' and \"STEP_ACTION\"='KEY QTY' AND \"PROCESS_STATUS\" <> 'COMPLETE'  " ;
			$result =  writeData($sql);
			while ($row = pg_fetch_assoc($result)) {
				
				if (is_numeric($text)) {
                    $sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='Confirm', \"STEP2_VALUE\"='$text'
							WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";		
                    writeData($sql);
                    $messages = [
                            'type' => 'template',
                            'altText' => 'this is a confirm  template',
                            'template' => [
                                'type' => 'confirm',
                                'text' => 'บันทึกตาย เล้า '.$row['STEP1_VALUE'].'  
										จำนวน  '.$text.' 
										ยืนยันข้อมูล ? ',
                                'actions' => array(
                                    [
                                    'type' => 'message',
                                    'label' => 'ยืนยัน',
                                    'text' => '!YesDEADCULL',
                                    ],[
                                    'type' => 'message',
                                    'label' => 'ยกเลิก',
                                    'text' => '!NoDEADCULL',									
                                    ]
                                )
                            ]
                    ];							
					
				} else {
					$messages = 
					[
							'type' => 'text',
							'text' => 'ระบุตัวเลข เท่านั้น !  กรุณาระบุใหม่อีกครั้ง'
					];					
				}
			}
            
			if ($text  == '!YesDEADCULL') {

                $sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"PROCESS_STATUS\"='COMPLETE'
							WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";	
                
                $messages = 
                [
                        'type' => 'text',
                        'text' => 'บันทึกข้อมูลเรียบร้อย'
                ];
                
                $messages_2 =  [
                    'type' => 'sticker',
                    'packageId' => '1',
                    'stickerId' => 138
            ];				
			}
			
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
			
			/*if ($text  == '!MaDeadCull') {
				
				
				$sql = "INSERT INTO \"FR_DATA_COLLECTION\"(
				\"USER_ID\", \"PROCESS_NAME\", \"STEP_ACTION\", \"CREATE_DATE\", \"PROCESS_STATUS\")
				VALUES ('$userid', 'DEADCULL', 'SELECT FARM', now(), 'KEYING') ";
				
				writeData($sql);
				
				
				$messages = [
						'type' => 'template',
						'altText' => 'this is a buttons template',
						'template' => [
							'type' => 'buttons',
							'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
							'title' => 'Menu',
							'text' => 'Please select',
							'actions' => array(
								[
								'type' => 'postback',
								'label' => '620500-0-2-4-775',
								'data' => 'action=buy&itemid=123',
								'text' => 'FARMSEL!620500-0-2-4-6',
								],[
								'type' => 'postback',
								'label' => '620500-0-2-4-775',
								'data' => 'action=buy&itemid=123',	
								'text' => 'FARMSEL!620500-0-2-4-775',
								]
							)
						]
				];			
			}	*/
			
			/*if(stristr($text,'FARMSEL!') ) {
				
				$STEP1_VALUE = str_replace('FARMSEL!','',$text);
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='KEY QTY', \"STEP1_VALUE\"='$STEP1_VALUE'
							WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";
                
				writeData($sql); 
				
				$messages = 
					[
							'type' => 'text',
							'text' => 'กรุณาระบุจำนวนตาย  '
					];	

			}*/
            
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

			if ( $messages == 'X') {
                $messages = 
                [
                        'type' => 'text',
                        'text' => 'ตอบจาก  Bot v4.1.22 pj corefarm : สามารถใช้ Key Word ได้ คือ  
       im  (Image) ,  
         cf (Confirm), 
         tmp (Tempalte), 
         con (Carousel), 
         st (sticker)'
                ];
                
                $messages_2 =  [
                    'type' => 'sticker',
                    'packageId' => '1',
                    'stickerId' => rand(100, 118)
            ];	
			}
			
			
			
			// SABPAROD LANDING HERE
			if($text == '!MaDeadCull') {
				
				$sqlDelete = "DELETE FROM \"FR_DATA_COLLECTION\" WHERE \"USER_ID\" = '$userid' ";
				
				writeData($sqlDelete);
				
				$sql = "INSERT INTO \"FR_DATA_COLLECTION\"(
				\"USER_ID\", \"PROCESS_NAME\", \"STEP_ACTION\", \"CREATE_DATE\", \"PROCESS_STATUS\")
				VALUES ('$userid', 'DEADCULL', 'MENUSELECT', now(), 'KEYING') ";
				
				//writeData($sql);
				
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
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTDATE', \"STEP1_VALUE\"='$STEP1_VALUE'
						WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				//writeData($sql); 
				
				$msgCv = retrieveMsgCv(['userId' => $userid]);
				
				if($msgCv['msgType'] == 'template') {
					array_push($msg,$msgCv['msgVal']);
				}
				else {
					array_push($msg,$msgCv['msgVal']);
					
					$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userid, 'cvFarm' => str_replace('!SelDateDe ','',$msgCv['msgVal']['text'])]);
					
					if($msgFarmOrg['msgType'] == 'template') {
						
						$msg = array();
						
						array_push($msg,$msgFarmOrg['msgVal']);
					}
					else {
						
						array_push($msg,$msgFarmOrg['msgVal']);
						
						$msgSexStock = retrieveMsgSexStock();
						
						if($msgSexStock['msgType'] == 'template') {
							
							//$msg = array();
							
							array_push($msg,$msgSexStock['msgVal']);
						}
						else {
							
							array_push($msg,$msgSexStock['msgVal']);
							
							$msgDeadType = retrieveMsgDeadType();
							
							//final
							array_push($msg,$msgDeadType['msgVal']);
						}
					}
				}
			}

			if(stristr($text,'!SelCvDe') ) {				
				
				$STEP2_VALUE = str_replace('!SelCvDe ','',$text);
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTCV', \"STEP2_VALUE\"='$STEP2_VALUE'
						WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				//writeData($sql);
				
				$msgFarmOrg = retrieveMsgFarmOrg(['userId' => $userid, 'cvFarm' => $STEP2_VALUE ]);
				
				if($msgFarmOrg['msgType'] == 'template') {
					
					array_push($msg,$msgFarmOrg['msgVal']);
					
				}
				else {
					
					array_push($msg,$msgFarmOrg['msgVal']);
					
					$msgSexStock = retrieveMsgSexStock();
						
					if($msgSexStock['msgType'] == 'template') {
						
						$msg = array();
						
						array_push($msg,$msgSexStock['msgVal']);
					}
					else {
						
						array_push($msg,$msgSexStock['msgVal']);
						
						$msgDeadType = retrieveMsgDeadType();
						
						//final
						array_push($msg,$msgDeadType['msgVal']);
					}
				}
			}
					
			if(stristr($text,'!SelFarmDe') ) {
				
				$STEP3_VALUE = str_replace('!SelFarmDe ','',$text);
				
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
						SET  \"STEP_ACTION\"='INPUTFARMORG', \"STEP3_VALUE\"='$STEP3_VALUE'
						WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";
                 
				//writeData($sql);
				
				$msgSexStock = retrieveMsgSexStock(['userId' => $userId , 'orgSel' => $STEP3_VALUE]);
				
				error_log('$msgSexStock = retrieveMsgSexStock([userId => $userId , orgSel => $STEP3_VALUE]) <<<<<<<<<<<<'.$userId);
				
				if($msgSexStock['msgType'] == 'template') {
					
					array_push($msg,$msgSexStock['msgVal']);
				}
				else {
					
					array_push($msg,$msgSexStock['msgVal']);
					
					$msgDeadType = retrieveMsgDeadType();
					
					//final
					array_push($msg,$msgDeadType['msgVal']);
				}
			}
			
			if(stristr($text,'!SelSexDe') ) {
				
				$msgDeadType = retrieveMsgDeadType();
					
				array_push($msg,$msgDeadType['msgVal']);
				
			}
			
			/*input qty 
			if (is_numeric($text)) {
				$sql =  " UPDATE  \"FR_DATA_COLLECTION\"
					SET  \"STEP_ACTION\"='Confirm', \"STEP2_VALUE\"='$text'
						WHERE \"USER_ID\" = '$userid' and \"PROCESS_NAME\" = 'DEADCULL' ";		
				writeData($sql);
				$messages = [
						'type' => 'template',
						'altText' => 'this is a confirm  template',
						'template' => [
							'type' => 'confirm',
							'text' => 'บันทึกตาย เล้า '.$row['STEP1_VALUE'].'  
									จำนวน  '.$text.' 
									ยืนยันข้อมูล ? ',
							'actions' => array(
								[
								'type' => 'message',
								'label' => 'ยืนยัน',
								'text' => '!YesDEADCULL',
								],[
								'type' => 'message',
								'label' => 'ยกเลิก',
								'text' => '!NoDEADCULL',									
								]
							)
						]
				];							
				
			} else {
				$messages = 
				[
						'type' => 'text',
						'text' => 'ระบุตัวเลข เท่านั้น !  กรุณาระบุใหม่อีกครั้ง'
				];					
			}*/
			

			// END SABPAROD LANDING HERE
			
			// $mes_line ='';
			
			// if ($messages_2 =='X') {
				// $mes_line = array (	$messages); 				
			// }else{
				// $mes_line = array (	$messages,$messages_2); 	
			// }

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				//'messages' => $mes_line,
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
				'text' => '!SelCvDe '.$arrData[0]['Farm_Name'],
				'val' => $arrData[0]['Farm_Code']
			]
		];
	}
	
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
				'text' => $arrData[0]['Farm_Org'],
				'val' => $arrData[0]['Farm_Org']
			]
		];
	}
	
	
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
				'label' => $val['Sex'],
				'data' => 'action=buy&itemid=123',
				'text' => '!SelSexDe '.$val['Sex'],
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
				'text' => $arrData[0]['Sex'],
				'val' => $arrData[0]['Sex']
			]
		];
	}
	
	
	return $ret;
}
function retrieveMsgDeadType() {
	$arrData = array([
		'name' =>  'ยืนตาย',
		'type' => 'L77'
	],[
		'name' => 'นอนตาย',
		'type' => 'L88'
	]);
	
	if(count($arrData) > 1) {
		$arrMessageDs = array(); 

		foreach ($arrData as $val) {
			array_push($arrMessageDs,[
				'type' => 'postback',
				'label' => $val['name'],
				'data' => 'action=buy&itemid=123',
				'text' => '!SelFarmDe '.$val['name'],
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
				'text' => $arrData[0]['name']
			]
		];
	}
	
	
	return $ret;
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
		case 'deadswine':
			$url = $url.'deadswine/'.$obj['userId'].','.$obj['orgSel'].','.$obj['deadType'].','.$obj['sex'].','.$obj['qty'];
			$keyValue = 'GenDeadSwineResult';
			break;
		case 'reasondead':
			$url = $url.'reasondead/'.$obj['userId'];
			$keyValue = 'GetReasonDeadsResult';
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
