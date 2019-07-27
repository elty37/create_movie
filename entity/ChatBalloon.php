<?php
require_once "/var/www/html/entity/TextNotes.php";
require_once "/var/www/html/entity/ImageNotes.php";
/**
 * 吹き出しクラス
 * 
 * 
 */
class ChatBalloon {

	// /** 左右の区分 */
	// public const RIGHT = 0;
	// public const LEFT  = 1;
	
	/** @var ImageNotes $chatImageNotes チャット吹き出しイメージNotes  */
	public $chatImageNotes;
	/** @var ImageNotes $chatCharactorIconNotes チャットキャラアイコンイメージNotes  */
	public $chatCharactorIconNotes;
	/** @var TextNotes $chatTextNotes チャットテキストイメージNotes  */
	public $chatTextNotes; 

	public function setMoveHeight($height) {
		$this->chatImageNotes->setMoveHeight($height);
		$this->chatCharactorIconNotes->setMoveHeight($height);
		$this->chatTextNotes->setMoveHeight($height);
	}

	public function setYStartFromYEnd() {
		$this->chatImageNotes->yStart = $this->chatImageNotes->yEnd;
		$this->chatCharactorIconNotes->yStart = $this->chatCharactorIconNotes->yEnd;
		$this->chatTextNotes->yStart = $this->chatTextNotes->yEnd;
		$this->chatImageNotes->yEnd = null;
		$this->chatCharactorIconNotes->yEnd = null;
		$this->chatTextNotes->yEnd = null;

	}

	public function setStart($start) {
		$this->chatImageNotes->start = $start;
		$this->chatCharactorIconNotes->start = $start;
		$this->chatTextNotes->start = $start;
		$this->chatImageNotes->end = $start + 200;
		$this->chatCharactorIconNotes->end = $start + 200;
		$this->chatTextNotes->end = $start + 200;

	}
}
