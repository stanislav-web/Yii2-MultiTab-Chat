<?php

use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
?>
<div class="row ">
    <br/>
    <div class="col-md-8 main-chat">
        <div class="panel panel-info">
            <div class="ttop">
                <span class="ttitle">Live Chat</span>
            </div>
            <div class="panel-body">
                <ul class="media-list" id="media-list">
                </ul>
            </div>
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => true,
                'enableAjaxValidation' => true,
                'action' => Url::to(['chat/save']),
                'validationUrl' => Url::to(['chat/validate']),
                'options' => ['class' => 'panel-footer'],
                'errorCssClass' => 'alert alert-danger',
                'fieldConfig' => [
                    'errorOptions' => ['encode' => false, 'class' => 'help-block'],
                    'template' => '{input}{error}',
                ]
            ]); ?>
            <div class="form-group">
                <?= $form->field($userModel, 'username')->textInput(['placeholder' => 'Type your nickname']); ?>
            </div>
            <div class="input-group">
                <?= $form->field($messageModel, 'message')
                    ->textarea([
                            'rows' => '5',
                            'cols' => 100,
                            'placeholder' => 'Type message'
                        ]
                    );
                ?>
            </div>
            <div class="panel-footer">
                <div class="input-group">
                            <span class="input-group-btn">
                                 <?= Html::submitButton('SEND', ['class' => 'btn btn-info']); ?>
                            </span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>

    var tid = setInterval( function () {
        if ( document.readyState !== 'complete' ) return;
        clearInterval( tid );

        var username = Storage.get('username');
        if(username) {
            var input = document.getElementById('user-username');
                input.type = 'hidden';
                input.value = username;
        }

        return (function() {
            var interval = 0;
            (function worker() {
                Message.load('<?=Url::to(['chat/list']);?>');
                interval = 2000;
                Message.disableTime = interval;
                setTimeout(worker, Message.disableTime);
            })();
        })();
    }, 100 );
</script>