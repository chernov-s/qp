<?php

namespace app\modules\backend\controllers;

use Yii;
use app\models\Order;
use app\models\OrderProduct;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'product-delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->joinWith('user'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $order = Order::findOneOr404($id);
        if ($order->load(Yii::$app->request->post())) {
            if ($order->save()) {
                Yii::$app->session->setFlash('success', 'Заказ изменён');
            }
            else {
                Yii::$app->session->setFlash('error', 'Ошибка при изменении заказа. ' .
                    implode(', ', $order->getFirstErrors()));
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => OrderProduct::find()->where(['order_id' => $id]),
        ]);

        return $this->render('update', [
            'dataProvider' => $dataProvider,
            'order' => $order,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Order::findOneOr404($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionProductUpdate($id)
    {
        $model = OrderProduct::findOneOr404($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->order_id]);
        } else {
            return $this->render('product-update', [
                'model' => $model,
            ]);
        }
    }

    public function actionProductCreate($order_id)
    {
        $model = new OrderProduct();
        $model->order_id = $order_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->order_id]);
        } else {
            return $this->render('product-create', [
                'model' => $model,
            ]);
        }
    }
    public function actionProductDelete($id)
    {
        $model = OrderProduct::findOneOr404($id);
        $model->delete();

        return $this->redirect(['update', 'id' => $model->order_id ]);
    }
}
