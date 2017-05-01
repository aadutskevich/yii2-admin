<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\password\PasswordInput;

/* @var $this yii\web\View */
/* @var $model \common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('menu', '/admin/user/change-password');
$this->params['breadcrumbs'][] = $this->title;

foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
	echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}

$form = ActiveForm::begin([
	'id' => $model->formName(),
]);


echo $form->field($model, 'current_password')->passwordInput();

echo $form->field($model, 'new_password')
	->widget(PasswordInput::className(), ['options' => ['class' => 'form-control']])
	->hint(Yii::t('User', 'Password hint'));

echo $form->field($model, 'password_repeat')->passwordInput(['class' => 'form-control']);


// buttons block
echo Html::beginTag('div', ['class' => 'form-group button-group']);
echo Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-success']);
echo Html::a(Yii::t('common', 'Cancel'), \yii\helpers\Url::to(['index']), ['class' => 'btn btn-primary']);
echo Html::endTag('div');

ActiveForm::end();

