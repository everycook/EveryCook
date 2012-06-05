<?php
class CAjaxOptimizedClientScript extends CClientScript {
	/**
	 * Renders the registered scripts.
	 * This method is called in {@link CController::render} when it finishes
	 * rendering content. CClientScript thus gets a chance to insert script tags
	 * at <code>head</code> and <code>body</code> sections in the HTML output.
	 * @param string $output the existing output that needs to be inserted with script tags
	 */
	public function render(&$output) {
		if(!$this->hasScripts)
			return;
		
		if (method_exists(Yii::app()->controller,'getIsAjaxRequest')){
			$ajaxRequest = Yii::app()->controller->getIsAjaxRequest();
		} else {
			$ajaxRequest = Yii::app()->request->isAjaxRequest;
		}
		
		if (!$ajaxRequest)
			$this->renderCoreScripts();

		if(!empty($this->scriptMap))
			$this->remapScripts();

		$this->unifyScripts();

		if (!$ajaxRequest){
			$this->renderHead($output);
			if($this->enableJavaScript) {
				$this->renderBodyBegin($output);
				$this->renderBodyEnd($output);
			}
		} else {
			$this->renderAjaxHead($output);
			
			if($this->enableJavaScript) {
				$this->renderAjaxBodyBegin($output);
				$this->renderAjaxBodyEnd($output);
			}
		}
		
	}
	
	protected function replaceTagStartAndEnd($scripttag){
		$scripttag = str_replace("'<", "unescape('%3C') + '", $scripttag);
		$scripttag = str_replace(">'", "' + unescape('%3E')", $scripttag);
		return $scripttag;
	}
	protected function checkNotExistInsertLink($link){
		return "check = jQuery.('link[href=" . $link . "]'); if(check.length==0){" . $this->replaceTagStartAndEnd("jQuery('head').append('".CHtml::linkTag(null,null,null,null,$link)."');") . "}";
	}
	
	protected function checkNotExistInsertCssFile($url,$media){
		if($media!=='') {
			return "check = jQuery('link[href=\"" . $url . "\",media=\"" . $media . "\"]'); if(check.length==0){" . $this->replaceTagStartAndEnd("jQuery('head').append('".CHtml::cssFile($url,$media)."');") . "}";
		} else {
			return "check = jQuery('link[href=\"" . $url . "\"]'); if(check.length==0){" . $this->replaceTagStartAndEnd("jQuery('head').append('".CHtml::cssFile($url,$media)."');") . "}";
		}
	}
	
	protected function checkInsertReplaceMeta($meta){
		return "check = jQuery('meta[name=\"" . $meta['name'] . "\"]'); if(check.length!=0){check.attr('content','" . $meta['content'] . "');} else {" . $this->replaceTagStartAndEnd("jQuery('head').append('".CHtml::metaTag($meta['content'],null,null,$meta)."');") . "}";
	}
	
	protected function checkNotExistInsertScriptFile($scriptFile, $insertFunction){
		return "check = jQuery('script[src=\"" . $scriptFile . "\"]'); if(check.length==0){" . $this->replaceTagStartAndEnd($insertFunction . "('".CHtml::scriptFile($scriptFile)."');") . "}";
	}
	
	/**
	 * Inserts the scripts in the head section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderAjaxHead(&$output) {
		/*
		$count=preg_match('/(<title\b[^>]*>|<\\/head\s*>)/is',$output);
		if ($count){
			$this->renderHead($output);
		} else {
		*/
			$replaceScripts='';
			$html='';
			foreach($this->metaTags as $meta)
				$replaceScripts.=checkInsertReplaceMeta($meta)."\n";
			foreach($this->linkTags as $link)
				$replaceScripts.=$this->checkNotExistInsertLink($link)."\n";
			foreach($this->cssFiles as $url=>$media)
				$replaceScripts.=$this->checkNotExistInsertCssFile($url,$media)."\n";
				
			foreach($this->css as $css)
				$html.=CHtml::css($css[0],$css[1])."\n";
			if($this->enableJavaScript) {
				if(isset($this->scriptFiles[self::POS_HEAD])) {
					foreach($this->scriptFiles[self::POS_HEAD] as $scriptFile)
						$replaceScripts.=$this->checkNotExistInsertScriptFile($scriptFile, "jQuery('head').append")."\n";
				}

				if(isset($this->scripts[self::POS_HEAD]))
					$html.=CHtml::script(implode("\n",$this->scripts[self::POS_HEAD]))."\n";
			}
			if($replaceScripts!=='') {
				$html=CHtml::script($replaceScripts)."\n".$html;
			}
			if($html!=='') {
				$output=$html.$output;
			}
		//}
	}
	
	
	/**
	 * Inserts the scripts at the beginning of the body section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderAjaxBodyBegin(&$output)
	{
		$html='';
		$replaceScripts='';
		if(isset($this->scriptFiles[self::POS_BEGIN])) {
			foreach($this->scriptFiles[self::POS_BEGIN] as $scriptFile)
				$replaceScripts.=$this->checkNotExistInsertScriptFile($scriptFile, "jQuery('body').prepend")."\n";
		}
		if(isset($this->scripts[self::POS_BEGIN]))
			$html.=CHtml::script(implode("\n",$this->scripts[self::POS_BEGIN]))."\n";
		
		if($replaceScripts!=='') {
			$html=CHtml::script($replaceScripts)."\n".$html;
		}
		if($html!=='') {
			$output=$html.$output;
		}
	}

	/**
	 * Inserts the scripts at the end of the body section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderAjaxBodyEnd(&$output)
	{
		if(!isset($this->scriptFiles[self::POS_END]) && !isset($this->scripts[self::POS_END])
			&& !isset($this->scripts[self::POS_READY]) && !isset($this->scripts[self::POS_LOAD]))
			return;
		
		$fullPage=0;
		$output=preg_replace('/(<\\/body\s*>)/is','<###end###>$1',$output,1,$fullPage);
		$html='';
		$scripts=array();
		if(isset($this->scriptFiles[self::POS_END])) {
			foreach($this->scriptFiles[self::POS_END] as $scriptFile)
				$scripts[]=$this->checkNotExistInsertScriptFile($scriptFile, "jQuery('body').append");
		}
		if(isset($this->scripts[self::POS_END])) {
			$scripts[]=implode("\n",$this->scripts[self::POS_END]);
		}
		if(isset($this->scripts[self::POS_READY])) {
			if($fullPage)
				$scripts[]="jQuery(function($) {\n".implode("\n",$this->scripts[self::POS_READY])."\n});";
			else
				$scripts[]=implode("\n",$this->scripts[self::POS_READY]);
		}
		if(isset($this->scripts[self::POS_LOAD])) {
			if($fullPage)
				$scripts[]="jQuery(window).load(function() {\n".implode("\n",$this->scripts[self::POS_LOAD])."\n});";
			else
				$scripts[]=implode("\n",$this->scripts[self::POS_LOAD]);
		}
		if(!empty($scripts))
			$html.=CHtml::script(implode("\n",$scripts))."\n";
			
		if($fullPage)
			$output=str_replace('<###end###>',$html,$output);
		else
			$output=$output.$html;
	}
}