<div class="resultArea">
	
	<div class="f-right">
		<?php
		foreach($data['ingredients'] as $ingredient){
			echo '<div>' . $ingredient . '</div>';
		}
		?>
	</div>
	
	<div class="f-left">
	<h2><?php echo $this->trans->SHOPPINGLISTS_LIST_SAVED_AT . ' ' . date('d.m.Y', $data['SHO_DATE']); ?></h2>
	<?php
	echo '<div>';
	printf($this->trans->SHOPPINGLISTS_TOTAL_INGREDIENTS,$data['total_products']);
	echo '</div><div>';
	printf($this->trans->SHOPPINGLISTS_NOT_ASSIGNED,$data['not_assigned']);
	echo '</div>';
	foreach($data['products_from'] as $from){
		echo '<div>';
		printf($this->trans->SHOPPINGLISTS_PRODUCTS_FROM,$from['amount'],$from['supplier']);
		echo '</div>';
	}
	?>
	</div>
	
	<div class="f-center">
		<?php echo CHtml::link(CHtml::encode($this->trans->GENERAL_EDIT), array('view', 'id'=>$data['SHO_ID']), array('class'=>'button')); ?>
	</div>
</div>