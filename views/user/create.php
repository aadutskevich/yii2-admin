<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MediaType */


$this->title = Yii::t('menu', '/admin/user/create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', '/admin/user/index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

// fields block
echo $form->field($model, 'name')->textInput(['maxlength' => TRUE, 'autocomplete' => 'off']);

echo $form->field($model, 'login')
	->textInput(['maxlength' => TRUE, 'autocomplete' => 'off'])
	->hint(Yii::t('User', 'Login hint'), ['class' => ['help-block help-block-hide-on-error']]);

echo $form->field($model, 'new_password')
	->widget(\kartik\password\PasswordInput::className(), ['options' => ['maxlength' => TRUE, 'autocomplete' => 'off', 'class' => 'form-control']])
	->hint(Yii::t('User', 'Password hint'));

// buttons block
echo Html::beginTag('div', ['class' => 'form-group button-group']);
echo Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-success']);
echo Html::a(Yii::t('common', 'Cancel'), Url::to(['index']), ['class' => 'btn btn-primary']);
echo Html::endTag('div');

ActiveForm::end();
