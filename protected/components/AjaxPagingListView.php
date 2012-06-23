<?php
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
		else
			$this->renderEmptyText();
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
