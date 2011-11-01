<?php
    include 'lang.php';
    $language=$_GET['language'];
    if (strlen($language)==2) {
        $view = $_GET['view'];
        $langm = langmenu($language);
        $langv = langview($language,$view);
        foreach($langv as $z) {
            $i=0;
            array_push($langm,$z);
            $i++;
        }
        echo json_encode($langm);
    }
?>
