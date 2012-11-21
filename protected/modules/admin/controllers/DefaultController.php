<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->checkRenderAjax('index');
	}
}