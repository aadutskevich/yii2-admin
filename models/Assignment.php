<?php

namespace aadutskevich\admin\models;

use common\models\User;
use Yii;
use yii\base\Object;
use aadutskevich\admin\components\Helper;

/**
 * Description of Assignment
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 2.5
 */
class Assignment extends Object
{
	/**
	 * @var integer User id
	 */
	public $id;
	/**
	 * @var \yii\web\IdentityInterface User
	 */
	public $user;

	/**
	 * Assignment constructor.
	 * @param integer $id
	 * @param User $user
	 * @param array $config
	 */
	public function __construct($id, $user = NULL, $config = [])
	{
		$this->id = $id;
		$this->user = $user;
		parent::__construct($config);
	}

	/**
	 * Grands a roles from a user.
	 * @param array $items
	 * @return integer number of successful grand
	 */
	public function assign($items)
	{
		$manager = Yii::$app->getAuthManager();
		$success = 0;
		foreach ($items as $name) {
			try {
				$item = $manager->getRole($name);
				$item = $item ?: $manager->getPermission($name);
				$manager->assign($item, $this->id);
				$success++;
			} catch (\Exception $exc) {
				Yii::error($exc->getMessage(), __METHOD__);
			}
		}
		Helper::invalidate();

		return $success;
	}

	/**
	 * Revokes a roles from a user.
	 * @param array $items
	 * @return integer number of successful revoke
	 */
	public function revoke($items)
	{
		$manager = Yii::$app->getAuthManager();
		$success = 0;
		foreach ($items as $name) {
			try {
				$item = $manager->getRole($name);
				$item = $item ?: $manager->getPermission($name);
				$manager->revoke($item, $this->id);
				$success++;
			} catch (\Exception $exc) {
				Yii::error($exc->getMessage(), __METHOD__);
			}
		}
		Helper::invalidate();

		return $success;
	}

	/**
	 * Get all available and assigned roles/permission
	 * @return array
	 */
	public function getItems()
	{
		$manager = Yii::$app->getAuthManager();
		$available = [];
		foreach ($manager->getRoles() as $role) {
			$available[$role->name] = [
				'type' => $role->type,
				'name' => $role->name,
				'name_t' => $role->name_t,
			];
		}

		foreach ($manager->getPermissions() as $permission) {
			if (strpos($permission->name, '/') !== 0) { // исключаем маршруты
				$available[$permission->name] = [
					'type' => $permission->type,
					'name' => $permission->name,
					'name_t' => $permission->name_t,
				];
			}
		}


		$assigned = [];
		foreach ($manager->getAssignments($this->id) as $item) {
			$assigned[$item->roleName] = $available[$item->roleName];
			unset($available[$item->roleName]);
		}

		return [
			'available' => $available,
			'assigned' => $assigned,
		];
	}

	/**
	 * @inheritdoc
	 */
	public function __get($name)
	{
		if ($this->user) {
			return $this->user->$name;
		}
	}
}
