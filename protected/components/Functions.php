<?php
class Functions {
	const IMG_TYPE_GIF = 1;
	const IMG_TYPE_JPG = 2;
	const IMG_TYPE_PNG = 3;
	
	const DROP_DOWN_LIST = 0;
	const CHECK_BOX_LIST = 1;
	
	public static function preparedStatementToStatement($command, $params){
		$sql = $command->getText();
		foreach($params as $key => $value){
			$sql = str_replace($key, $value, $sql);
		}
		return Yii::app()->db->createCommand($sql);
	}

	public static function searchCriteriaInput($label, $model, $fieldName, $dataList, $type, $id, $htmlOptions) {
		$html = '<div class="row" id="'.$id.'">';
		$html .= CHtml::activeLabel($model,$fieldName, array('label'=>$label));
		$html .= ' ';
		if ($type == 0){
			$html .= CHtml::dropDownList(CHtml::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
		} else if ($type == 1){
			$html .= '<ul class="search_choose">';
			$html .= CHtml::checkBoxList(CHtml::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
			$html .= '</ul>';
			$html .= '<div class="clearfix"></div>';
		}
		$html .= '</div>';
		
		return $html;
	}
	
	public static function createInput($label, $model, $fieldName, $dataList, $type, $id, $htmlOptions, $form) {
		$html = '<div class="row" id="'.$id.'">';
		$html .= CHtml::activeLabelEx($model, $fieldName, array('label'=>$label));
		$html .= ' ';
		if ($type == 0){
			$html .= CHtml::dropDownList(CHtml::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
		} else if ($type == 1){
			$html .= '<ul class="search_choose">';
			$html .= CHtml::checkBoxList(CHtml::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
			$html .= '</ul>';
			$html .= '<div class="clearfix"></div>';
		}
		if ($form){
			$html .= $form->error($model, $fieldName);
		}
		$html .= '</div>';
		
		return $html;
	}
	
	/*
	Logic from CHtml::resolveName
	*/
	public static function resolveArrayName($model,$attribute,$index){
		if(($pos=strpos($attribute,'['))!==false) {
			if($pos!==0)  // e.g. name[a][b]
					return get_class($model).'['.$index.']'.'['.substr($attribute,0,$pos).']'.substr($attribute,$pos);
			if(($pos=strrpos($attribute,']'))!==false && $pos!==strlen($attribute)-1)  // e.g. [a][b]name
			{
				$sub=substr($attribute,0,$pos+1);
				$attribute=substr($attribute,$pos+1);
				return get_class($model).'['.$index.']'.$sub.'['.$attribute.']';
			}
			if(preg_match('/\](\w+\[.*)$/',$attribute,$matches))
			{
				$name=get_class($model).'['.$index.']'.'['.str_replace(']','][',trim(strtr($attribute,array(']['=>']','['=>']')),']')).']';
				$attribute=$matches[1];
				return $name;
			}
		}
		return get_class($model).'['.$index.']'.'['.$attribute.']';
	}
	
	public static function createInputTable($valueArray, $fieldOptions, $options, $form, $text) {
		if (isset($options['new'])){
			$new = $options['new'];
			$new->unsetAttributes(); // clear any default values
			unset($options['new']);
		}
		
		$html = '<table class="addRowContainer">';
		$html .= '<thead><tr>';
		
		$visibleFields = 0;
		foreach($fieldOptions as $field){
			if($field[1]){
				$html .='<th>'.$field[1].'</th>';
				$visibleFields++;
			}
		}
		$html .='<th>'.$text['options'].'</th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		$i = 1;
		foreach($valueArray as $value){
			$html .= '<tr class="'.(($i % 2 == 1)?'odd':'even').'">';
			foreach($fieldOptions as $field){
				if($field[1]){
					if (is_array($field[2])){
						$html .= '<td>'.CHtml::dropDownList(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0]), $field[2], $field[3]).'</td>';
					} else {
						$html .= '<td>'.CHtml::textField(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0])).'</td>';
					}
				} else {
					$html .= CHtml::hiddenField(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0]));
				}
			}
			$html .= '<td><div class="buttonSmall remove">' . $text['remove'] . '</div><div class="buttonSmall up">' . $text['move up'] . '</div><div class="buttonSmall down">' . $text['move down'] . '</div></td>';
			$html .= '</tr>';
			$i++;
		}
		
		if ($new){
			$newhtml = '<tr class="%class%">';
			foreach($fieldOptions as $field){
				if($field[1]){
					if (is_array($field[2])){
						$newhtml .= '<td>'.CHtml::dropDownList(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0]), $field[2], $htmlOptions).'</td>';
					} else {
						$newhtml .= '<td>'.CHtml::textField(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0])).'</td>';
					}
				} else {
					$newhtml .= CHtml::hiddenField(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0]));
				}
			}
			$newhtml .= '<td><div class="buttonSmall remove">' . $text['remove'] . '</div><div class="buttonSmall up">' . $text['move up'] . '</div><div class="buttonSmall down">' . $text['move down'] . '</div></td>';
			$newhtml .= '</tr>';
			
			$html .= '<tr class="'.(($i % 2 == 1)?'odd':'even').'">';
			$html .= '<td colspan="'.$visibleFields.'"><div class="buttonSmall add">' . $text['add'] . '</div>'. CHtml::hiddenField('addContent', $newhtml).CHtml::hiddenField('lastIndex', $i).'</td>';
			$html .= '</tr>';
		}
		
		$html .= '</tbody></table>';
		
		return $html;
	}
	
	public static function resizePicture($file, $file_new, $width, $height, $qualitaet, $destType)
	{
		if(!file_exists($file))
			return false;
		$info = getimagesize($file);

		if($info[2] == 1){
			$image = imagecreatefromgif($file);
		} elseif($info[2] == 2) {
			$image = imagecreatefromjpeg($file);
		} elseif($info[2] == 3) {
			$image = imagecreatefrompng($file);
		} else  {
				return false;
		}
		if ($destType == -1){
			$destType = $info[2];
		}
		
	/*    echo $info[0]. " ".$info[1]; //Breite * Höhe
		if ($info[0] < $info[1]){
			$temp=$height;
			$width=$height;
			$height=$width;
		}*/
		if ($width && ($info[0] < $info[1])){
			$width = ($height / $info[1]) * $info[0];
		} else { 
		  $height = ($width / $info[0]) * $info[1]; 
		}
		$imagetc = imagecreatetruecolor($width, $height);
		if (($info[0] > $width) or ($info[1] > $height)){
			imagecopyresampled($imagetc, $image, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
		} else {
			if ($info[2] == $destType){
				copy($file,$file_new);
				return;
			} else {
				$imagetc = $image;
			}
		}
		
		$transparent=imagecolortransparent($image);
		imagecolortransparent($imagetc,$transparent);
		
		if($destType == 1){
			imagegif($imagetc, $file_new);  
			//imagejpeg($imagetc, $file_new, $qualitaet);  
		} elseif($destType == 2) {
			imagejpeg($imagetc, $file_new, $qualitaet);  
		} elseif($destType == 3) {
			imagepng($imagetc, $file_new);  
		} else  {
			imagejpeg($imagetc, $file_new, $qualitaet);  
		}
	}
	
	public static function getImage($modified, $etag, $picture){
		//Not using default function to have posibility to set Cache control...
		//Yii::app()->request->sendFile('image.png', $picture, 'image/png');
		
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
			//remove information after the semicolon and form a timestamp                                                         
			$request_modified = explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
			$request_modified = strtotime($request_modified[0]);
			
			// Compare the mtime on the request to the mtime of the image file                                                      
			if ($modified <= $request_modified) {
				header('HTTP/1.1 304 Not Modified');
				exit();
			}
		}
		
		if ($etag == '' && $picture != ''){
			$etag = md5($picture);
		}
		
		if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
			$request_etag = $_SERVER['HTTP_IF_NONE_MATCH'];  //If-None-Match: “877f3628b738c76a54?
			if ($etag == $request_etag){
				header('HTTP/1.1 304 Not Modified');
				exit();
			}
		}
		
		//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
        //header('Expires: ' . gmdate('D, d M Y H:i:s', (time() + 604800)) . ' GMT');  //604800 = 7 days in seconds
		header('Expires: ' . gmdate('D, d M Y H:i:s', (time() + 86400)) . ' GMT');  //86400 = 1 days in seconds
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
		header('Cache-Control: public');
		header('Etag: ' . $etag);
		header("Content-type: image/png");
		if(ini_get("output_handler")=='')
			header('Content-Length: '.(function_exists('mb_strlen') ? mb_strlen($picture,'8bit') : strlen($picture)));
		//header("Content-Disposition: attachment; filename=\"image_" . $id . ".png\"");
		header('Content-Transfer-Encoding: binary');
		echo $picture;
	}
}
?>