<?php

namespace app\modules\bot\controllers\privates;

use Yii;
use app\modules\bot\components\Controller;
use app\modules\bot\components\helpers\Emoji;
use app\modules\bot\components\helpers\MessageText;
use app\models\User;
use app\models\UserStellar;

/**
 * Class MyStellarController
 *
 * @package app\modules\bot\controllers\privates
 */
class MyStellarController extends Controller
{
    /**
     * @return array
     */
    public function actionIndex()
    {
        $this->getState()->setName(null);
        $user = $this->getUser();

        if (isset($user->stellar)) {
            if ($user->stellar->isExpired()) {
                $user->stellar->delete();
                unset($user->stellar);

                return $this->actionUpdate();
            }
        } else {
            return $this->actionUpdate();
        }

        if (!$user->stellar->isConfirmed()) {
            $buttons[] = [
                [
                    'callback_data' => self::createRoute('confirm'),
                    'text' => Yii::t('app', 'Confirm'),
                ],
            ];
        }

        // TODO показывать только при наличии доступных групп для пользователя
        if ($user->stellar->isConfirmed()) {
            $buttons[] = [
                [
                    'callback_data' => self::createRoute('index'),
                    'text' => Yii::t('app', 'Telegram groups'),
                ],
            ];
        }

        $buttons[] = [
            [
                'callback_data' => MyAccountController::createRoute(),
                'text' => Emoji::BACK,
            ],
            [
                'text' => Emoji::MENU,
                'callback_data' => MenuController::createRoute(),
            ],
            [
                'callback_data' => self::createRoute('update'),
                'text' => Emoji::EDIT,
            ],
            [
                'callback_data' => self::createRoute('delete'),
                'text' => Emoji::DELETE,
            ],
        ];

        return $this->getResponseBuilder()
            ->editMessageTextOrSendMessage(
                $this->render('index', [
                    'stellar' => $user->stellar,
                ]),
                $buttons,
                [
                        'disablePreview' => true,
                ]
            )
            ->build();
    }

    public function actionUpdate()
    {
        $this->getState()->setName(self::createRoute('update'));
        $user = $this->getUser();

        if ($this->getUpdate()->getMessage()) {
            if ($text =  $this->getUpdate()->getMessage()->getText()) {
                if (isset($user->stellar)) {
                    if ($user->stellar->public_key != $text) {
                        $user->stellar->public_key = $text;
                        $user->stellar->created_at = time();
                        $user->stellar->confirmed_at = null;
                    }

                    $userStellar = $user->stellar;
                } else {
                    $userStellar = new UserStellar();

                    $userStellar->user_id = $user->id;
                    $userStellar->public_key = $text;
                }

                if ($userStellar->save()) {
                    unset($user->stellar);

                    return $this->actionIndex();
                } else {
                    Yii::warning($userStellar->getErrors());
                }
            }
        }

        return $this->getResponseBuilder()
            ->editMessageTextOrSendMessage(
                $this->render('update'),
                [
                    [
                        [
                            'callback_data' => ($user->stellar ? self::createRoute() : MyAccountController::createRoute()),
                            'text' => Emoji::BACK,
                        ],
                    ],
                ]
            )
            ->build();
    }

    public function actionDelete()
    {
        $user = $this->getUser();

        if (isset($user->stellar)) {
            $user->stellar->delete();
            unset($user->stellar);
        }

        return $this->actionIndex();
    }

    // TODO use Stellar API
    public function actionConfirm()
    {
        return $this->actionIndex();
    }
}