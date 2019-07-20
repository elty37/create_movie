<?php
require_once "/var/www/html/entity/Notes.php";
/**
 * 画像ノーツクラス
 */
class ImageNotes Extends Notes {
	/** @var string $file 画像ファイルパス */
	public $file;

	/**
	 * 画像の基本exo出力を返す
	 * @return string 画像基本ノーツexo
	 */
	public function printImageNotes() {
		return "[" . $this->notesNumber . ".0]\r\n_name=画像ファイル\r\nfile=" . $this->file . "\r\n";
	}
		
}
