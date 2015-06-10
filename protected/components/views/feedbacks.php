<ul>
<?php 
$comments = $this->getFeedbacks();
 
foreach($comments as $comment)
{
    echo "<li> {$comment->fieldName}</li>";
}
?>
</ul>