<?php

class AjaxPager extends CBasePager
{
	/**
	 * @var boolean if there is also a previous link / pager.
	 */
	public $prev = false;
	/**
	 * @var array HTML attributes for the enclosing 'div' tag.
	 */
	public $htmlOptions=array();

	/**
	 * Initializes the pager by setting some default property values.
	 */
	public function init()
	{
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$this->getId();
		
		
		Yii::app()->controller->trans->GENERAL_CLICK_TO_LOAD_NEXTPAGE;
		Yii::app()->controller->trans->GENERAL_LOADING_PREVPAGE;
		Yii::app()->controller->trans->GENERAL_CLICK_TO_LOAD_PREVPAGE;
	}

	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return;
		$currentPage = $this->getCurrentPage();
		$params = $this->getController()->getActionParams();
		
		if ($this->prev){
			$this->prev = false;
			if ($currentPage<=1)
				return;
			if (isset($_GET['noPrev']) && $_GET['noPrev'])
				return;
			$nextPage = $currentPage-1;
			$params['noNext'] = true;
			$text = Yii::app()->controller->trans->GENERAL_LOADING_NEXTPAGE;
		} else {
			if ($currentPage>=$pageCount)
				return;
			if (isset($_GET['noNext']) && $_GET['noNext'])
				return;
			$nextPage = $currentPage+1;
			$params['noPrev'] = true;
			$text = Yii::app()->controller->trans->GENERAL_LOADING_PREVPAGE;
		}
		$text = 'load next page';
		
		$params['ajaxPaging'] = true;
		$this->getPages()->params = $params;
		
		$url = $this->getPages()->createPageUrl($this->getController(),$nextPage);
		
		echo '<div id="ajaxPaging' . (($nextPage<$currentPage)?'Prev':'') . '" class="ajaxPagingAutoLoad">';
		echo '<input type="hidden" class="pagingUrl" value="' . $url . '"/>';
		echo '<div class="pagingText">' . $text . '</div>';
		echo '<div class="pagingLoading"></div>';
		echo '</div>';
	}
}