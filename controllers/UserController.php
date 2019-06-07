<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\AdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\UserSearch;
use app\models\TicketSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['adminlist', 'userlist', 'viewprofile', 'modifyuser', 'sendticket', 'listownedtickets', 'delete', 'modifyname', 'ticketlist'],
                'rules' => [
                    [
                        'actions' => ['adminlist', 'userlist', 'modifyuser', 'delete', 'ticketlist'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($role, $action) {
                            return Yii::$app->user->identity->is_admin;
                        }
                    ],
                    [
                        'actions' => ['viewprofile', 'sendticket', 'listownedtickets', 'modifyname'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($role, $action) {
                            return !Yii::$app->user->identity->is_admin;
                        }
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Admin.
     *
     * @return mixed
     */
    public function actionAdminlist()
    {
        $adminSearch = new AdminSearch();
        $adminSearch->is_admin = true;
        $dataProvider = $adminSearch->search(Yii::$app->request->queryParams);

        return $this->render('adminlist', [
            'adminSearch' => $adminSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all User who is not admin.
     *
     * @return mixed
     */
    public function actionUserlist()
    {
        $userSearch = new UserSearch();
        $userSearch->is_admin = false;
        $dataProvider = $userSearch->search(Yii::$app->request->queryParams);

        return $this->render('userlist', [
            'userSearch' => $userSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * User's profile controller.
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewprofile()
    {
        return $this->render('viewprofile', [
            'model' => $this->findModel(Yii::$app->user->identity->getId()),
        ]);
    }

    /**
     * Admin can change user's data.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionModifyuser($id)
    {
        $userUpdate = $this->findModel($id);

        if ($userUpdate->load(Yii::$app->request->post()) && $userUpdate->save()) {
            return $this->redirect(['userlist', 'id' => $userUpdate->id]);
        }

        return $this->render('modifyuser', [
            'userUpdate' => $userUpdate,
        ]);
    }

    /**
     * Deletes user by ID.
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['userlist']);
    }

    /**
     * Makes possible to modify user's name.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionModifyname($id)
    {
        $nameModel = $this->findModel($id);

        if ($nameModel->load(Yii::$app->request->post()) && $nameModel->save()) {
            return $this->redirect(['viewprofile', 'id' => $nameModel->id]);
        }

        return $this->render('/user/modifyname', [
            'nameModel' => $nameModel,
        ]);
    }

    /**
     * Lists exact user's tickets.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTicketlist($id) {

        $ticketSearch = new TicketSearch();
        $dataProvider = $ticketSearch->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['user_id' => $id]);

        $ticketModel = $this->findModel($id);

        return $this->render('/user/ticketlist', [
            'dataProvider' => $dataProvider,
            'ticketModel' => $ticketModel
        ]);

    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
