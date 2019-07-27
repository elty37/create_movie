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
	


	public function setMoveHeight($height, $isNew = false) {
		if (!$isNew) {
			$nextYStart = $this->moveChatBalloon->chatTextNotes->yEnd;
		} else {
			$nextYStart = 1000;
		}
		if ($nextYStart - $height >= self::LINE_LIMIT_HEIGHT) {
			if ($isNew) {
				$this->moveChatBalloon = clone $this->fixedChatBalloon;
			}
			$this->moveChatBalloon->setMoveHeight($height);
			return true;
		}
		return false;
	}

	public function setFixedBalloonByBeforeMoveBalloon($moveBalloon) {
		$newBalloonSet->fixedChatBalloon = clone $moveBalloon;
		$newBalloonSet->fixedChatBalloon->setYStartFromYEnd();
		$newBalloonSet->fixedChatBalloon->setStart($newBalloonSet->fixedChatBalloon->chatTextNotes->start);
		
	}
}
