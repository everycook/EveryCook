<?php
function preparedStatementToStatement($command, $params){
	$sql = $command->getText();
	foreach($params as $key => $value){
		$sql = str_replace($key, $value, $sql);
	}
	return Yii::app()->db->createCommand($sql);
}

function searchCriteriaInput($label, $model, $fieldName, $dataList, $type, $id, $htmlOptions) {
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
function createInput($label, $model, $fieldName, $dataList, $type, $id, $htmlOptions, $form) {
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

function resizePicture($file, $file_new, $width, $height, $qualitaet, $destType)
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
?>