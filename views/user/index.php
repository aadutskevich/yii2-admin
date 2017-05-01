<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
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
		'{pager}' . \common\widgets\pagesize\PageSize::widget()
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

		// Назначения
		[
			'header' => Yii::t('User', 'Assignments'),
			'class' => EditableColumn::className(),

		],


		// Действия
		[
			'class' => 'yii\grid\ActionColumn',
			'contentOptions' => ['class' => 'action-column'],
			'template' => '{assignment}{delete}',
			'buttons' => [
				'assignment' => function($url, $model, $key) {
					$options = [
						'title' => Yii::t('User', 'Assignments'),
						'data-toggle' => 'tooltip',
					];

					return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, $options);
				},
				'delete' => function($url, $model, $key) {
					return ConfirmAction::widget(['url' => $url]);
				},
			],
		],
	],
]);


ConfirmAction::widget(['type' => ConfirmAction::TYPE_JS]);
