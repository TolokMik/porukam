<h1>Reset</h1>
<?php
$item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
ModelRbc::newInstance()->premiumOff($item['pk_i_id']);