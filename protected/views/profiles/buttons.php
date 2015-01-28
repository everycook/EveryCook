<?php
if ($professional){
	echo CHtml::link($this->trans->PROFILES_PUBLIC_PROFILE, array('profiles/view', 'id'=>$model->PRF_UID), array('class'=>'button', 'id'=>'publicProfile'));
}
echo CHtml::link($this->trans->PROFILES_FAVOURITES, array('profiles/favorites'), array('class'=>'button', 'id'=>'favorites'));
echo CHtml::link($this->trans->PROFILES_SETTINGS, array('profiles/update'), array('class'=>'button', 'id'=>'settings'));
?>