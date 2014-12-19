<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

Yii::import('zii.widgets.CListView');

class AjaxPagingListView extends CListView
{	
	public $template="{summary}\n{sorter}\n{items}";

	public function run()
	{
		if (!isset($_GET['ajaxPaging']) || !$_GET['ajaxPaging']){
			parent::run();
		} else {
			$this->renderItems();
		}
	}
	
	/**
	 * Renders the data item list.
	 */
	public function renderItems()
	{
		if (!isset($_GET['ajaxPaging']) || !$_GET['ajaxPaging']){
			echo CHtml::openTag($this->itemsTagName,array('class'=>$this->itemsCssClass))."\n";
		}
		$data=$this->dataProvider->getData();
		if(($n=count($data))>0)
		{
			$owner=$this->getOwner();
			$render=$owner instanceof CController ? 'renderPartial' : 'render';
			$j=0;
			$this->renderPagerPrev();
			foreach($data as $i=>$item)
			{
				$data=$this->viewData;
				$data['index']=$i;
				$data['data']=$item;
				$data['widget']=$this;
				$owner->$render($this->itemView,$data);
				if($j++ < $n-1)
					echo $this->separator;
			}
			$this->renderPagerNext();
		}
		else {
			echo '&nbsp;';
			$this->renderEmptyText();
		}
		if (!isset($_GET['ajaxPaging']) || !$_GET['ajaxPaging']){
			echo CHtml::closeTag($this->itemsTagName);
		}
	}
	
	/**
	 * Renders the summary text.
	 */
	public function renderSummary()
	{
		if(($count=$this->dataProvider->getItemCount())<=0)
			return;
		
		echo '<div class="'.$this->summaryCssClass.'">';
		if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('zii','Total {count} result(s).');
			$pagination=$this->dataProvider->getPagination();
			$total=$this->dataProvider->getTotalItemCount();
			$start=$pagination->currentPage*$pagination->pageSize+1;
			$end=$start+$count-1;
			if($end>$total)
			{
				$end=$total;
				$start=$end-$count+1;
			}
			echo strtr($summaryText,array(
				'{start}'=>$start,
				'{end}'=>$end,
				'{count}'=>$total,
				'{page}'=>$pagination->currentPage+1,
				'{pages}'=>$pagination->pageCount,
			));
		echo '</div>';
	}

	/**
	 * Renders the pager.
	 */
	public function renderPagerPrev()
	{
		if(!$this->enablePagination)
			return;

		$pager=array('prev'=>true);
		$class='AjaxPager';
		$pager['pages']=$this->dataProvider->getPagination();
		$this->widget($class,$pager);
	}
	
	/**
	 * Renders the pager.
	 */
	public function renderPagerNext()
	{
		if(!$this->enablePagination)
			return;

		$pager=array('prev'=>false);
		$class='AjaxPager';
		$pager['pages']=$this->dataProvider->getPagination();
		$this->widget($class,$pager);
	}
}
