<?php

use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
?>
<div class="row ">
    <h3 class="text-center"><?= Html::encode($title) ?> </h3>
    <br/><br/>
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <?= $subtitle; ?>
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
            <?php if (false === $isAuth): ?>
                <div class="form-group">
                    <?= $form->field($userModel, 'username')->textInput(['placeholder' => 'Type your nickname']); ?>
                </div>
            <?php endif; ?>
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
        Message.currentIp = '<?=$ip;?>';

        return (function() {
            var interval = 0;
            (function worker() {
                Message.load('<?=Url::to(['chat/list']);?>');
                interval = 3000;
                Message.disableTime = interval;
                setTimeout(worker, Message.disableTime);
            })();
        })();
    }, 100 );
</script>