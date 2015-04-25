<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\EditPicture;
use app\models\Save;
use app\models\Gallery;
//use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            /*'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
        ];

    }

    public function actionIndex()
    {
        $model = new Gallery();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionEditor($id=0)
    {
        //was pressed Save button
        if(isset(\Yii::$app->request->post()['save-button']))
        {
            return $this->goHome();
        }

        $model = new EditPicture();

        if($model->load(\Yii::$app->request->post()) && $model->register())
        {
            return $this->goHome();
        } 
        else
        {
            return $this->render('editor', [
                'model' => $model,
            ]);
        }
    }

    //save picture action
    public function actionSave()
    {
        $model = new Save();
        
        switch(\Yii::$app->request->post()['action'])
        {
            case "save":
                $model->save();
                break;
            case "delete":
                $model->delete();
                break;
        }
    }
}
