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
$asdas = 'asdsdsdsd';

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
		
		$userid = $event['source']['userId'];
		
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
					
			$messages = 'X';
			$messages_2 = 'X';
					
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
			
			if ($text  == '!MaDeadCull') {
				
				
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
			}	
			
			
			if(stristr($text,'FARMSEL!') ) {
				
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
			
			$mes_line ='';
			
			if ($messages_2 =='X') {
				$mes_line = array (	$messages); 				
			}else{
				$mes_line = array (	$messages,$messages_2); 	
			}

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => $mes_line,
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

			$myfile = fopen('newfile.txt', 'w') or die('Unable to open file!');
			$txt = 'log '.$text.PHP_EOL;
			fwrite($myfile, $txt);	
			fwrite($myfile, print_r($result).PHP_EOL);
			fwrite($myfile, json_encode($messages).PHP_EOL);
			fwrite($myfile, json_encode($data).PHP_EOL);
			fclose($myfile);
			
			echo $result . '\r\n';
			
			
		}
	}
}
echo 'OK';


				
?>
