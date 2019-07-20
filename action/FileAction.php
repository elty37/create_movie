<?php
require_once "/var/www/html/controller/CreateEXOController.php";
require_once "/var/www/html/entity/InputFileInfo.php";


class FileAction {
	private function validate($fileInputInfo, &$error){
/*
		if (pathinfo($fileInputInfo->fileName, PATHINFO_EXTENSION) != 'xlsx') {
			$error[] = "illigal Extension!";
			return false;
		}
		if(count($fileInputInfo->fileTmpName) < 1) {
			$error[] = "illigal input!";
			return false;
		}
*/
		return true;
	}
	private function getParams(){
		if (is_uploaded_file($_FILES["csvfile"]["tmp_name"])) {
			$fileTmpName = $_FILES["csvfile"]["tmp_name"];
			$fileName = $_FILES["csvfile"]["name"];		
		}
		if(isset($fileName) && isset($fileTmpName)) {
			$fileInputInfo = new FileInputInfo();
			$fileInputInfo->fileTmpName = $fileTmpName;
			$fileInputInfo->fileName = $fileName;
			return $fileInputInfo;
		} else{
			return false;
		}
	}
	public function execute(){	
//		$fileInputInfo = $this->getParams();
		$fileInputInfo = "";
		$error = [];
		if($this->validate($fileInputInfo, $error)) {
//			try{
				$readFile = "test3.xlsx";
				$controller = new CreateEXOController();
			//	$controller->readXlsx($readFile)

			//	$fp = $controller->saveFile($fileInputInfo);
			//	$convertedArray = $controller->convertArray($fp);
				$sheet = $controller->readXlsx($readFile);
				$c = dirname(__FILE__);
				$outputFile = fopen($c . "/tmp/out.exo" ,"w+");
				$wavOutputFile = fopen($c . "/tmp/out2.exo" ,"w+");
				$charactorImageOutputFile = fopen($c . "/tmp/out3.exo" ,"w+");
				$tmpwavlengthfile = fopen($c . "/tmp/wavlength.tmp" ,"w+");
				$tmpFile = fopen($c . "/tmp/conv.tmp" ,"w+");
				$controller->generateConvertedSerifFile($sheet, $tmpFile);
				fclose($tmpFile);
				$tmpFile = fopen($c . "/tmp/conv.tmp" ,"r+");
				$controller->getWavLength("/var/www/html/controller/tmp/*", $tmpwavlengthfile);
                fclose($tmpwavlengthfile);
				$wavTmpFile = fopen($c . "/tmp/wavlength.tmp", "r+");
				$controller->fileInit($outputFile);
				$controller->writeSerif($tmpFile, $outputFile, $wavTmpFile, $wavOutputFile, $charactorImageOutputFile);
				
				$lineOutputFile = fopen($c . "/tmp/out4.exo" ,"w+");
				$lineArray = $controller->readXlsxCustom($readFile, 1, "B2:D200");
				$controller->writeLine($lineOutputFile, $lineArray);
				return array();
			
/*			}catch(Exception $e){
				return $error;
			}
*/		
		} else {
			return $error;
		}
	}
}

