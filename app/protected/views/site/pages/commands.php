<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Email Commands';
$this->breadcrumbs=array(
	'Email Commands',
);
?>
<h1>Available Email Commands</h1>
What these are for: How to use them.

<?php

$gridDataProvider = new CArrayDataProvider(array(
    array('id'=>'!private', 'desc'=>'Sends your reply privately to the message sender (instead of to the whole list)'),
    array('id'=>'!abuse', 'desc'=>'Mark this message as abusive and alert the moderator.'),
    array('id'=>'!spam', 'desc'=>'Mark this message as spam and alert the moderator.'),
    array('id'=>'!favorite', 'desc'=>'Save this message to your favorites.'),
    array('id'=>'!unfollow', 'desc'=>'Stop sending replies to this message thread. You will continue receiving new messages from this place.'),
    array('id'=>'!leave', 'desc'=>'Leave this place.'),
    array('id'=>'!mute', 'desc'=>'Stop sending messages to you from this sender.'),
    array('id'=>'!nomail', 'desc'=>'Turn off all geogram email. You will have to visit the website to turn email back on.'),
));

 $this->widget('bootstrap.widgets.TbGridView', array(
   'dataProvider'=>$gridDataProvider,
    'template'=>"{items}",
    'type'=>'striped bordered condensed',
    'columns'=>array(
        array('name'=>'id', 'header'=>'Command','htmlOptions'=>array('style'=>'width: 50px')),
        array('name'=>'desc', 'header'=>'Description'),
    ),
)); ?>
