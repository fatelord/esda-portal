<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UploadsModel */
/* @var $form yii\widgets\ActiveForm */
/* @var $user_id */

$user_id = Yii::$app->user->id;
?>

<div class="user-uploads-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'USER_ID')->hiddenInput(['value' => $user_id, 'maxlength' => true, 'readonly' => true])->label(false) ?>
    <?= $form->field($model, 'FILE_NAME')->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
    <?= $form->field($model, 'FILE_PATH')->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>

    <?= $form->field($model, 'FILE_SELECTOR')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            //'accept' => 'image/*',
            'multiple' => false
        ],
        'pluginOptions' => [
            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'rtf', 'odt'],
            'maxFileCount' => 10,
            'uploadAsync' => true,
            'showPreview' => false,
            'showUpload' => true,
            'overwriteInitial' => false,
            'maxFileSize' => 10000,
            'uploadExtraData' => [
                'USER_ID' => $user_id,
                '_csrf' => Yii::$app->request->csrfToken
            ],
            'uploadUrl' => \yii\helpers\Url::to(['//users/uploads/file-upload']),
        ],
        'pluginEvents' => [
            'fileuploaded' => "function(event, data, previewId, index){
                $.each(data.response, function( index, resp ) {
                    $('#uploadsmodel-file_path').val(resp.path);
                    $('#uploadsmodel-file_name').val(resp.file_name);
                    //after uploading enable the submit button
                    $(':input[type=\"submit\"]').prop('disabled', false);
                });

            }"
        ]
    ]); ?>
    <?= $form->field($model, 'COMMENTS')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'PUBLICLY_AVAILABLE')->dropDownList([
        \app\components\Constants::FILE_IS_NOT_PUBLIC => 'Private',
        \app\components\Constants::FILE_IS_PUBLIC => 'Public',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save Details') : Yii::t('app', 'Update Details'), [
            'class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block',
            'disabled' => $model->isNewRecord ? true : false
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
