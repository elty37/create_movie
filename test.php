<?php
require_once "vendor/autoload.php";

function excelTest(){
	// ファイル名の指定
	$readFile = "test2.xlsx";

	// 連想配列でデータ受け取り
	$data = readXlsx($readFile);

	// 出力確認
	print '<pre>';
	//echo convertSerifToHexUncodeText($data[0][5]);
	echo convertSerifToHexUncodeText($data[0][5]);
	print '</pre>';

}
// ファイル名渡したら配列返すラッパー関数
function readXlsx($readFile)
{
	$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	$spreadsheet = $reader->load($readFile);
	$sheet = $spreadsheet->getSheet(0);
	//B2のセルの値
	$data = $sheet->rangeToArray("C3:H175");
	return $data;

}
function convertSerifToHexUncodeText($str)
{
	$str = mb_convert_encoding($str, "UTF-16LE");
	$str = bin2hex($str);
	for($i = 0; $i<4096 - strlen($str); $i++)
	{
		$str = $str . "0";
	}
	return $str;
}
// 文字列のユニコードエンコードを行う
function unicode_encode($str) {
  return preg_replace_callback("/\\\\u([0-9a-zA-Z]{4})/", "encode_callback", $str);
}
function utf8_to_unicode_code($utf8_string)
{
    $expanded = iconv("UTF-8", "UTF-32", $utf8_string);
    return unpack("L*", $expanded);
}
function wavDur($file) {
  $fp = fopen($file, 'r');
  if (fread($fp,4) == "RIFF") {
    fseek($fp, 20);
    $rawheader = fread($fp, 16);
    $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits',$rawheader);
    $pos = ftell($fp);
    while (fread($fp,4) != "data" && !feof($fp)) {
      $pos++;
      fseek($fp,$pos);
    }
    $rawheader = fread($fp, 4);
    $data = unpack('Vdatasize',$rawheader);
    $sec = $data["datasize"]/$header["bytespersec"];
    $minutes = intval(($sec / 60) % 60);
    $seconds = intval($sec % 60);
    return str_pad($minutes,2,"0", STR_PAD_LEFT).":".str_pad($seconds,2,"0", STR_PAD_LEFT);
  }
}

echo wavDur("test.wav");
