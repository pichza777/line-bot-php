<?php

namespace LINE;

$access_token = 'aAMls5K18jevEiIZZlJ1zyu5u8gLxv7FGOYcaHak5tt2Zni2NfzWs5nfapzErLNnBsK8TlaCnHJvxBg1md67eMxWaFPl9GE/sCKzFpC0mM1ai70aKau/lF+0svFNjCWq8Zv1+RMvO4eRAVeYfoEybwdB04t89/1O/w1cDnyilFU=';

$profileData = '';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON

$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		$userId = $event['source']['userId'];
		
		$bot = new \LINE\LINEBot(new CurlHTTPClient('aAMls5K18jevEiIZZlJ1zyu5u8gLxv7FGOYcaHak5tt2Zni2NfzWs5nfapzErLNnBsK8TlaCnHJvxBg1md67eMxWaFPl9GE/sCKzFpC0mM1ai70aKau/lF+0svFNjCWq8Zv1+RMvO4eRAVeYfoEybwdB04t89/1O/w1cDnyilFU='), [
			'channelSecret' => '047a51ae1557c6a602e7a417d2e68182'
		]);

		$res = $bot->getProfile($userId);
		if ($res->isSucceeded()) {
			$profile = $res->getJSONDecodedBody();
			$displayName = $profile['displayName'];
			$statusMessage = $profile['statusMessage'];
			$pictureUrl = $profile['pictureUrl'];
		}
		
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$user = $event['profile'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $userId//$sss //'ทดสอบ '. ' : ' .$text . ' '. $event
				];

			
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
		else
		{
			
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$user = $event['profile'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $event['type'] . ' ' . $event['source']['userId']//$sss //'ทดสอบ '. ' : ' .$text . ' '. $event
				];

			
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
}
echo "OK";