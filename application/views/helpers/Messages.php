<?php
class Site_View_Helper_Messages extends Zend_View_Helper_Abstract{

	/**
	 *
	 */
	public function messages($isCurrent=false,$class='messages')
	{
		Log::debug($this,"messages");
		$messenger=Zend_Controller_Action_HelperBroker::getStaticHelper('flashMessenger');
		$buffer="";
		//Текущие сообщения
//		if($isCurrent && $messenger->hasCurrentMessages()){
//			Log::debug($this,"current message",$messenger->getCurrentMessages());
//			$buffer.="<div class=\"$class\">";
//			foreach($messenger->getCurrentMessages() as $message)
//				$buffer.="<p>$message</p>";
//			$buffer.="</div>";
//		}
		//Не текущие сообщения
		if(/*!$isCurrent && */$messenger->hasMessages()){
			Log::debug($this,"not current message",$messenger->getMessages());
			$buffer.="<div class=\"$class\">";
			foreach($messenger->getMessages() as $message)
				$buffer.="<p>$message</p>";
			$buffer.="</div>";
		}
		return $buffer;
	}

}