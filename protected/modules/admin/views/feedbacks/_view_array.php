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
?>
<div class="resultArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['FEE_ID'], array('class'=>'f-right button FeedbacksSelect'));
	/*
	} else {
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['FEE_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'FEE_ID'=>$data['FEE_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['FEE_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	*/
	}
	?>
	
	<div class="data">
		<?php
        //echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_LANG) .':</b>';
        echo CHtml::encode($data['FEE_LANG']);
        echo '<br />';
		if(isset($data['FEE_TITLE'])){
			//echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_TITLE) .'</b>';
			echo CHtml::encode($data['FEE_TITLE']);
			echo '<br />';
		}
		?>
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['FEE_TEXT']);
			} else {
				echo CHtml::link(CHtml::encode($data['FEE_TEXT']), array('view', 'id'=>$data['FEE_ID']));
			}
			?>
		</div>
		
		<?php
		if(isset($data['FEE_EMAIL'])){
			//echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_EMAIL) .':</b>';
			echo CHtml::encode($data['FEE_EMAIL']);
			echo '<br />';
		}
		/*
		echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_ID) .':</b>';
        echo CHtml::link(CHtml::encode($data['FEE_ID']), array('view', 'id'=>$data['FEE_ID']));
        echo '<br />';

        echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_LANG) .':</b>';
        echo CHtml::encode($data['FEE_LANG']);
        echo '<br />';

        echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_TITLE) .':</b>';
        echo CHtml::encode($data['FEE_TITLE']);
        echo '<br />';

        echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_TEXT) .':</b>';
        echo CHtml::encode($data['FEE_TEXT']);
        echo '<br />';

        echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_EMAIL) .':</b>';
        echo CHtml::encode($data['FEE_EMAIL']);
        echo '<br />';

        echo '<b>' . CHtml::encode($this->trans->FIELD_FEE_STATUS) .':</b>';
        echo CHtml::encode($data['FEE_STATUS']);
        echo '<br />';
        */
        ?>
	</div>
	<div class="clearfix"></div>
</div>