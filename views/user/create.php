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

echo Html::tag('div', $form->field($model, 'fake_login')->textInput() . $form->field($model, 'fake_password')->passwordInput(), ['class' => 'hidden']);

// fields block
echo $form->field($model, 'name')->textInput(['maxlength' => TRUE, 'autocomplete' => 'off']);

echo $form->field($model, 'login')
	->textInput(['maxlength' => TRUE, 'autocomplete' => 'off'])
	->hint(Yii::t('User', 'Login must start from letter. Valid symbols are latin letters, digits, dot, dash and underscore. Length between 4 and 20.'), ['class' => ['help-block help-block-hide-on-error']]);

echo $form->field($model, 'password')
	->widget(\kartik\password\PasswordInput::className(), ['options' => ['class' => 'form-control']])
	->hint(Yii::t('User', 'Password must contain one upper case and one lower case letter. Min length is 8 symbols.'));

echo $form->field($model, 'password_repeat')->passwordInput();

// buttons block
echo Html::beginTag('div', ['class' => 'form-group button-group']);
echo Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-success']);
echo Html::a(Yii::t('common', 'Cancel'), Url::to(['index']), ['class' => 'btn btn-primary']);
echo Html::endTag('div');

ActiveForm::end();
