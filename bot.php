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
					'text' => 'ตอบจาก  Bot v2 : '.$text.' to key' 
			];	
					

			if ($text == 'im') {
				$messages = [
						'type' => 'image',
						'originalContentUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
						'previewImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig_pre.jpg'
				];			
			}	

			if ($text == 'cf') {
				$messages = [
						'type' => 'template',
						'altText' => 'this is a confirm  template',
						'template' => [
							'type' => 'confirm',
							'text' => 'Are you sure?',
							'action' => [
								[
								'type' => 'message',
								'label' => 'Yes',
								'data' => 'yes',
								],[
								'type' => 'message',
								'label' => 'No',
								'data' => 'No',									
								]
							]
						]
				];			
			}			
			
			if ($text == 'tmp') {
				$messages = [
						'type' => 'template',
						'altText' => 'this is a buttons template',
						'template' => [
							'type' => 'buttons',
							'thumbnailImageUrl' => 'https://immense-stream-37827.herokuapp.com/pig.jpg',
							'title' => 'Menu',
							'text' => 'Please select',
							'action' => [
								[
								'type' => 'postback',
								'label' => 'Buy',
								'data' => 'action=buy&itemid=123',
								],[
								'type' => 'postback',
								'label' => 'Sale',
								'data' => 'action=buy&itemid=123',									
								],[
								'type' => 'url',
								'label' => 'View detail',
								'data' => 'http://www.cpfworldwide.com/th',							
								]
							]
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

			echo $result . '\r\n';
			
			
		}
	}
}
echo 'OK';
?>
