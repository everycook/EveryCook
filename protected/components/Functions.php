<?php
class Functions extends CHtml{
	
	const DROP_DOWN_LIST = 0;
	const CHECK_BOX_LIST = 1;
	
	/*
	public static function preparedStatementToStatement($command, $params){
		$sql = $command->getText();
		foreach($params as $key => $value){
			$sql = str_replace($key, $value, $sql);
		}
		return Yii::app()->db->createCommand($sql);
	}
	*/

	public static function searchCriteriaInput($label, $model, $fieldName, $dataList, $type, $id, $htmlOptions) {
		$html = '<div class="row" id="'.$id.'">';
		$html .= self::activeLabel($model,$fieldName, array('label'=>$label));
		$html .= ' ';
		if ($type == 0){
			$html .= self::dropDownList(self::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
		} else if ($type == 1){
			$html .= '<ul class="search_choose">';
			$html .= self::checkBoxList(self::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
			$html .= '</ul>';
			$html .= '<div class="clearfix"></div>';
		}
		$html .= '</div>';
		
		return $html;
	}
	
	public static function createInput($label, $model, $fieldName, $dataList, $type, $id, $htmlOptions, $form) {
		$html = '<div class="row" id="'.$id.'">';
		$html .= self::activeLabelEx($model, $fieldName, array('label'=>$label));
		$html .= ' ';
		if ($type == 0){
			$html .= self::dropDownList(self::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
		} else if ($type == 1){
			$html .= '<ul class="search_choose">';
			$html .= self::checkBoxList(self::resolveName($model,$fieldName), $model->__get($fieldName), $dataList, $htmlOptions); 
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
	Logic from self::resolveName
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
	
	public static function createInputTable($valueArray, $fieldOptions, $options, $form, $texts) {
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
		$html .='<th>'.$texts['options'].'</th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		$i = 1;
		foreach($valueArray as $value){
			$html .= '<tr class="'.(($i % 2 == 1)?'odd':'even').'">';
			foreach($fieldOptions as $field){
				if($field[1]){
					$options = $field[3];
					if ($options['fancy']){
						$text = $options['empty'];
						$val = $value->__get($field[0]);
						if ($val != '' && is_array($field[2])){
							foreach($field[2] as $row_key=>$row_val){
								if($row_key == $val){
									$text = $row_val;
									break;
								}
							}
						}
						$htmlOptions = array_merge(array('id'=>self::getIdByName(self::resolveArrayName($value,$field[0].'_DESC','%index%'))),$options['htmlOptions']);
						$html .= '<td>' . self::hiddenField(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0])) . self::link($text, $options['url'], $htmlOptions) . '</td>';
					} else if (is_array($field[2])){
						$html .= '<td>'.self::dropDownList(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0]), $field[2], $field[3]).'</td>';
					} else {
						$html .= '<td>'.self::textField(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0])).'</td>';
					}
				} else {
					$html .= self::hiddenField(self::resolveArrayName($value,$field[0],$i), $value->__get($field[0]));
				}
			}
			$html .= '<td><div class="buttonSmall remove">' . $texts['remove'] . '</div><div class="buttonSmall up">' . $texts['move up'] . '</div><div class="buttonSmall down">' . $texts['move down'] . '</div></td>';
			$html .= '</tr>';
			$i++;
		}
		
		if ($new){
			$newhtml = '<tr class="%class%">';
			foreach($fieldOptions as $field){
				if($field[1]){
					$options = $field[3];
					if (isset($options['fancy']) && $options['fancy']){
						$text = $options['empty'];
						$htmlOptions = array_merge(array('id'=>self::getIdByName(self::resolveArrayName($new,$field[0].'_DESC','%index%'))),$options['htmlOptions']);
						$newhtml .= '<td>' . self::hiddenField(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0]), array('class'=>'fancyValue')) . self::link($text, $options['url'], $htmlOptions) . '</td>';
					} else if (is_array($field[2])){
						$newhtml .= '<td>'.self::dropDownList(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0]), $field[2], $field[3]).'</td>';
					} else {
						$newhtml .= '<td>'.self::textField(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0])).'</td>';
					}
				} else {
					$newhtml .= self::hiddenField(self::resolveArrayName($new,$field[0],'%index%'), $new->__get($field[0]));
				}
			}
			$newhtml .= '<td><div class="buttonSmall remove">' . $texts['remove'] . '</div><div class="buttonSmall up">' . $texts['move up'] . '</div><div class="buttonSmall down">' . $texts['move down'] . '</div></td>';
			$newhtml .= '</tr>';
			
			$html .= '<tr id="newLine">';
			$html .= '<td colspan="'.$visibleFields.'"><div class="buttonSmall add">' . $texts['add'] . '</div>'. self::hiddenField('addContent', $newhtml).self::hiddenField('lastIndex', $i).'</td>';
			$html .= '</tr>';
		}
		
		$html .= '</tbody></table>';
		
		return $html;
	}
	
	public static function resizePicture($file, $file_new, $width, $height, $qualitaet, $destType){
		self::resizePicturePart($file, $file_new, $width, $height, $qualitaet, $destType, 0, 0 ,-1 ,-1);
	}
	
	public static function resizePicturePart($file, $file_new, $width, $height, $qualitaet, $destType, $src_x, $src_y, $src_w, $src_h)
	{
		if(!file_exists($file))
			return false;
		$info = getimagesize($file);
		
		if($info[2] == IMAGETYPE_GIF){
			$image = imagecreatefromgif($file);
		} elseif($info[2] == IMAGETYPE_JPEG) {
			$image = imagecreatefromjpeg($file);
		} elseif($info[2] == IMAGETYPE_PNG) {
			$image = imagecreatefrompng($file);
		} else  {
				return false;
		}
		if ($destType == -1){
			$destType = $info[2];
		}
		
	/*    echo $info[0]. " ".$info[1]; //Breite * H�he
		if ($info[0] < $info[1]){
			$temp=$height;
			$width=$height;
			$height=$width;
		}*/
		if ($width > $src_w && $src_w != -1){
			$width = $src_w;
		}
		if ($height > $src_h && $src_h != -1){
			$height = $src_h;
		}
		
		if ($src_w==-1){$src_w=$info[0];}
		if ($src_h==-1){$src_h=$info[1];}
		if ($width && ($src_w < $src_h)){
			$width = ($height / $src_h) * $src_w;
		} else { 
			$height = ($width / $src_w) * $src_h; 
		}
		$imagetc = imagecreatetruecolor($width, $height);
		if (($info[0] > $width) or ($info[1] > $height) or ($width != $src_w) or ($height != $src_h) or ($src_x != 0) or ($src_y != 0)){
			imagecopyresampled($imagetc, $image, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);
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
		
		if($destType == IMAGETYPE_GIF){
			imagegif($imagetc, $file_new);  
			//imagejpeg($imagetc, $file_new, $qualitaet);  
		} elseif($destType == IMAGETYPE_JPEG) {
			imagejpeg($imagetc, $file_new, $qualitaet);  
		} elseif($destType == IMAGETYPE_PNG) {
			imagepng($imagetc, $file_new);  
		} else  {
			imagejpeg($imagetc, $file_new, $qualitaet);  
		}
	}
	
	
	public static function changePictureType($file, $file_new, $destType)
	{
		if(!file_exists($file))
			return false;
		
		$info = getimagesize($file);

		if ($destType == -1 || $destType == $info[2]){
			return true;
		}
		
		if($info[2] == IMAGETYPE_GIF){
			$image = imagecreatefromgif($file);
		} elseif($info[2] == IMAGETYPE_JPEG) {
			$image = imagecreatefromjpeg($file);
		} elseif($info[2] == IMAGETYPE_PNG) {
			$image = imagecreatefrompng($file);
		} else  {
			return false;
		}
		
		$width = $info[0];
		$height = $info[1];
		$qualitaet = 0.8;
		
		//$imagetc = imagecreatetruecolor($width, $height
		//imagecopyresampled($imagetc, $image, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);
		//$transparent=imagecolortransparent($image);
		//imagecolortransparent($imagetc,$transparent);
		
		$imagetc = $image;
		
		if($destType == IMAGETYPE_GIF){
			imagegif($imagetc, $file_new); 
		} elseif($destType == IMAGETYPE_JPEG) {
			imagejpeg($imagetc, $file_new, $qualitaet);
		} elseif($destType == IMAGETYPE_PNG) {
			imagepng($imagetc, $file_new);
		} else  {
			imagejpeg($imagetc, $file_new, $qualitaet);
		}
	}
	
	public static function getImage($modified, $etag, $picture, $id){
		//Not using default function to have posibility to set Cache control...
		//Yii::app()->request->sendFile('image.png', $picture, 'image/png');
		if ($id != 'backup'){
			if ($modified){
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
			}
			
			if ($etag == '' && $picture != ''){
				$etag = md5($picture);
			}
			
			if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
				$request_etag = $_SERVER['HTTP_IF_NONE_MATCH'];  //If-None-Match: �877f3628b738c76a54?
				if ($etag == $request_etag){
					header('HTTP/1.1 304 Not Modified');
					exit();
				}
			}
			
			//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			//header('Expires: ' . gmdate('D, d M Y H:i:s', (time() + 604800)) . ' GMT');  //604800 = 7 days in seconds
			header('Expires: ' . gmdate('D, d M Y H:i:s', (time() + 86400)) . ' GMT');  //86400 = 1 days in seconds
			if ($modified){
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
			}
			header('Cache-Control: public');
			header('Etag: ' . $etag);
		}
		header("Content-type: image/png");
		if(ini_get("output_handler")=='')
			header('Content-Length: '.(function_exists('mb_strlen') ? mb_strlen($picture,'8bit') : strlen($picture)));
		//header("Content-Disposition: attachment; filename=\"image_" . $id . ".png\"");
		header('Content-Transfer-Encoding: binary');
		echo $picture;
	}
	
	public static function updatePicture($model, $picFieldName, $oldPicture){
		$file = CUploadedFile::getInstance($model,'filename');
		if ($file){
			self::resizePicture($file->getTempName(), $file->getTempName(), 400, 400, 0.8, IMAGETYPE_PNG);
			$model->__set($picFieldName, file_get_contents($file->getTempName()));
			$model->__set($picFieldName . '_ETAG', md5($model->__get($picFieldName)));
			$model->setScenario('withPic');
		} else {
			if ($model->__get($picFieldName) == '' && $oldPicture != ''){
				$model->__set($picFieldName, $oldPicture);
				$model->__set($picFieldName . '_ETAG', md5($model->__get($picFieldName)));
				$model->setScenario('withPic');
			} else {
				$cropInfosAvailable = isset($_POST['imagecrop_w']) && ($_POST['imagecrop_w'] > 0) && isset($_POST['imagecrop_h']) && ($_POST['imagecrop_h'] > 0);
				if (($model->imagechanged == true || $cropInfosAvailable) && $model->__get($picFieldName) != ''){
					$tempfile = tempnam(sys_get_temp_dir(), 'img');
					file_put_contents($tempfile,$model->__get($picFieldName));
					if ($cropInfosAvailable){
						self::resizePicturePart($tempfile, $tempfile, 400, 400, 0.8, IMAGETYPE_PNG, $_POST['imagecrop_x'], $_POST['imagecrop_y'], $_POST['imagecrop_w'], $_POST['imagecrop_h']);
					} else {
						self::resizePicture($tempfile, $tempfile, 400, 400, 0.8, IMAGETYPE_PNG);
					}
					$model->__set($picFieldName, file_get_contents($tempfile));
					$model->__set($picFieldName . '_ETAG', md5($model->__get($picFieldName)));
					$model->imagechanged = false;
				}
			}
		}
	}
	
	private static function uploadPicture($model, $picFieldName){
		$file = CUploadedFile::getInstance($model,'filename');
		if ($file){
			if (self::changePictureType($file->getTempName(),$file->getTempName(), IMAGETYPE_PNG)){
				$model->__set($picFieldName, file_get_contents($file->getTempName()));
				$model->__set($picFieldName . '_ETAG', md5($model->__get($picFieldName)));
				$model->imagechanged = true;
				$model->setScenario('withPic');
				return true;
			} else {
				return -2;
			}
		} else {
			return -1;
		}
	}
	
	public static function uploadImage($modelName, $model, $sessionBackupName, $pictureFieldName){
		if(isset($_POST[$modelName])){
			$model->attributes=$_POST[$modelName];
			$sucessfull = Functions::uploadPicture($model, $pictureFieldName);
			Yii::app()->session[$sessionBackupName] = $model;
			
			if ($sucessfull === true){
				echo '{imageId:"backup"}';
				exit;
			} else if ($sucessfull == -1){
				//TODO: Yii::app()->controller->trans->
				echo '{error:"Uploaded File not accessible."}';
				exit;
			} else if ($sucessfull == -2){
				echo '{error:"Unknown Filetype, you can only use GIF, JPG and PNG."}';
				exit;
			}
		} else {
			echo '{error:"invalide Request, no file information submitted"}';
			exit;
		}
	}
	
	/**
	 * Generates a special (HTML5 types) field input for a model attribute.
	 * If the attribute has input error, the input field's CSS class will
	 * be appended with {@link errorCss}.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
	 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
	 * @return string the generated input field
	 * @see clientChange
	 * @see activeInputField
	 */
	public static function activeSpecialField($model,$attribute,$type,$htmlOptions=array()){
		self::resolveNameID($model,$attribute,$htmlOptions);
		if ($type != 'hidden'){
			self::clientChange('change',$htmlOptions);
		}
		if(isset($htmlOptions['class'])){
			$htmlOptions['class'] = $htmlOptions['class'] . ' input_' . $type;
		} else {
			$htmlOptions['class'] = 'input_' . $type;
		}
		return self::activeInputField($type,$model,$attribute,$htmlOptions);
	}
	
	
	public static function addLikeInfo($id, $type, $like){
		$model=Profiles::model()->findByPk(Yii::app()->user->id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$fieldname = 'PRF_' . ((!$like)?'NOT':'') . 'LIKES_' . $type;
		$fieldnameRemove = 'PRF_' . (($like)?'NOT':'') . 'LIKES_' . $type;
		$value = $model->$fieldname;
		if ($value == ''){
			$model->$fieldname = $id;
		} else {
			$values = explode(',', $value);
			$values[] = $id;
			$values = array_unique($values);
			sort($values, SORT_NUMERIC);
			$value = implode(',', $values);
			$model->$fieldname = $value;
		}
		if (isset($model->$fieldnameRemove)){
			$value = $model->$fieldnameRemove;
			if ($value == '' || $value == $id){
				$model->$fieldnameRemove = '';
			} else {
				$values = explode(',', $value);
				for($i=0; $i<count($values); $i++){
					if ($values[$i] == $id){
						unset($values[$i]);
						break;
					}
				}
				$value = implode(',', $values);
				$model->$fieldnameRemove = $value;
			}
		}
		return $model->save();
	}
}
?>