<?php

namespace aadutskevich\admin\controllers;

use aadutskevich\admin\components\Helper;
use aadutskevich\admin\models\Assignment;
use aadutskevich\admin\models\UserSearch;
use common\models\User;
use common\traits\AjaxUpdate;
use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\bootstrap\ActiveForm;
use \yii\web\Response;


class UserController extends Controller
{
	use AjaxUpdate;


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // ajax - render the grid by renderAjax
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } // non-ajax - render the grid by default
        else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }


    }


    /**
     * Добавление сотрудника
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        // ajax валидация формы
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        // сохранение данных
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } // вывод данных
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }




    public function actionDelete($id)
    {
	    try {
		    $model = $this->findModel($id);

		    // проверяем, что для текущей организации это не последний пользователь с доступом к назначению ролей

		    $model->delete();

		    return '';
	    } catch (NotFoundHttpException $e) {
		    Yii::$app->response->setStatusCode(404);
		    return Yii::t('Exception', 'Record not found.');
	    } catch (yii\base\Exception $e) {
		    Yii::$app->response->setStatusCode(500);
		    return Yii::t('Exception', 'Record could not be deleted due to constraints.');
	    }
    }


	/**
	 * Displays a single Assignment model.
	 * @param  integer $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
	public function actionAssignment($id)
	{
		if (($user = User::findIdentity($id)) !== null) {
			$model = new Assignment($id, $user);
		} else {
			throw new NotFoundHttpException();
		}

		return $this->render('assignment', [
			'model' => $model,
		]);
	}

	/**
	 * Assign items
	 * @param string $id
	 * @return array
	 */
	public function actionAssign($id)
	{
		$items = Yii::$app->getRequest()->post('items', []);
		$model = new Assignment($id);
		$success = $model->assign($items);
		Yii::$app->getResponse()->format = 'json';
		return array_merge($model->getItems(), ['success' => $success]);
	}

	/**
	 * Assign items
	 * @param string $id
	 * @return array
	 */
	public function actionRevoke($id)
	{
		$items = Yii::$app->getRequest()->post('items', []);
		$model = new Assignment($id);
		$success = $model->revoke($items);
		Yii::$app->getResponse()->format = 'json';
		return array_merge($model->getItems(), ['success' => $success]);
	}


    /**
     * Изменение пароля
     *
     * @return mixed
     */
    public function actionChangePassword()
    {
        /** @var User $model */
        $model = Yii::$app->user->identity;
        $model->scenario = User::SCENARIO_CHANGE_PASSWORD;


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Data saved'));
            return $this->redirect(['change-password']);
        } else {
            return $this->render('change-password', [
                'model' => $model,
            ]);
        }
    }

	/**
	 * Находит модель по первичному ключу с проверкой прав доступа к чтению записи.
	 * Необходим для работы AjaxUpdate.
	 *
	 * @param integer $id
	 * @return User Возвращает найденный экземпляр модели
	 * @throws yii\web\NotFoundHttpException Возвращает 404 ошибку, если модели нет или к ней нет доступа.
	 */
	protected function findModel($id)
	{
		return User::findModel($id);
	}
}
