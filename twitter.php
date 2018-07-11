<?php
$tgBotKey = getenv('tgBotKey');
$twAuth = getenv('twAuth');
ini_set('default_charset', 'utf-8');
ini_set('display_errors', 1);
$de = file_get_contents('php://input');
$d = json_decode($de);
$cid = $d->message->message_id;
$mid = $d->message->chat->id;
$mes = urldecode($d->message->text);
if ($mes == 'on') {
	file_put_contents('status.txt', 'started');
file_get_contents("https://api.telegram.org/$tgBotKey/sendMessage?chat_id=$mid&text=ok");
exit();
}

if ($mes == 'off') {
	//file_put_contents('status', 'started');
	unlink(realpath('status.txt'));
	unlink('status.txt');
	exec('rm status.txt');
exec('rm maxid.txt');
	file_get_contents("https://api.telegram.org/$tgBotKey/sendMessage?chat_id=$mid&text=ok");
exit();
	}
	if(strpos($mes, '/change') !== false) {
		unlink(realpath('maxid.txt'));
		$key = str_replace('/change ', '', $mes);
		$key = urlencode($key . " exclude:retweets");
		$file = fopen('status.txt', 'w');
		fwrite($file, '?q=' . $key . '&result_type=recent&count=100');
		fclose($file);
		file_get_contents("https://api.telegram.org/$tgBotKey/sendMessage?chat_id=$mid&text=ok");
exit();
		}
		if (file_exists('maxid.txt')) {
		$max = file_get_contents('maxid.txt');
		$maxid = '&since_id=' . $max;
		}
		else {
			$max = '1';
			}
		$get = file_get_contents('status.txt');
$ur = 'https://api.twitter.com/1.1/search/tweets.json';
$gets = $get . $maxid;
$url = $ur . $gets . '&tweet_mode=extended';
$ch = curl_init($url);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array($twAuth)); 
$res = curl_exec($ch);
file_put_contents('log.json', $res);
$tweets = json_decode($res);
//echo $res;
$x = 50;
echo $url . "\n";
$url = urlencode($url);
echo $url;
file_get_contents("https://api.telegram.org/$tgBotKey/sendMessage?chat_id=32709704&text=$url");
while ($x >= 0) {
	if ($tweets->statuses[$x]->id_str > $max) {
$text = $tweets->statuses[$x]->text;
//$text = $tweets->statuses[$x]->retweeted_status->text;
//$op = $tweets->statuses[$x]->retweeted_status->user->screen_name;
$named = $tweets->statuses[$x]->user->name;
$username = $tweets->statuses[$x]->user->screen_name;
$id = $tweets->statuses[$x]->id;
$createdat = $tweets->statuses[$x]->created_at;
$src_dt = $createdat;
$src_tz =  new DateTimeZone
('UTC');
$dest_tz = new DateTimeZone
('Asia/Tehran');
$dt = new DateTime($src_dt, $src_tz);
$dt->setTimeZone($dest_tz);
$twtime = $dt->format('Y-m-d H:i:s');
$tosen = "$named (@$username):\n$text\n$twtime";
$tosend = urlencode($tosen);
$opz = [[array("text"=>"URL","url"=>"https://twitter.com/$username/status/$id")]];
$dlkey = json_encode(array("inline_keyboard"=>$opz));
file_get_contents("https://api.telegram.org/$tgBotKey/sendMessage?chat_id=32709704&text=$tosend&reply_markup=$dlkey");
}
$x--;
}
echo 'ok';
if (isset($tweets->statuses[0]->id_str)) {
$file = fopen('maxid.txt', 'w');
fwrite($file, $tweets->statuses[0]->id_str);
fclose($file);
}
//teskfkfjrjdjrjr
//jfjjfjrirjfnfnfn
// (¯ｰ¯)(ﾟ∇^*)＾ω＾σ(^○^)σ(^○^)(*´ー`)(ﾟ∇^*)(¯ｰ¯)(¯ｰ¯)(¯ｰ¯)▼ω▼▼ω▼(ﾟ∇^*)v(¯∇¯dddfffghgtgjjkkfrd
