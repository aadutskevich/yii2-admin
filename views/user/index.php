<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\User;
use kartik\grid\EditableColumn;
use common\widgets\ConfirmAction;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MediaTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('menu', '/admin/user/index');
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
	'id' => 'user-grid',
	'panel' => [
		'type' => GridView::TYPE_PRIMARY,
		'heading' => $this->title,
	],

	'panelBeforeTemplate' =>
		Html::a(Yii::t('common', 'Create'), ['create'], ['class' => 'btn btn-success pull-left']) .
		'{pager}' . \common\widgets\PageSize::widget()
	,

	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'kartik\grid\SerialColumn'],

		// Активность
		[
			'attribute' => 'is_active',
			'filter' => [1 => Yii::t('common', 'Yes'), 0 => Yii::t('common', 'No')],
			'filterInputOptions' => [
				'prompt' => Yii::t('common', 'All'),
				'class' => 'form-control',
			],
			'format' => 'raw',
			'value' => function($model, $key, $index) {
				return \common\widgets\AjaxUpdateSwitchInput::widget([
					'name' => 'editable-' . $key . '-' . $index,
					'value' => $model->is_active,
					'options' => [
						'data-attribute' => 'is_active',
						'data-key' => $key,
						'data-url' => \yii\helpers\Url::to(['ajax-update']),
					],
				]);
			},
		],

		// Логин
		[
			'attribute' => 'login',
			'class' => EditableColumn::className(),

		],

		// ФИО
		[
			'attribute' => 'name',
			'class' => EditableColumn::className(),

		],

		// Роли
		[
			'header' => Yii::t('User', 'Roles'),
			'value' => function(User $model) {
				return implode(', ', \yii\helpers\ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->id), 'name_t'));
			},
		],

		// Пароль
		[
			'attribute' => 'new_password',
			'label' => '',
			'contentOptions' => ['class' => 'action-column'],
			'class' => EditableColumn::className(),
			'format' => 'raw',
			'editableOptions' => function(User $model, $key, $index) {
				$icon = Html::tag('span', '', [
					'title' => Yii::t('User', 'Set new password'),
					'data-toggle' => 'tooltip',
					'class' => 'glyphicon glyphicon-lock',
				]);

				return [
					'size' => \kartik\popover\PopoverX::SIZE_LARGE,
					'encodeOutput' => FALSE,
					'displayValue' => $model->getPasswordIcon(),
					'beforeInput' => Html::hiddenInput('displayAttribute', 'passwordIcon'),
					'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
					'widgetClass' => \common\widgets\PasswordInput::className(),
					'options' => [
						'options' => [
							'autocomplete' => 'off',
						],
					],
					'pluginEvents' => [ // stopPropagation, чтобы dirtyFields не сохранял введённое значение
						"editableSuccess" => "function(event, val, form, data) { 
							event.stopPropagation();
						}",
					],
					'header' => Yii::t('User', 'Set new password'),
					'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
				];
			},
		],

		// Назначения
		[
			'class' => 'yii\grid\ActionColumn',
			'contentOptions' => ['class' => 'action-column'],
			'template' => '{assignment}',
			'buttons' => [
				'assignment' => function($url, $model, $key) {
					$options = [
						'title' => Yii::t('User', 'Assignments'),
						'data-toggle' => 'tooltip',
					];

					return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, $options);
				},
			],
		],

		// Удаление
		[
			'class' => 'yii\grid\ActionColumn',
			'contentOptions' => ['class' => 'action-column'],
			'template' => '{delete}',
			'buttons' => [
				'delete' => function($url, $model, $key) {
					return ConfirmAction::widget(['url' => $url]);
				},
			],
		],
	],
]);


ConfirmAction::widget(['type' => ConfirmAction::TYPE_JS]);
