<?php
class ErrorHandler extends CErrorHandler
{
	/**
	 * whether the current request is an AJAX (XMLHttpRequest) request.
	 * @return boolean whether the current request is an AJAX request.
	 */
	protected function isAjaxRequest()
	{
		//return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
		return false; //Show answare in normal way also if it's ajax
	}
}
