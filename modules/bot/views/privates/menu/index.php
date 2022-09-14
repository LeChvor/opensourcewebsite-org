<?php

use app\components\helpers\Html;
use app\modules\bot\components\helpers\ExternalLink;

?>
<b>ID</b>: #<?= $user->id; ?><?= ($user->username ? ' @' . $user->username : '') ?><br/>
<br/>
<b><?= Yii::t('user', 'Rank') ?></b>: <?= $user->getRank(); ?><br/>
<b><?= Yii::t('user', 'Rating') ?></b>: <?= $user->getRating() ?><br/>
<b><?= Yii::t('user', 'Real confirmations') ?></b>: <?= $user->getRealConfirmations(); ?><br/>
<?php if ($userLocation = $user->userLocation) : ?>
<br/>
<b><?= Yii::t('bot', 'Location') ?></b>: <?= Html::a($userLocation->location, ExternalLink::getOSMLink($userLocation->location_lat, $userLocation->location_lon)) ?><br/>
<?php endif; ?>
