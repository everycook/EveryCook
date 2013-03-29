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
	<?php echo CHtml::link($this->trans->GENERAL_SELECT, $data['NUT_ID'], array('class'=>'f-right button NutrientDataSelect')); ?>
	
	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_DESCRIPTION); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data['NUT_DESC']), array('view', 'id'=>$data['NUT_ID']), array('class'=>'name')); ?>
	<br />

	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_WATER); ?>:</b>
	<?php echo CHtml::encode($data['NUT_WATER']); ?>
	<br />

	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_ENERGY); ?>:</b>
	<?php echo CHtml::encode($data['NUT_ENERG']); ?>
	<br />

	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_PROTEIN); ?>:</b>
	<?php echo CHtml::encode($data['NUT_PROT']); ?>
	<br />

	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_SUGAR); ?>:</b>
	<?php echo CHtml::encode($data['NUT_SUGAR']); ?>
	<br />

	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_CALCIUM); ?>:</b>
	<?php echo CHtml::encode($data['NUT_CALC']); ?>
	<br />

	<b><?php echo CHtml::encode($this->trans->NUTRIENT_DATA_CHOLESTERIN); ?>:</b>
	<?php echo CHtml::encode($data['NUT_CHOLEST']); ?>
	<br />
	<div class="clearfix"></div>
	
	<?php /*
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_LIPID')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_LIPID']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_ASH')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_ASH']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_CARB')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_CARB']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FIBER')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FIBER']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_CALC')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_CALC']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_IRON')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_IRON']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_MAGN')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_MAGN']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_PHOS')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_PHOS']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_POTAS')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_POTAS']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_SODIUM')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_SODIUM']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_ZINC')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_ZINC']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_COPP')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_COPP']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_MANG')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_MANG']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_SELEN')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_SELEN']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_C')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_C']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_THIAM')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_THIAM']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_RIBOF')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_RIBOF']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_NIAC')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_NIAC']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_PANTO')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_PANTO']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_B6')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_B6']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FOLAT_TOT')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FOLAT_TOT']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FOLIC')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FOLIC']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FOLATE_FD')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FOLATE_FD']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FOLATE_DFE')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FOLATE_DFE']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_CHOLINE')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_CHOLINE']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_B12')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_B12']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_A_IU')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_A_IU']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_A_RAE')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_A_RAE']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_RETINOL')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_RETINOL']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_ALPHA_CAROT')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_ALPHA_CAROT']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_BETA_CAROT')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_BETA_CAROT']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_BETA_CRYPT')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_BETA_CRYPT']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_LYCOP')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_LYCOP']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_LUT_ZEA')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_LUT_ZEA']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_E')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_E']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_D')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_D']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_D_IU')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_D_IU']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_VIT_K')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_VIT_K']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FA_SAT')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FA_SAT']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FA_MONO')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FA_MONO']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_FA_POLY')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_FA_POLY']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_CHOLEST')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_CHOLEST']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_REFUSE')); ?>:</b>
	<?php echo CHtml::encode($data['NUT_REFUSE']); ?>
	<br />

	*/ ?>

</div>