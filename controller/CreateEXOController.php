<?php
require_once "/var/www/html/vendor/autoload.php";
require_once "/var/www/html/entity/TextNotes.php";
require_once "/var/www/html/entity/ImageNotes.php";

class CreateEXOController{

	const FRAMERATE = 30;
	const MESSAGE_MAX = 200;
	const VOICE_BUFFER = 45;
	const LECTURE_START = 20011;
	const LECTURE_END = 20139;
	const HUUNA_SERIF_COLOR = "f6f694";
	const SAKURA_SERIF_COLOR = "ffadb4";
	const DEFAULT_SERIF_COLOR = "ffffff";
	const SAKURA_LAYER_NUMBER = 4;
	const HUUNA_LAYER_NUMBER = 6;
	const DEFAULT_LAYER_NUMBER = 8;
	const SAKURA_VOICE_LAYER_NUMBER = 5;
	const HUUNA_VOICE_LAYER_NUMBER = 7;
	const DEFAULT_VOICE_LAYER_NUMBER = 9;
	const SAKURA_ILLUST_LAYER_NUMBER = 1;
	const HUUNA_ILLUST_LAYER_NUMBER = 2;
	const DEFAULT_ILLUST_LAYER_NUMBER = 3;


	public function saveFile($fileInputInfo){
		if (!move_uploaded_file($file_tmp_name, "../../data/uploaded/" . $file_name)) {
			return false;
		}
		chmod("../../data/uploaded/" . $file_name, 0644);
		$file = '../../data/uploaded/'.$file_name;
		$fp   = fopen($file, "r");
		return $fp;	
	}
	public function convertArray($fp){
		$convertArray = array();
		while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
			$convertArray[] = $data;
		}
		return $convertArray;
	}
	public function removeFile($fp){
		fclose($fp);
		unlink('../../data/uploaded/'.$file_name);
	}
	public function createEXOFile($convertedArray){

	}
	public function downloadEXOFile($exo){
		$filepath = '';
		$filename = 'out.exo';
		header('Content-Length: '.filesize($filepath));
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		readfile($filepath);
	}
	//解説モードのposition変換
	private function convertImagePositionLecture($fileName){
		$positionX = array();
		$positionY = array();

		$positionX["風奈"]   =  "-86";
		$positionX["桜"]     =  "-86";
		$positionX["さくら"] = "-86";
		$positionY["風奈"]   = "316";
		$positionY["桜"]     = "316";
		$positionY["さくら"] = "316";

		foreach($positionX as $key => $value) {
			if(strpos($fileName, $key) !== false) {
				$positionReturn = $positionX[$key] . "," . $positionY[$key];
			}
		}
		return $positionReturn;
	}

	//基本モードのposition変換
	public function convertImagePosition($fileName){
		$positionX = array();
		$positionY = array();

		$positionX["風奈"]   =  "-193";
		$positionX["桜"]     =  "-193";
		$positionX["干物風奈"] = "193";
		$positionX["さくら制服"] = "240";

		$positionY["風奈"]   = "56";
		$positionY["桜"]     = "56";
		$positionY["さくら制服"] = "56";
		$positionY["干物風奈"] = "56";

		foreach($positionX as $key => $value) {
			if(strpos($fileName, $key) !== false) {
				$positionReturn = $positionX[$key] . "," . $positionY[$key];
			}
		}

		return $positionReturn;
	}

	//出力ファイル初期設定
	//outputFile 出力ファイルオブジェクト
	public function fileInit($outputFile){
		$init = "[exedit]" .  "\r\n" . "width=640" .  "\r\n" . "height=480" .  "\r\n" . "rate=" . self::FRAMERATE .  "\r\n" . "scale=1" .  "\r\n" . "length=4055" .  "\r\n" . "audio_rate=44100" .  "\r\n" . "audio_ch=2" .  "\r\n";
		$init = mb_convert_encoding($init, 'SJIS-win', 'UTF-8');
		$fwrite = fwrite($outputFile, $init);
		if ($fwrite === false) {
			return false;
		}
		return true;
	}

	//テキスト成形
	public function setText($text, $newText){
		return $text . $newText . "\r\n";
	}
	//講義モードのキャラを出力
	//$outputFile 出力ファイルオブジェクト
	//$filePath エンコードしたセリフ
	//$serifNumber [NNN]に入る数
	//$layer レイヤ
	//$startPoint 開始フレーム
	//$endPoint 終了フレーム
	//$posX X
	//$posY Y
	public function convertImageLecture($outputFile,$filePath,$serifNumber,$layer,$startPoint,$endPoint,$posX,$posY){
		$formattedSerif = "";
		$formattedSerif = $this->setText($formattedSerif ,"[" . $serifNumber . "]");
		$formattedSerif = $this->setText($formattedSerif , "start=" . $startPoint);
		$formattedSerif = $this->setText($formattedSerif , "end=" . $endPoint);
		$formattedSerif = $this->setText($formattedSerif , "layer=" . $layer);
		$formattedSerif = $this->setText($formattedSerif , "overlay=1");
		$formattedSerif = $this->setText($formattedSerif , "camera=0");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".0]");
		$formattedSerif = $this->setText($formattedSerif , "_name=画像ファイル");
		$formattedSerif = $this->setText($formattedSerif , "file=" . $filePath);
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".1]");
		$formattedSerif = $this->setText($formattedSerif , "_name=マスク");
		$formattedSerif = $this->setText($formattedSerif , "X=0.0");
		$formattedSerif = $this->setText($formattedSerif , "Y=-174.8 ");
		$formattedSerif = $this->setText($formattedSerif , "回転=0.00");
		$formattedSerif = $this->setText($formattedSerif , "サイズ=155");
		$formattedSerif = $this->setText($formattedSerif , "縦横比=0.0");
		$formattedSerif = $this->setText($formattedSerif , "ぼかし=0");
		$formattedSerif = $this->setText($formattedSerif , "マスクの反転=0");
		$formattedSerif = $this->setText($formattedSerif , "元のサイズに合わせる=0");
		$formattedSerif = $this->setText($formattedSerif , "type=5");
		$formattedSerif = $this->setText($formattedSerif , "name=");
		$formattedSerif = $this->setText($formattedSerif , "mode=0");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".2]");
		$formattedSerif = $this->setText($formattedSerif , "_name=縁取り");
		$formattedSerif = $this->setText($formattedSerif , "_disable=1");
		$formattedSerif = $this->setText($formattedSerif , "サイズ=3");
		$formattedSerif = $this->setText($formattedSerif , "ぼかし=10");
		$formattedSerif = $this->setText($formattedSerif , "color=00ff00");
		$formattedSerif = $this->setText($formattedSerif , "file=");
		$formattedSerif = $this->setText($formattedSerif , "ぼかし=10");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".3]");
		$formattedSerif = $this->setText($formattedSerif , "_name=クリッピング");
		$formattedSerif = $this->setText($formattedSerif , "上=107");
		$formattedSerif = $this->setText($formattedSerif , "下=456");
		$formattedSerif = $this->setText($formattedSerif , "左=136");
		$formattedSerif = $this->setText($formattedSerif , "右=146");
		$formattedSerif = $this->setText($formattedSerif , "中心の位置を変更=0");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".4]");
		$formattedSerif = $this->setText($formattedSerif , "_name=標準描画");
		$formattedSerif = $this->setText($formattedSerif , "X=" . $posX);
		$formattedSerif = $this->setText($formattedSerif , "Y=" . $posY);
		$formattedSerif = $this->setText($formattedSerif , "拡大率=75.00");
		$formattedSerif = $this->setText($formattedSerif , "透明度=0.0");
		$formattedSerif = $this->setText($formattedSerif , "回転=0.00");
		$formattedSerif = $this->setText($formattedSerif , "blend=0");
		$formattedSerif = mb_convert_encoding($formattedSerif, 'SJIS-win', 'UTF-8');
		fwrite($outputFile, $formattedSerif);
	}
	//日常モードのキャラを出力
	//$outputFile 出力ファイルオブジェクト
	//$filePath エンコードしたセリフ
	//$serifNumber [NNN]に入る数
	//$layer レイヤ
	//$startPoint 開始フレーム
	//$endPoint 終了フレーム
	//$posX X
	//$posY Y
	public function convertImage($outputFile,$filePath,$serifNumber,$layer,$startPoint,$endPoint,$posX,$posY) {
		error_log($posX , 3, "/var/www/html/error_log");
		$formattedSerif = "";
		$formattedSerif = $this->setText($formattedSerif ,"[" . $serifNumber . "]");
		$formattedSerif = $this->setText($formattedSerif , "start=" . $startPoint);
		$formattedSerif = $this->setText($formattedSerif , "end=" . $endPoint);
		$formattedSerif = $this->setText($formattedSerif , "layer=" . $layer);
		$formattedSerif = $this->setText($formattedSerif , "overlay=1");
		$formattedSerif = $this->setText($formattedSerif , "camera=0");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".0]");
		$formattedSerif = $this->setText($formattedSerif , "_name=画像ファイル");
		$formattedSerif = $this->setText($formattedSerif , "file=" . $filePath);
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".1]");
		$formattedSerif = $this->setText($formattedSerif , "_name=標準描画");
		$formattedSerif = $this->setText($formattedSerif , "X=" . $posX);
		$formattedSerif = $this->setText($formattedSerif , "Y=" . $posY);
		$formattedSerif = $this->setText($formattedSerif , "拡大率=75.00");
		$formattedSerif = $this->setText($formattedSerif , "透明度=0.0");
		$formattedSerif = $this->setText($formattedSerif , "回転=0.00");
		$formattedSerif = $this->setText($formattedSerif , "blend=0");
		$formattedSerif = mb_convert_encoding($formattedSerif, 'SJIS-win', 'UTF-8');
		fwrite($outputFile, $formattedSerif);
	}

	//セリフを出力
	//$outputFile 出力ファイルオブジェクト
	//serif エンコードしたセリフ
	//$serifNumber [NNN]に入る数
	//$layer レイヤ
	//$startPoint 開始フレーム
	//$endPoint 終了フレーム
	//$color 文字色
	public function convertSerif($outputFile,$serif,$serifNumber,$layer,$startPoint,$endPoint,$color) {
		$formattedSerif = "";
		$formattedSerif = $this->setText($formattedSerif ,"[" . $serifNumber . "]");
		$formattedSerif = $this->setText($formattedSerif , "start=" . $startPoint);
		$formattedSerif = $this->setText($formattedSerif , "end=" . $endPoint);
		$formattedSerif = $this->setText($formattedSerif , "layer=" . $layer);
		$formattedSerif = $this->setText($formattedSerif , "overlay=1");
		$formattedSerif = $this->setText($formattedSerif , "camera=0");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".0]");
		$formattedSerif = $this->setText($formattedSerif , "_name=テキスト");
		$formattedSerif = $this->setText($formattedSerif , "サイズ=34");
		$formattedSerif = $this->setText($formattedSerif , "表示速度=0.0");
		$formattedSerif = $this->setText($formattedSerif , "文字毎に個別オブジェクト=0");
		$formattedSerif = $this->setText($formattedSerif , "移動座標上に表示する=0");
		$formattedSerif = $this->setText($formattedSerif , "自動スクロール=0");
		$formattedSerif = $this->setText($formattedSerif , "B=0");
		$formattedSerif = $this->setText($formattedSerif , "I=0");
		$formattedSerif = $this->setText($formattedSerif , "type=0");
		$formattedSerif = $this->setText($formattedSerif , "autoadjust=0");
		$formattedSerif = $this->setText($formattedSerif , "soft=1");
		$formattedSerif = $this->setText($formattedSerif , "monospace=0");
		$formattedSerif = $this->setText($formattedSerif , "align=0");
		$formattedSerif = $this->setText($formattedSerif , "spacing_x=0");
		$formattedSerif = $this->setText($formattedSerif , "spacing_y=0");
		$formattedSerif = $this->setText($formattedSerif , "precision=1");
		$formattedSerif = $this->setText($formattedSerif , "color=" . $color);
		$formattedSerif = $this->setText($formattedSerif , "color2=000000");
		$formattedSerif = $this->setText($formattedSerif , 'font=あずきフォント');
		$formattedSerif = $formattedSerif . "text=" . $serif;
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".1]");
		$formattedSerif = $this->setText($formattedSerif , "_name=標準描画");
		$formattedSerif = $this->setText($formattedSerif , "X=-310.0");
		$formattedSerif = $this->setText($formattedSerif , "Y=142.0");
		$formattedSerif = $this->setText($formattedSerif , "Z=0.0");
		$formattedSerif = $this->setText($formattedSerif , "拡大率=80.00");
		$formattedSerif = $this->setText($formattedSerif , "透明度=0.0");
		$formattedSerif = $this->setText($formattedSerif , "回転=0.00");
		$formattedSerif = $this->setText($formattedSerif , "blend=0");
		$formattedSerif = mb_convert_encoding($formattedSerif, 'SJIS-win', 'UTF-8');
		fwrite($outputFile, $formattedSerif);
	}

	//セリフを出力
	//$outputFile 出力ファイルオブジェクト
	//serif エンコードしたセリフ
	//$serifNumber [NNN]に入る数
	//$layer レイヤ
	//$startPoint 開始フレーム
	//$endPoint 終了フレーム
	//$color 文字色
	public function convertWav($outputFile,$wavFileName,$serifNumber,$layer,$startPoint,$endPoint) {

		$formattedSerif = "";
		$formattedSerif = $this->setText($formattedSerif ,"[" . $serifNumber . "]");
		$formattedSerif = $this->setText($formattedSerif , "start=" . $startPoint);
		$formattedSerif = $this->setText($formattedSerif , "end=" . $endPoint);
		$formattedSerif = $this->setText($formattedSerif , "layer=" . $layer);
		$formattedSerif = $this->setText($formattedSerif , "overlay=1");
		$formattedSerif = $this->setText($formattedSerif , "audio=1");
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".0]");
		$formattedSerif = $this->setText($formattedSerif , "_name=音声ファイル");
		$formattedSerif = $this->setText($formattedSerif , "再生位置=0.00");
		$formattedSerif = $this->setText($formattedSerif , "再生速度=100.0");
		$formattedSerif = $this->setText($formattedSerif , "ループ再生=0");
		$formattedSerif = $this->setText($formattedSerif , '動画ファイルと連携=0');
		$formattedSerif = $this->setText($formattedSerif , "file=C:\movie\character voice\current\\" . $wavFileName);
		$formattedSerif = $this->setText($formattedSerif , "[" . $serifNumber . ".1]");
		$formattedSerif = $this->setText($formattedSerif , "_name=標準再生");
		$formattedSerif = $this->setText($formattedSerif , "音量=300.0");
		$formattedSerif = $this->setText($formattedSerif , "blend=0");
		$formattedSerif = mb_convert_encoding($formattedSerif, 'SJIS-win', 'UTF-8');
		fwrite($outputFile, $formattedSerif);
	}

	//風奈 :0
	//桜   :1
	//その他:99
	private function whoIsThis($charactor){
		if($charactor == "風奈") {
			return "0";
		} elseif($charactor == "桜") {
			return "1";
		} else {
			return "99";
		}	
	}


	public function getWavLength($filePath, $fpout) {
        foreach (glob('/var/www/html/controller/tmp/{*.wav}',GLOB_BRACE) as $file) {
            if (is_file($file)) {
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
					if (!isset($minites)) {
						$minites = 0;
					}
					$seconds = intval($sec % 60);
                    fwrite($fpout, str_replace( ".wav", "", str_replace("/var/www/html/controller/tmp/" , "" , $file)) . " " . ($seconds + $minites * 60) * 30 . "\n");
                    fclose($fp);
                }
            }
        }
	}
	public function searchWavLength($lines, $fileName){
		$fileName = str_replace(PHP_EOL, '', $fileName);
		foreach($lines as $line){
			$col = explode(" ", $line);
			if( count($col) > 1 ){
				if( $col[0] == $fileName ){
					return intval($col[1]) + self::VOICE_BUFFER;
				}
			}
		}
		return 0;
	}
	/**
	 * エクセル読み込み
	 */
	public function readXlsx($readFile)
	{
		$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load("/var/www/html/" . $readFile);
		$sheet = $spreadsheet->getSheet(0);
		//B2のセルの値
		$data = $sheet->rangeToArray("C3:H175");
		return $data;

	}

	/**
	 * エクセル読み込み(範囲指定Ver)
	 */
	public function readXlsxCustom($readFile, $sheetNumber, $position)
	{
		$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load("/var/www/html/" . $readFile);
		$sheet = $spreadsheet->getSheet($sheetNumber);
		$data = $sheet->rangeToArray($position);
		return $data;

	}

	/**
	* 文字コードの変換(utf16のリトルエンディアン)
	* @param string $str 変換文字列
	* @return string 変換された文字列
	*/
	private function convertSerifToHexUncodeText($str)
	{
		$str = mb_convert_encoding($str, "UTF-16LE");
		$str = bin2hex($str);
		$strlength = strlen($str);
		for($i = 0; $i<(4096 - $strlength); $i++)
		{
			$str = $str . "0";
		}
		return strtoupper($str);
	}
	public function generateConvertedSerifFile($sheet, $fp)
	{
		$currentMode = "daily";
		$i = 0;
		While ($i < count($sheet)){
			$line = $sheet[$i][0];
			if( strlen($line) < 1 ){
				$i = $i + 1;
				continue;
			}
			$Serif = $sheet[$i][3];
			$filePath = $sheet[$i][4];
			$fileName = $sheet[$i][5];
			if( strlen($fileName) > 0 ){
				if( $line == self::LECTURE_START ){
					$currentMode = "lecture";
				}
				if( $line == self::LECTURE_END ){
					$currentMode = "daily";
				}
				if( $currentMode == "daily" ){
					$pos = $this->convertImagePosition($fileName);
				}else{
					$pos = $this->convertImagePositionLecture($fileName);
				}
                $tmppos = $pos;
				$pos = explode(",", $pos);
				$posX = $pos[0];
				$posY = $pos[1];
			}else{
				$filePath = "skip";
				$posX = "";
				$posY = "";
			}
			$charactor = $sheet[$i][2];
			if( strlen($line) > 0 and strlen($charactor) > 0 ){
				fwrite($fp, $this->whoIsThis($charactor) ."\n");
				fwrite($fp, $line . "\n");
				fwrite($fp, $filePath . " " . $posX . " " . $posY . "\n");
				fwrite($fp, $this->convertSerifToHexUncodeText($Serif) . "\n");
				/*
				   objShell.Run "cmd /c echo " & whoIsThis(charactor) & " >> conv.tmp", 0, True;
				   objShell.Run "cmd /c echo " & line & " >> conv.tmp", 0, True;
				   objShell.Run "cmd /c echo " & filePath & " " & posX & " " & posY & " >> conv.tmp", 0, True;
				   objShell.Run "cmd /c TestClass.exe " & Serif & " >> conv.tmp", 0, True;
				 */
			}

			$i = $i + 1;

		}
	}
	/**
	 * 
	 */
	public function writeSerif($tmpFile,$outputFile,$wavtmpFile,$wavOutputFile, $charactorImageOutputFile)
	{
		$startPoint = 50;
		$endPoint = 50;
		$charactor = "";
		$i = 0;
		$j = 0;

		$currentMode = "daily";
		$wavContent = "";
		while ($line = fgets($wavtmpFile)) {
			$wavContent = $wavContent . "," . $line;
		}
		$wavLines = explode(",", $wavContent);
		while ($lineStr = fgets($tmpFile)) {
			if( $i % 4 == 3 ){
				$charactor = str_replace(" ", "", $charactor);
				if( $charactor == "0" ) {
					$this->convertSerif($outputFile,$lineStr,($i - ($i % 4)) / 4,self::SAKURA_LAYER_NUMBER,$startPoint,$endPoint,self::SAKURA_SERIF_COLOR);
					$this->convertWav($wavOutputFile, $wavFileName, ($i - ($i % 4)) / 4, self::SAKURA_VOICE_LAYER_NUMBER,$startPoint, $endPoint);
					if( $currentMode == "lecture" ) {
						$this->convertImageLecture($charactorImageOutputFile,$filePath,$j,self::SAKURA_ILLUST_LAYER_NUMBER,$startPoint,$endPoint,$posX,$posY);
					} else {
						$this->convertImage($charactorImageOutputFile, $filePath, $j, self::SAKURA_ILLUST_LAYER_NUMBER, $startPoint, $endPoint, $posX, $posY);
					}
					$j = $j + 1;
				} elseif( $charactor == "1" ) {
					$this->convertSerif($outputFile,$lineStr,($i - ($i % 4)) / 4, self::HUUNA_LAYER_NUMBER, $startPoint, $endPoint, self::HUUNA_SERIF_COLOR);
					$this->convertWav($wavOutputFile, $wavFileName, ($i - ($i % 4)) / 4, self::HUUNA_VOICE_LAYER_NUMBER, $startPoint, $endPoint);
					if( $currentMode == "lecture" ) {
						$this->convertImageLecture($charactorImageOutputFile, $filePath, $j, self::HUUNA_ILLUST_LAYER_NUMBER, $startPoint, $endPoint, $posX, $posY);
					} else {
						$this->convertImage($charactorImageOutputFile, $filePath, $j, self::HUUNA_ILLUST_LAYER_NUMBER, $startPoint, $endPoint, $posX, $posY);
					}
					$j = $j + 1;                
				} else {
					$this->convertSerif($outputFile,$lineStr,($i - ($i % 4)) / 4, self::DEFAULT_LAYER_NUMBER, $startPoint, $endPoint, self::DEFAULT_SERIF_COLOR);
					$this->convertWav($wavOutputFile,$wavFileName,($i - ($i % 4)) / 4, self::DEFAULT_LAYER_NUMBER, $startPoint, $endPoint);
				}
			} elseif( $i % 4 == 1 ) {
				$lineStr = str_replace(" ", "", $lineStr);
				$startPoint = $endPoint + 1;
				$endPoint = $startPoint + $this->searchWavLength($wavLines, $lineStr);
				$lineStr = str_replace(PHP_EOL, '', $lineStr);
				$wavFileName = $lineStr . ".wav";
				$currentPoint = $lineStr;
				if( $currentPoint == self::LECTURE_START ){
					$currentMode = "lecture"; 
				}
				if( $currentPoint == self::LECTURE_END ){
					$currentMode = "daily";
				}
			} elseif( $i % 4 == 2 ) {
				$col = explode(" ", $lineStr);
				$filePath = $col[0];
				$posX = $col[1];
				$posY = $col[2];
			} else {
				$charactor = $lineStr;
				$charactor = str_replace(PHP_EOL, '', $charactor);
			}
			$i = $i + 1;
		}
	}

	/**
	 * 解説モードのライン風画面EXOを生成
	 */
	public function writeLine($lineOutputFile, $lineArray) {
		$length=40;
		$res = "";

		for ($i=0; $i<count($lineArray); $i++) {
			if (is_null($lineArray[$i][0]) || strlen($lineArray[$i][0]) === 0 )
			{
				continue;
			}

			$huunaTextNote = new TextNotes();
			$huunaTextNote->start = $length * $i + 1;
			$huunaTextNote->end = $length * ($i + 1);
			$huunaTextNote->layer = 5;
			$huunaTextNote->notesNumber = $i;
			$huunaTextNote->xStart = "154.0";
			$huunaTextNote->yStart = "40.0";
			$huunaTextNote->xEnd = null;
			$huunaTextNote->yEnd = null;
			$huunaTextNote->text = $lineArray[$i][2];
			$huunaTextNote->moveType = $huunaTextNote::MOVE_HIGH_LOW;
			$res = $res . $huunaTextNote->printDefaultNotes();
			$res = $res . $huunaTextNote->printImageTextNotes(0);
			$res = $res . $huunaTextNote->printDefaultPrintNotes(1);	
		}
		$res =  mb_convert_encoding($res, 'SJIS-win', 'UTF-8');
		fwrite($lineOutputFile, $res);
	}
}
