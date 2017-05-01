<?php

use yii\helpers\Html;
use yii\helpers\Json;
use aadutskevich\admin\AnimateAsset;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model aadutskevich\admin\models\Assignment */

$this->title = Yii::t('User', 'Assignments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menu', '/admin/user/index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// переводы для javascript
\lajax\translatemanager\helpers\Language::registerAssets();

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode(['items' => $model->getItems()]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';

?>


<div class="row">
    <div class="col-sm-5">
        <input class="form-control search" data-target="available"
               placeholder="<?= Yii::t('User', 'Search for available') ?>">
        <select multiple size="20" class="form-control list" data-target="available">
        </select>
    </div>
    <div class="col-sm-1">
        <br><br>
		<?= Html::a('<i class="glyphicon glyphicon-arrow-right"></i>' . $animateIcon, ['assign', 'id' => (string)$model->id], [
			'class' => 'btn btn-success btn-assign',
			'data-target' => 'available',
			'data-toggle' => 'tooltip',
			'title' => Yii::t('User', 'Assign'),
		]) ?><br><br>
		<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>' . $animateIcon, ['revoke', 'id' => (string)$model->id], [
			'class' => 'btn btn-danger btn-assign',
			'data-target' => 'assigned',
			'data-toggle' => 'tooltip',
			'title' => Yii::t('User', 'Remove assignment'),
		]) ?>
    </div>
    <div class="col-sm-5">
        <input class="form-control search" data-target="assigned"
               placeholder="<?= Yii::t('User', 'Search for assigned') ?>">
        <select multiple size="20" class="form-control list" data-target="assigned">
        </select>
    </div>
</div>
