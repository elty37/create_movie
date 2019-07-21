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

	/** @var bool $isMove 移動用ノーツならtrue */
	public $isMove = false;

	/** @var bool $hasMove 移動用ノーツ生成済みならtrue */
	public $hasMove = false;

	/** @var bool $hasFix 移動用ノーツの次の固定ノーツがあればtrue */
	public $hasFix = false;


	/** @var bool $isCurrentNotes 生成中ノーツならtrue */
	public $isCurrentNotes = false;

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
			$res = $res . "X=". $this->xStart . "," . $this->xEnd . "," . $this->moveType . "\r\n";
			if (is_null($this->yEnd)) {
				$xEnd = $this->yStart;
			}
			$res = $res . "Y=". $this->yStart . "," . $this->yEnd . "," . $this->moveType . "\r\n";
		}
		$res = $res . "Z=0.0\r\n拡大率=" . $this->extendRate . "\r\n透明度=" . $this->invisibility . "\r\n回転=" . $this->rotate . "\r\nblend=" . $this->blend . "\r\n";
		return $res;
	}



	public static function printInitialLineBase() 
	{
		return "[0]\r\nstart=1\r\nend=3719\r\nlayer=1\r\noverlay=1\r\ncamera=0\r\n[0.0]\r\n_name=図形\r\nサイズ=400\r\n縦横比=57.3\r\nライン幅=4000\r\ntype=2\r\ncolor=d2d2fc\r\nname=\r\n[0.1]\r\n_name=クリッピング\r\n上=0\r\n下=57\r\n左=0\r\n右=0\r\n中心の位置を変更=0\r\n[0.2]\r\n_name=標準描画\r\nX=231.0\r\nY=-31.0\r\nZ=0.0\r\n拡大率=100.00\r\n透明度=0.0\r\n回転=0.00\r\nblend=0\r\n";
	}
}
