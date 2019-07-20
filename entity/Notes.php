<?php
/**
 * 基本ノーツクラス
 * start=1368
 * end=2705
 * layer=2
 * overlay=1
 * camera=0
 */
class Notes {
	/** 移動幅 */
	const MOVE_ROW_4 = 90;
	const MOVE_ROW_3 = 70;
	const MOVE_ROW_2 = 40;
	const MOVE_ROW_1 = 20;
	
	/** 改行文字幅 */
	const RETURN_WIDTH = 18;

	/** 加減速移動 */
	const MOVE_HIGH_LOW = 103;
	/**等速移動 */
	const MOVE_NORMAL = 1;

	/** @var int $start 開始フレーム */
	public $start;

	/** @var int $end 終了フレーム */
	public $end;

	/** @var int $layer レイヤ */
	public $layer;

	/** @var string $overlay ?? */
	public $overlay = 1;

	/** @var string $camera ?? */
	public $camera = 0;

	/** @var int $notesNumber ノーツ番号 */
	public $notesNumber;

	/** @var string $xStart x座標開始位置 */
	public $xStart;

	/** @var string $yStart y座標開始位置 */
	public $yStart;

	/** @var string $xEnd x座標終了位置 */
	public $xEnd = null;

	/** @var string $yEnd y座標終了位置 */
	public $yEnd = null;

	/** @var int $moveType 移動タイプ */
	public $moveType;

	/** @var string $extendRate 拡大率 */
	public $extendRate = "100.00";

	/** @var string $invisibility 透明率 */
	public $invisibility = "0.0";

	/** @var string $rotate 回転 */
	public $rotate = "0.00";

	/** @var int $blend 合成タイプ */
	public $blend = 0;

	public function printDefaultNotes() {
		return "[" . $this->notesNumber . "]\r\nstart=" . $this->start . "\r\nend=" . $this->end . "\r\nlayer=" . $this->layer . "\r\noverlay=" . $this->overlay . "\r\ncamera=" . $this->camera . "\r\n";
	}

	/**
	 * 画像の標準描画exo出力を返す
	 * @param int $effectNumber 効果番号
	 * @return string 画像基本ノーツexo
	 */
	public function printDefaultPrintNotes($effectNumber) {
		$res = "[" . $this->notesNumber . "." . $effectNumber . "]\r\n_name=標準描画\r\n";
		if (is_null($this->xEnd) && is_null($this->yEnd)) {
			$res = $res . "X=". $this->xStart . "\r\n" . "Y=$this->yStart\r\n";			
		} else {
			if (is_null($this->xEnd)) {
				$xEnd = $this->xStart;
			}
			$res = $res . "X=". $this->xStart . "," . $xEnd . "," . $this->moveType . "\r\n";
			if (is_null($this->yEnd)) {
				$xEnd = $this->yStart;
			}
			$res = $res . "Y=". $this->yStart . "," . $yEnd . "," . $this->moveType . "\r\n";
		}
		$res = $res . "Z=0.0\r\n拡大率=" . $this->extendRate . "\r\n透明度=" . $this->invisibility . "\r\n回転=" . $this->rotate . "\r\nblend=" . $this->blend . "\r\n";
		return $res;
	}

	/**
	 * 移動させる幅を返す
	 * @return int 移動幅(px)
	 */
	public function getMovePx() {
		$width = mb_strwidth($this->text);
		$rowNum = $width / self::RETURN_WIDTH;
		switch($rowNum) {
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
