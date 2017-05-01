<?php

use yii\helpers\Html;
use yii\grid\GridView;
use aadutskevich\admin\components\RouteRule;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel aadutskevich\admin\models\search\AuthItem */
/* @var $context aadutskevich\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Yii::$app->getAuthManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);

?>
<div class="role-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
		<?= Html::a(Yii::t('rbac-admin', 'Create ' . $labels['Item']), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'name',
				'label' => Yii::t('rbac-admin', 'ID'),
			],
			[
				'attribute' => 'name_t',
				'label' => Yii::t('rbac-admin', 'Name'),
				'visible' => $searchModel->type == 1,
			],
			[
				'attribute' => 'ruleName',
				'label' => Yii::t('rbac-admin', 'Rule Name'),
				'filter' => $rules,
			],
			[
				'attribute' => 'description',
				'label' => Yii::t('rbac-admin', 'Description'),
			],
			['class' => 'yii\grid\ActionColumn',],
		],
	])
	?>

</div>
