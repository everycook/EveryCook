<?php
class ViewAction extends CViewAction {
	/**
	 * Runs the action.
	 * This method displays the view requested by the user.
	 * @throws CHttpException if the view is invalid
	 */
	public function run()
	{
		$this->resolveView($this->getRequestedView());
		$controller=$this->getController();
		
		if($this->layout!==null)
		{
			$layout=$controller->layout;
			$controller->layout=$this->layout;
		}
		
		$this->onBeforeRender($event=new CEvent($this));
		if(!$event->handled)
		{
			if($this->renderAsText)
			{
				$text=file_get_contents($controller->getViewFile($this->view));
				$controller->renderText($text);
			}
			else
			{
				if($controller->getIsAjaxRequest()){
					$output=$this->renderPartial($this->view);
				} else {
					$controller->render($this->view);
				}
			}
			$this->onAfterRender(new CEvent($this));
		}
		if($this->layout!==null)
			$controller->layout=$layout;
	}
}