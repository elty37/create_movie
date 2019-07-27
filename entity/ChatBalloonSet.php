<?php
require_once "/var/www/html/entity/ChatBalloonSet.php";
/**
 * 吹き出しクラス
 * 
 * 
 */
class ChatBalloonSet {

	const LINE_LIMIT_HEIGHT = -231;

	/** @var ChatBalloon $fixedChatBalloon 移動しないバルーン */
	public $fixedChatBalloon;
	/** @var ChatBalloon $moveChatBalloon 移動するバルーン */
	public $moveChatBalloon;
	/**@ver bool $isCreated 次のノーツがある */	
	public $isCreated = false;
    function __clone()
    {
        // this->object のコピーを作成します。こうしないと、
        // 同じオブジェクトを指すことになってしまいます。
		$this->fixedChatBalloon = clone $this->fixedChatBalloon;
		$this->moveChatBalloon = clone $this->moveChatBalloon;
		$this->isCreated = clone $this->isCreated;
    }
	public function setMoveHeight($height, $isNew = false) {
		if (!$isNew) {
			$nextYStart = $this->fixedChatBalloon->chatTextNotes->yStart;
		} else {
			$nextYStart = 1000;
		}
		error_log($nextYStart,"3","/var/www/html/debug.log");
		$nextHeight = intval($nextYStart) - $height;
		if (is_null($this->moveChatBalloon)) {
			$this->moveChatBalloon = clone $this->fixedChatBalloon;
		}
		if (intval($nextHeight) >= self::LINE_LIMIT_HEIGHT) {
			$this->moveChatBalloon->setStart($this->moveChatBalloon->chatTextNotes->end+1, 5);
			$this->moveChatBalloon->setMoveHeight($height);
			return true;
		}
		return false;
	}

	public function setFixedBalloonByBeforeMoveBalloon($moveBalloon, $balloonSet) {
		$this->fixedChatBalloon = clone $moveBalloon;
		$this->fixedChatBalloon->setYStartFromYEnd();
		$this->fixedChatBalloon->setStart($balloonSet->moveChatBalloon->chatTextNotes->start + 1, 200);
		
	}
}
