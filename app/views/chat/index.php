<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
?>
<div class="row ">
        <h3 class="text-center" ><?= Html::encode($title) ?> </h3>
        <br /><br />
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <?= $subtitle;?>
                </div>
                <div class="panel-body">
                    <ul class="media-list">
                        <li class="media">
                            <div class="media-body">
                                <div class="media">
                                    <a class="pull-left" href="#">
                                        <img class="media-object img-circle " src="<?=BaseUrl::base(true);?>/images/user.png" />
                                    </a>
                                    <div class="media-body" >
                                        Donec sit amet ligula enim. Duis vel condimentum massa.

                                        Donec sit amet ligula enim. Duis vel condimentum massa.Donec sit amet ligula enim.
                                        Duis vel condimentum massa.
                                        Donec sit amet ligula enim. Duis vel condimentum massa.
                                        <br />
                                        <small class="text-muted">Alex Deo | 23rd June at 5:00pm</small>
                                        <hr />
                                    </div>
                                </div>

                            </div>
                        </li>
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
                    <?= $form->field($userModel, 'username')->hint('Type your nickname'); ?>
                </div>
                    <div class="input-group">
                        <?=$form->field($messageModel, 'message')
                            ->textarea([
                                'rows' => '5',
                                'cols' => 100,
                                'placeholder'=>'Type message']
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
