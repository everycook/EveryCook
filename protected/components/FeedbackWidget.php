<?php
Yii::import('zii.widgets.CPortlet');
 
class FeedbackWidget extends CPortlet
{
    public $title='Recent Comments';
    public $maxComments=10;
 
    public function getFeedbacks()
    {
        return Feedbacks::model()->findFeedbacks($this->maxComments);
    }
 
    protected function renderContent()
    {
        $this->render('feedbacks');
    }
}

?>