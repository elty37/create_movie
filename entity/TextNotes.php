<?php
require_once "/var/www/html/entity/Notes.php";
/**
 * テキストノーツクラス
 * 
 * 
 */
class TextNotes extends Notes {

	/** @ver int $size 文字サイズ */
	public $size=12;
	/** @ver string $printSpeed 表示速度 */
	public $printSpeed="0.0";
	/** @ver int $textAsObject 文字毎に個別オブジェクト */
	public $textAsObject=0;
	/** @ver int $visibleOrbital 移動座標上に表示する */
	public $visibleOrbital=0;
	/** @ver int $autoScroll 自動スクロール */
	public $autoScroll=0;
	/** @ver int $bold ボールド */
	public $bold=0;
	/** @ver int $italic イタリック */
	public $italic=0;
	/** @ver int $type 合成タイプ */
	public $type=0;
	/** @ver int $type ??? */
	public $autoadjust=0;
	/** @ver int ??? */
	public $soft=1;
	/** @ver int ??? */
	public $monospace=0;
	/** @ver int ??? */
	public $align=0;
	/** @ver int ??? */
	public $spacing_x=0;
	/** @ver int ??? */
	public $spacing_y=0;
	/** @ver int ??? */
	public $precision=1;
	/** @ver string $color 色 */
	public $color="202020";
	/** @ver string $color2 影色 */
	public $color2="000000";
	/** @ver string $font フォント */
	public $font="メイリオ";
	/** @ver string $text 表示する文書(生) */
	public $text;
	/** @ver string $text 表示する文書(UTF16LEエンコード) */
	public $encodedText;

	public function printDefaultNotes() {
		return "[" . $this->notesNumber . "]\r\nstart=" . $this->start . "\r\nend=" . $this->end . "\r\nlayer=" . $this->layer . "\r\noverlay=" . $this->overlay . "\r\ncamera=" . $this->camera . "\r\n";
	}

	/**
	 * テキストexo出力を返す
	 * @param int $effectNumber 効果番号
	 * @return string 画像基本ノーツexo
	 */
	public function printImageTextNotes($effectNumber) {
		$res = "[" . $this->notesNumber . "." . $effectNumber . "]\r\n_name=テキスト\r\n";
		$res = $res . "サイズ=" . $this->size . "\r\n";
		$res = $res . "表示速度=" . $this->printSpeed . "\r\n";
		$res = $res . "文字毎に個別オブジェクト=" . $this->textAsObject . "\r\n";
		$res = $res . "移動座標上に表示する=" . $this->visibleOrbital . "\r\n";
		$res = $res . "自動スクロール=" . $this->autoScroll . "\r\n";
		$res = $res . "B=" . $this->bold . "\r\n";
		$res = $res . "I=" . $this->italic . "\r\n";
		$res = $res . "type=" . $this->type . "\r\n";
		$res = $res . "autoadjust=" . $this->autoadjust . "\r\n";
		$res = $res . "soft=" . $this->soft . "\r\n";
		$res = $res . "monospace=" . $this->monospace . "\r\n";
		$res = $res . "align=" . $this->align . "\r\n";
		$res = $res . "spacing_x=" . $this->spacing_x . "\r\n";
		$res = $res . "spacing_y=" . $this->spacing_y . "\r\n";
		$res = $res . "precision=" . $this->precision . "\r\n";
		$res = $res . "color=" . $this->color . "\r\n";
		$res = $res . "color2=" . $this->color2 . "\r\n";
		$res = $res . "font=" . $this->font . "\r\n";
		if(is_null($this->encodedText) || strlen($this->encodedText) === 0) {
			$res = $res . "text=" . $this->setConvertText($this->text) . "\r\n";
		} else {
			$res = $res . "text=" . $this->encodedText . "\r\n";
		}
		return $res;
	}

	/**
	* 文字コードの変換(utf16のリトルエンディアン)
	* @return string 変換された文字列
	*/
	public function setConvertText()
	{
		$str = $this->text;
		$str = mb_convert_encoding($str, "UTF-16LE");
		$str = bin2hex($str);
		$strlength = strlen($str);
		for($i = 0; $i<(4096 - $strlength); $i++)
		{
			$str = $str . "0";
		}
		$this->encodedText = $str;
		return $str;
	}
	/**
	 * 移動させる幅を返す
	 * @return int 移動幅(px)
	 */
	public function getMovePx() {
		$width = mb_strwidth($this->text);
		$rowNum = floor($width / self::RETURN_WIDTH);
		switch($rowNum) {
			case 0:
				$res = self::MOVE_ROW_1;
				break;
			case 1:
				$res = self::MOVE_ROW_1;
				break;
			case 2:
				$res = self::MOVE_ROW_2;
				break;
			case 3:
				$res = self::MOVE_ROW_3;
				break;
			case 4:
				$res = self::MOVE_ROW_4;
				break;
			default:
				$res = self::MOVE_ROW_4;
		}
		return $res;
	}
}
