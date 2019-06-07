<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 6/4/19
 * Time: 10:25 AM
 */

namespace app\controllers;

use app\models\CommentSendForm;
use app\models\TicketSendForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\OwnedTicketSearch;
use app\models\ListOfTicketSearch;
use app\models\Ticket;
use yii\web\NotFoundHttpException;

class TicketController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['sendticket', 'listownedtickets', 'listopenedtickets', 'listclosedtickets', 'takingticket', 'viewticket', 'setpriority', 'reportticket'],
                'rules' => [
                    [
                        'actions' => ['sendticket', 'listownedtickets', 'viewticket', 'reportticket'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->identity->is_admin;
                        }
                    ],
                    [
                        'actions' => ['listopenedtickets', 'listclosedtickets', 'takingticket', 'viewticket', 'setpriority'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->is_admin;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * This action sends a ticket.
     *
     * @return string
     * @throws \Throwable
     */
    public function actionSendticket()
    {
        $ticketSendForm = new TicketSendForm();

        $transaction = $ticketSendForm->getDb()->beginTransaction();

        try {
            if ($ticketSendForm->load(Yii::$app->request->post()) && $ticketSendForm->sendTicket()) {
                $transaction->commit();
                $this->redirect('/ticket/listownedtickets');
            } else {
                return $this->render('/ticket/sendticket', [
                    'ticketSendForm' => $ticketSendForm,
                ]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    /**
     * Lists all owned Ticket.
     *
     * @return mixed
     */
    public function actionListownedtickets()
    {
        $ownedTicketSearch = new OwnedTicketSearch();
        $dataProvider = $ownedTicketSearch->search(Yii::$app->request->queryParams);
        //$id = Yii::$app->user->identity->getId();

        return $this->render('listownedtickets', [
            'ownedTicketSearch' => $ownedTicketSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Makes possible for admins to take tickets.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTakingticket($id)
    {

        $ticketModel = $this->findModel($id);
        $ticketModel->setAttribute('admin_id', $id);

        if ($ticketModel->load(Yii::$app->request->post()) && $ticketModel->save()) {
            return $this->redirect(['listopenedtickets', 'id' => $ticketModel->id]);
        }

        return $this->render('takingticket', [
            'ticketModel' => $ticketModel,
        ]);
    }

    /**
     * Lists all Opened Ticket that doesn't have manager admin.
     *
     * @return mixed
     */
    public function actionListopenedtickets()
    {
        $listOfTicketSearch = new ListOfTicketSearch();
        $dataProvider = $listOfTicketSearch->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['status' => true]);

        return $this->render('listopenedtickets', [
            'listOfTicketSearch' => $listOfTicketSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Closed Ticket models.
     * @return mixed
     */
    public function actionListclosedtickets()
    {
        $listOfTicketSearch = new ListOfTicketSearch();
        $dataProvider = $listOfTicketSearch->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['status' => false]);
        return $this->render('listclosedtickets', [
            'listOfTicketSearch' => $listOfTicketSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionViewticket($id)
    {
        $ticketModel = $this->findModel($id);
        $commentSendForm = new CommentSendForm();
        $dataProvider = new ActiveDataProvider([
            'query' => $ticketModel->getCommentsDesc(),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        if ($commentSendForm->load(Yii::$app->request->post())) {

            $transaction = $commentSendForm->getDb()->beginTransaction();

            try {
                $commentSendForm->sendComment();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();

                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();

                throw $e;
            }
        }

        return $this->render('viewticket', [
            'commentFormModel' =>  new CommentSendForm(),
            'ticketModel' => $ticketModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Action to set priority page.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSetpriority($id) {
        $ticketModel = $this->findModel($id);

        if ($ticketModel->load(Yii::$app->request->post()) && $ticketModel->save()) {
            return Yii::$app->user->identity->is_admin ? $this->redirect(['listopenedtickets']) : $this->redirect(['listownedtickets']);
        }

        return $this->render('setpriority', [
           'ticketModel' => $ticketModel,
        ]);
    }

    /**
     * Action to sends report.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionReportticket($id) {
        $ticketModel = $this->findModel($id);
        $commentSendForm = new CommentSendForm();

        if ($ticketModel->load(Yii::$app->request->post()) && $commentSendForm->load(Yii::$app->request->post())) {

            $transaction = $commentSendForm->getDb()->beginTransaction();

            try {
                $commentSendForm->sendComment();
                $ticketModel->save();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();

                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();

                throw $e;
            }

            return Yii::$app->user->identity->is_admin ? $this->redirect(['listopenedtickets']) : $this->redirect(['listownedtickets']);
        }

        return $this->render('reportticket', [
            'ticketModel' => $ticketModel,
            'commentSendForm' => $commentSendForm,
        ]);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}