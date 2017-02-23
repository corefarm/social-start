<?php


$access_token = 'ki/sALGeAGtfPJsCbQY+Ama0bBSByknlDdsU32D1fnAGwt2/L9KqasU/HxA9ojgPHNcSaItAV2cJEasYBZj1qQ+dZOEt7ZKaTz/OG7ZZNISFHh4NWE/P5Mg7hX84D+AZtaYHVjv2VS9oQiObD6Kl+QdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
					
			$messages = [
					'type' => 'text',
					'text' => 'ตอบจาก  Bot v3.1.11 : '.$text
			];	
					

			if (strtolower($text)  == 'im') {
				$messages = [
						'type' => 'image',
						'originalContentUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
						'previewImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig_pre.jpg'
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
			
			if (strtolower($text)  == 'tmp') {
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
								'label' => 'Buy',
								'data' => 'action=buy&itemid=123',
								],[
								'type' => 'postback',
								'label' => 'Sale',
								'data' => 'action=buy&itemid=123',									
								],[
								'type' => 'uri',
								'label' => 'View detail',
								'uri' => 'http://www.cpfworldwide.com/th',							
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
										'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/logostockcf.png',
										'title' => 'Stock',
										'text' => 'รายงานคงเหลือ ',
										'actions' => array(
											[
											'type' => 'postback',
											'label' => 'รายงานคงเหลือ',
											'data' => 'action=buy&itemid=123',
											],[
											'type' => 'uri',
											'label' => 'View detail',
											'uri' => 'https://www.google.com/',							
											]
									)],	
									[
										'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/po.jpg',
										'text' => 'สั่งซื้อ ชิ้นส่วนสุกร ',
										'actions' => array(
											[
											'type' => 'postback',
											'label' => 'สั่งซื้อ',
											'data' => 'action=buy&itemid=123',
											],[
											'type' => 'uri',
											'label' => 'View detail',
											'uri' => 'https://www.google.com/',							
											]
									)]									
							)													
						]
				];			
			}			 		



			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
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
			fclose($myfile);
			
			echo $result . '\r\n';
			
			
		}
	}
}
echo 'OK';


				
?>
