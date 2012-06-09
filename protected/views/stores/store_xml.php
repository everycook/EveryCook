<?php header("Content-Type:text/xml"); ?>
<?php echo "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<data>
	<status>success</status>
	<storeCount><?php echo count($stores); ?></storeCount>
	<stores>
<?php foreach($stores as $store){
?>		<store>
			<lat><?php echo $store['STO_GPS_LAT']; ?></lat>
			<lng><?php echo $store['STO_GPS_LNG']; ?></lng>
			<storeId><?php echo $store['STO_ID']; ?></storeId>
			<supplierId><?php echo $store['SUP_ID']; ?></supplierId>
			<distance><?php echo $store['distance']; ?></distance>
<?php if ($zoom > 9){ ?>
			<name><?php echo $store['STO_NAME']; ?></name>
			<street><?php echo $store['STO_STREET']; ?></street>
			<housenumber><?php echo $store['STO_HOUSE_NO']; ?></housenumber>
			<zip><?php echo $store['STO_ZIP']; ?></zip>
			<city><?php echo $store['STO_CITY']; ?></city>
			<supplier><?php echo $store['SUP_NAME']; ?></supplier>
			<type><?php echo $store['STY_TYPE']; ?></type>
			<typeId><?php echo $store['STY_ID']; ?></typeId>
			<imageUrl><?php /*echo $this->createUrl('stores/displaySavedImage', array('id'=>$store['STO_ID'], 'ext'=>'.png'));*/ ?></imageUrl><?php } ?>			
		</store>
<?php } ?>	</stores>
</data>