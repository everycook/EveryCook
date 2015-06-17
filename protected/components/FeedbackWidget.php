<?php
Yii::import('zii.widgets.CPortlet');
 
class FeedbackWidget extends Widget
{
/*
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
*/
    /**
     * Initializes the widget.
     * This method is called by {@link CBaseController::createWidget}
     * and {@link CBaseController::beginWidget} after the widget's
     * properties have been initialized.
     */
    public function init()
    {
    	ob_start();
    	ob_implicit_flush(false);
    }
    
    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run()
    {
    	$content=ob_get_contents();
    	ob_end_clean();
    	$content = trim($content);
    	
    	$model = new Feedbacks();
    	$model->FEE_TEXT = $content; 
    	$this->render('_form_simple', array('model'=>$model));
    }
}

?>