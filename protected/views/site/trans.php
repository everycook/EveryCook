var glob = glob || {};
glob.trans = {
<?php
header('Content-Type: application/javascript; charset:UTF-8');
$textKeys = array(
'MEALPLANNER_ADD_RECIPE',
'MEALPLANNER_EATING_PEOPLE_ADULT',
'MEALPLANNER_REMOVE_RECIPE',
'MEALPLANNER_COURSE_GDA',
'gibtsnicht',
);
foreach($textKeys as $key){
	echo '"' . $key . '":"' . $this->trans->__get($key) . '",';
}
?>
};