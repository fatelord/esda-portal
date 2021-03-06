<?php

namespace app\modules\user\controllers;

use app\components\Constants;
use app\modules\user\models\AuthenticationModel;
use Yii;
use app\modules\user\models\ProfileModel;
use app\modules\user\search\ProfileSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProfileController implements the CRUD actions for UserProfile model.
 */
class ProfileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	    return [
		    'verbs' => [
			    'class' => VerbFilter::className(),
			    'actions' => [
				    'delete' => ['post'],
				    'request-for-bid' => ['post'],
			    ],
		    ],
		    'access' => [
			    'class' => AccessControl::className(),
			    'only' => ['index', 'update','delete'],
			    'rules' => [
				    // allow authenticated users
				    [
					    'allow' => true,
					    'roles' => ['@'],
				    ],
				    // everything else is denied
			    ],
		    ]
	    ];
    }

    /**
     * Lists all UserProfile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserProfile model.
     * @return mixed
     */
    public function actionView()
    {
        $id = Yii::$app->user->id;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfileModel();
        $model->scenario = Constants::SCENARIO_SIGNUP;

        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //now insert the login credentials into teh authentication model
            $authModel = new AuthenticationModel();
            $authModel->isNewRecord = true;

            $authModel->USER_ID = $model->USER_ID;
            $authModel->PASSWORD = $model->PASSWORD;
            $authModel->ACCOUNT_AUTH_KEY = $model->ACCOUNT_AUTH_KEY;
            if ($authModel->save()) {
                //commit transaction
                $transaction->commit();
                //$this->redirect(['view', 'id' => $model->USER_ID]);
                $this->redirect(['//site/login']);
            } else {
                //roll back the transaction
                $model->PASSWORD = null; //clear the password field
                $model->REPEAT_PASSWORD = null; //clear the repeat password field
                $model->ACCOUNT_AUTH_KEY = null;
                $transaction->rollback();
            }
        }
        return $this->render('create', ['model' => $model,]);
    }

    /**
     * Updates an existing UserProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Constants::SCENARIO_UPDATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->USER_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfileModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfileModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
