<?php

$player = @json_decode(@file_get_contents('http://api.xwis.co.uk/player.php?player='. $_GET['nick'] .'&game='. $_GET['game']));
if (empty($player)) {
  //die(header('Status: 404 Not Found'));
  $player = new stdClass();
  $player->name = $_GET['nick'];
  $player->game = $_GET['game'];
  $player->ladder = 0;
  $player->wins = 0;
  $player->losses = 0;
  $player->points = 0;
  $player->discon = 0;
  $player->recon = 0;
  $player->status->online = 0;
} else {
  $player->status = @json_decode(@file_get_contents('http://api.xwis.co.uk/online.php?mode=player&player='. $_GET['nick'] .'&game='. $_GET['game']));
  if (empty($player->status)) $player->status->online = 0;
  if (isset($player->countries)) $player->countries = (array)$player->countries;
  if (isset($player->maps)) $player->maps = (array)$player->maps;
  if (!isset($player->discon)) $player->discon = 0;
  if (!isset($player->recon)) $player->recon = 0;
}

for($i = 1; $i <= 2; $i++) {
	$stat = $_GET['stat'. $i];
	if ($stat == 'co' &&  (!isset($player->countries) || empty($player->countries))) $stat = 'la';
	if ($stat == 'ma' &&  (!isset($player->maps) || empty($player->maps))) $stat = 'ra';

	switch($stat) {
		case 'la':
			$statName = 'Ladder';
			$statValue = '#'. $player->ladder;
		break;

		case 'wi':
			$statName = 'Wins';
			$statValue = $player->wins;
		break;

		case 'lo':
			$statName = 'Losses';
			$statValue = $player->losses;
		break;

		case 'po':
			$statName = 'Points';
			$statValue = $player->points;
		break;

		case 'di':
			$statName = 'Disconnects';
			$statValue = $player->discon;
		break;

		case 're':
			$statName = 'Reconnects';
			$statValue = $player->recon;
		break;

		case 'co':
			if ($_GET['game'] == 'ts') $statName = 'Faction';
			else $statName = 'Country';
			// not using array_shift() due to element removal
			reset($player->countries);
			$statValue = current(array_keys($player->countries));
		break;

		case 'ma':
			$statName = 'Map';
			reset($player->maps);
			$statValue = current(array_keys($player->maps));
		break;

		case 'fp':
			$statName = 'FPS';
			$statValue = $player->fps;
		break;

		case 'ti':
			$statName = ucfirst($player->time[1]);
			$statValue = $player->time[0];
		break;

		default:
			if (!isset($player->ratio)) $player->ratio = (($player->losses > 0) ? ($player->wins / $player->losses) : $player->wins);
			$statName = 'W/L Ratio';
			$statValue = round($player->ratio, 2);
		break;
	}

	$stats[] = array('name' => $statName, 'value' => $statValue);
	unset($stat, $statName, $statValue);

}

$player->rank = @json_decode(@file_get_contents('http://api.xwis.co.uk/rank.php?wins='. $player->wins .'&losses='. $player->losses .'&recon='. $player->recon .'&discon='. $player->discon));

$player->xpBar = round(($player->rank->experience - $player->rank->rank->begin) / ($player->rank->rank->next - $player->rank->rank->begin) * 400);
$player->xpText = $player->rank->experience .'/'. $player->rank->rank->next;

$valid = array('yr', 'gdi', 'nod', 'allied', 'soviet', 'gdic', 'nodc');
if (!in_array($_GET['faction'], $valid)) $_GET['faction'] = 'allied';
$faction['icon'] = '/home/sean/xwis.us/xwis.co.uk/sig/img/'. $_GET['faction'] .'.png';
$faction['logo'] = getimagesize($faction['icon']);
//die(sprintf('<pre>%s</pre>', print_r($faction['logo'][1], 1)));

if (preg_match('/gdic|nodc/', $_GET['faction'])) $_GET['faction'] = substr($_GET['faction'], 0,3);

$layers = array(
	array(
		'src' => imagecreatefrompng('/home/sean/xwis.us/xwis.co.uk/sig/img/'. $_GET['faction'] .'bg.png'),
		'src_w' => 400, 'src_h' => 100, 'dst_x' => 0, 'dst_y' => 0,
	),
	array(
		'src' => imagecreatefrompng($faction['icon']),
		'src_w' => $faction['logo'][0], 'src_h' => $faction['logo'][1], 'dst_x' => 348, 'dst_y' => 5,
	),
);

$image = imagecreatetruecolor(400, 71);

foreach($layers as $key => $layer) {
	imagecopy($image, $layer['src'], $layer['dst_x'], $layer['dst_y'], 0, 0, $layer['src_w'], $layer['src_h']);
}

$font = '/home/sean/xwis.us/xwis.co.uk/sig/fonts/BebasNeue-webfont.ttf';
$white = imagecolorallocate($image, 255, 255, 255);
$lgrey = imagecolorallocate($image, 224, 224, 224);
$dgrey = imagecolorallocate($image, 194, 194, 194);
$black = imagecolorallocate($image, 0, 0, 0);
$charc = imagecolorallocate($image, 29, 29, 29);
$yr = imagecolorallocate($image, 204, 0, 204);
$allied = imagecolorallocate($image, 57, 143, 190);
$nod = $soviet = imagecolorallocate($image, 150, 32, 32);
$gdi = imagecolorallocate($image, 156, 155, 111);
$red = imagecolorallocate($image, 240, 0, 0);
$green = imagecolorallocate($image, 0, 240, 0);

$coords[] = array('s' => 28, 'x' => 10, 'y' => 33, 'c' => $white, 't' => $player->name);
$dimensions = imagettfbbox(28, 0, $font, $player->name);
$x = ($dimensions[4] + 12);
$coords[] = array('s' => 10, 'x' => $x, 'y' => 16, 'c' => $dgrey, 't' => $player->rank->rank->name);
$coords[] = array('s' => 14, 'x' => $x, 'y' => 33, 'c' => $lgrey, 't' => $stats[0]['name']);

$dimensions = imagettfbbox(14, 0, $font, $stats[0]['name']);
$x = ($x + 2 + $dimensions[4]);
$coords[] = array('s' => 14, 'x' => $x, 'y' => 33, 'c' => $white, 't' => $stats[0]['value']);

$dimensions = imagettfbbox(14, 0, $font, $stats[0]['value']);
$x = ($x + 8 + $dimensions[4]);
$coords[] = array('s' => 14, 'x' => $x, 'y' => 33, 'c' => $lgrey, 't' => $stats[1]['name']);

$dimensions = imagettfbbox(14, 0, $font, $stats[1]['name']);
$x = ($x + 2 + $dimensions[4]);
$coords[] = array('s' => 14, 'x' => $x, 'y' => 33, 'c' => $white, 't' => $stats[1]['value']);

$coords[] = array('s' => 10, 'x' => 10, 'y' => 66, 'c' => $dgrey, 't' => $player->game .' Status');
$dimensions = imagettfbbox(10, 0, $font, $player->game .' Status');
$x = ($dimensions[4] + 12);
if ($player->status->online == 0) {
  $coords[] = array('s' => 10, 'x' => $x, 'y' => 66, 'c' => $red, 't' => 'Offline');
} else {
  $coords[] = array('s' => 10, 'x' => $x, 'y' => 66, 'c' => $green, 't' => $player->status->locale);
}

$dimensions = imagettfbbox(10, 0, $font, $player->xpText);
$x = ((imagesx($image) - 7) - (abs($dimensions[4] - $dimensions[0])));
$coords[] = array('s' => 10, 'x' => $x, 'y' => 66, 'c' => $white, 't' => $player->xpText);
$dimensions = imagettfbbox(10, 0, $font, 'Experience');
$x = (($x - 2) - (abs($dimensions[4] - $dimensions[0])));
$coords[] = array('s' => 10, 'x' => $x, 'y' => 66, 'c' => $dgrey, 't' => 'Experience');

foreach($coords as $coord) imagettftext($image, $coord['s'], 0, $coord['x'], $coord['y'], $coord['c'], $font, $coord['t']);

imagefilledrectangle($image, 10, 40, 390, 52, $dgrey);
imagefilledrectangle($image, 11, 41, 389, 51, $white);
if ($player->xpBar > 13) imagefilledrectangle($image, 12, 42, $player->xpBar, 50, ${$_GET['faction']});

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

?>
