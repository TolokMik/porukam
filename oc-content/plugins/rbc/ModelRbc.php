<?php
		   /*
 * Copyright 2015 osclass-pro.com
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */

class ModelRbc extends DAO{

    private static $instance ;

    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();
    }

    public function getTable_rbc_user(){
        return DB_TABLE_PREFIX.'t_user';
    }
    public function getTable_rbc_log(){
        return DB_TABLE_PREFIX.'t_rbc_log';
    }
	public function getTable_rbc_wallet(){
            return DB_TABLE_PREFIX.'t_rbc_wallet';
    }

    public function getTable_rbc_premium(){
        return DB_TABLE_PREFIX.'t_rbc_premium';
    }
    public function getTable_rbc_publish(){
            return DB_TABLE_PREFIX.'t_rbc_publish';
        }
    public function getTable_rbc_prices(){
        return DB_TABLE_PREFIX.'t_rbc_prices';
    }
    public function getTable_rbc_item(){
        return DB_TABLE_PREFIX.'t_item';
    }


    /**
     * Import sql file
     * @param type $file
     */
  public function import($file)
    {
        $path = osc_plugin_resource($file) ;
        $sql = file_get_contents($path);

        if(! $this->dao->importSQL($sql) ){
            throw new Exception( "Error import SQL::ModelRbc<br>".$file ) ;
        }
    }

    /**
     *  Фкнкция для установки плагина
     */
    public function install(){
        $this->import('rbc/struct.sql');

        osc_set_preference('version', '321', 'rbc', 'INTEGER');
        osc_set_preference('default_premium_cost', '1.0', 'rbc', 'STRING');
        osc_set_preference('default_top_cost', '1.0', 'rbc', 'STRING');
		osc_set_preference('default_publish_cost', '1.0', 'rbc', 'STRING');
        osc_set_preference('default_color_cost', '1.0', 'rbc', 'STRING');
		osc_set_preference('allow_move', '0', 'rbc', 'BOOLEAN');
		osc_set_preference('allow_high', '0', 'rbc', 'BOOLEAN');
        osc_set_preference('allow_premium', '0', 'rbc', 'BOOLEAN');
        osc_set_preference('pay_per_post', '0', 'rbc', 'BOOLEAN');
		osc_set_preference('allow_after', '0', 'rbc', 'BOOLEAN');
        osc_set_preference('premium_days', '7', 'rbc', 'INTEGER');
		osc_set_preference('color_days', '7', 'rbc', 'INTEGER');
		osc_set_preference('rbc_email', '', 'rbc', 'STRING');
		osc_set_preference('currency', 'RUB', 'rbc', 'STRING');
		osc_set_preference('but_premium', '0', 'rbc', 'BOOLEAN');
		osc_set_preference('but_top', '0', 'rbc', 'BOOLEAN');
		osc_set_preference('but_high', '0', 'rbc', 'BOOLEAN');
		osc_set_preference('pack_price_1', '', 'rbc', 'STRING');
        osc_set_preference('pack_price_2', '', 'rbc', 'STRING');
        osc_set_preference('pack_price_3', '', 'rbc', 'STRING');

        osc_set_preference('mrhlogin','','rbc','STRING');
        osc_set_preference('mrhpass1','','rbc','STRING');
        osc_set_preference('mrhpass2','','rbc','STRING');


        $this->dao->query(sprintf("ALTER TABLE %s ADD `dt_r_colorized` datetime NOT NULL;", $this->getTable_rbc_item()) ) ;

         $this->dao->select('pk_i_id') ;
            $this->dao->from(DB_TABLE_PREFIX.'t_item') ;
            $result = $this->dao->get();
            if($result) {
                $items  = $result->result();
                $date = date("Y-m-d H:i:s");
                foreach($items as $item) {
                    $this->createItem_rbc($item['pk_i_id'], 1, $date);
                }
            }

            $description[osc_language()]['s_title'] = '{WEB_TITLE} - Publish option for your ad: {ITEM_TITLE}';
            $description[osc_language()]['s_text'] = '<p>Hi {CONTACT_NAME}!</p><p>We just published your item ({ITEM_TITLE}) on {WEB_TITLE}.</p><p>{START_PUBLISH_FEE}</p><p>In order to make your ad available to anyone on {WEB_TITLE}, you should complete the process and pay the publish fee. You could do that on the following link: {PUBLISH_LINK}</p><p>{END_PUBLISH_FEE}</p><p>{START_PREMIUM_FEE}</p><p>You could make your ad premium and make it to appear on top result of the searches made on {WEB_TITLE}. You could do that on the following link: {PREMIUM_LINK}</p><p>{END_PREMIUM_FEE}</p><p>This is an automatic email, if you already did that, please ignore this email.</p><p>Thanks</p>';
            $res = Page::newInstance()->insert(
                array('s_internal_name' => 'email_rbc', 'b_indelible' => '1'),
                $description
                );
    }

    /**
     *  Фкнкция для удаления плагина
     */
    public function uninstall(){
        $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_rbc_premium()));
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_rbc_publish()) ) ;
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_rbc_wallet()));
        $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_rbc_prices()));
		$this->dao->query(sprintf('ALTER TABLE %s DROP dt_r_colorized', $this->getTable_rbc_item()) ) ;
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_rbc_log()));
		
		$page = Page::newInstance()->findByInternalName('email_rbc');
        Page::newInstance()->deleteByPrimaryKey($page['pk_i_id']);

        osc_delete_preference('version', 'rbc');
        osc_delete_preference('default_premium_cost', 'rbc');
		osc_delete_preference('default_top_cost', 'rbc');
		osc_delete_preference('default_publish_cost', 'rbc');
		osc_delete_preference('default_color_cost', 'rbc');
        osc_delete_preference('allow_premium', 'rbc');
        osc_delete_preference('pay_per_post', 'rbc');
        osc_delete_preference('premium_days', 'rbc');
		osc_delete_preference('color_days', 'rbc');
		osc_delete_preference('allow_after', 'rbc');
		osc_delete_preference('allow_move', 'rbc');
		osc_delete_preference('allow_high', 'rbc');
		osc_delete_preference('currency', 'rbc');
		osc_delete_preference('but_premium', 'rbc');
		osc_delete_preference('but_top', 'rbc');
		osc_delete_preference('but_high', 'rbc');
		osc_delete_preference('pack_price_1', 'rbc');
        osc_delete_preference('pack_price_2', 'rbc');
        osc_delete_preference('pack_price_3', 'rbc');


        osc_delete_preference('mrhlogin', 'rbc');
        osc_delete_preference('mrhpass1', 'rbc');
        osc_delete_preference('mrhpass2', 'rbc');
		osc_delete_preference('rbc_email', 'rbc');


    }
	public function versionUpdate_rbc() {
            $version = osc_get_preference('version', 'rbc');
            if( $version < 321) {
                osc_set_preference('version', 321, 'rbc', 'INTEGER');
                osc_reset_preferences();
            }
			}


    public function getPayment_rbc(){
        $this->dao->select('*');
        $this->dao->from($this->getTable_rbc_log());
        $result = $this->dao->get();
        if($result)
            return $result->result();
        return array();
    }
	 public function get_class_color_rbc($item_id){
    $this->dao->select();
    $this->dao->from($this->getTable_rbc_item());
    $this->dao->where('pk_i_id', $item_id) ;
    $result = $this->dao->get();
            $row = $result->row();
            if($row) {
                if($row['dt_r_colorized']=="0000-00-00 00:00:00") {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
}
	 public function getPublishData_rbc($itemId) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_publish());
            $this->dao->where('fk_i_item_id', $itemId);
            $result = $this->dao->get();
            if($result) {
                return $result->row();
            }
            return false;
        }

        public function createItem_rbc($itemId, $paid = 0, $date = NULL, $rbc = NULL) {
            if($date==NULL) { $date = date("Y-m-d H:i:s"); };
            $this->dao->insert($this->getTable_rbc_publish(), array('fk_i_item_id' => $itemId, 'dt_date' => $date, 'b_paid' => $paid, 'fk_i_payment_id' => $rbc));
        }

    public function premiumOff_rbc($id) {
        $this->dao->delete($this->getTable_rbc_premium(), array('fk_i_item_id' => $id));
    }

    public function deleteItem_rbc($id) {
        $this->premiumOff_rbc($id);
    }
	        public function getPublishPrice_rbc($categoryId) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_prices());
            $this->dao->where('fk_i_category_id', $categoryId);
            $result = $this->dao->get();
            if($result) {
                $cat = $result->row();
                if(isset($cat['f_publish_cost'])) {
                    return $cat["f_publish_cost"];
                }
            }
            return osc_get_preference('default_publish_cost', 'rbc');
        }
         public function getUser_rbc($useremail) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_user());
            $this->dao->where('s_email', $useremail);
            $result = $this->dao->get();
            if($result) {
                $cat = $result->row();
                if(isset($cat['pk_i_id'])) {
                    return $cat["pk_i_id"];
                }
            }
           return array();
        }
		public function getUseremail_rbc($userid) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_user());
            $this->dao->where('pk_i_id', $userid);
            $result = $this->dao->get();
            if($result) {
                $cat = $result->row();
                if(isset($cat['s_email'])) {
                    return $cat["s_email"];
                }
            }
           return array();
        }
    public function getCategoriesPrices_rbc() {
        $this->dao->select('*') ;
        $this->dao->from($this->getTable_rbc_prices());
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

    /**
     * @param $category
     * @param $publish_fee
     * @param $premium_fee
     */
    public function insertPrice_rbc($category, $premium_fee,$top_fee,$color_fee,$publish_fee) {
        $this->dao->replace($this->getTable_rbc_prices(), array(
                                              'fk_i_category_id' => $category,
                                              'f_top_cost' => $top_fee,
                                              'f_color_cost' => $color_fee,
                                              'f_premium_cost' => $premium_fee,
											  'f_publish_cost' => $publish_fee
        ));
    }


    /**
     * @param $itemId
     * @return bool
     */
    public function getPremiumData_rbc($itemId) {
        $this->dao->select('*') ;
        $this->dao->from($this->getTable_rbc_premium());
        $this->dao->where('fk_i_item_id', $itemId);
        $result = $this->dao->get();
        if($result) {
            return $result->row();
        }
        return false;
    }

    /**
     * @param $categoryId
     * @return string
     */
    public function getPremiumPrice_rbc($categoryId) {
        $this->dao->select('*') ;
        $this->dao->from($this->getTable_rbc_prices());
        $this->dao->where('fk_i_category_id', $categoryId);
        $result = $this->dao->get();
        if($result) {
            $cat = $result->row();
            if(isset($cat['f_premium_cost'])) {
                return $cat["f_premium_cost"];
            }
        }
        return osc_get_preference('default_premium_cost', 'rbc');
    }

    public function getTopPrice_rbc($categoryId) {
        $this->dao->select('*') ;
        $this->dao->from($this->getTable_rbc_prices());
        $this->dao->where('fk_i_category_id', $categoryId);
        $result = $this->dao->get();
        if($result) {
            $cat = $result->row();
            if(isset($cat['f_top_cost'])) {
                return $cat["f_top_cost"];
            }
        }
        return osc_get_preference('default_top_cost', 'rbc');
    }


    public function getColorPrice_rbc($categoryId) {
        $this->dao->select('*') ;
        $this->dao->from($this->getTable_rbc_prices());
        $this->dao->where('fk_i_category_id', $categoryId);
        $result = $this->dao->get();
        if($result) {
            $cat = $result->row();
            if(isset($cat['f_color_cost'])) {
                return $cat["f_color_cost"];
            }
        }
        return osc_get_preference('default_color_cost', 'rbc');
    }
	public function getWallet_rbc($userid) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_wallet());
            $this->dao->where('fk_i_user_id', $userid);
            $result = $this->dao->get();
            if($result) {
                $row = $result->row();
                return $row;
            }
			return false;
			 }
			 
	public function addWallet_rbc($userid, $outsum) {
            $wallet = $this->getWallet_rbc($userid);
            if(isset($wallet['f_outsum'])) {
                $this->dao->update($this->getTable_rbc_wallet(), array('f_outsum' => $outsum+$wallet['f_outsum']), array('fk_i_user_id' => $userid));
            } else {
                $this->dao->insert($this->getTable_rbc_wallet(), array('fk_i_user_id' => $userid, 'f_outsum' => $outsum));
            }

        }




    public function premiumFeeIsPaid_rbc($itemId) {
        $this->dao->select('*') ;
        $this->dao->from($this->getTable_rbc_premium());
        $this->dao->where('fk_i_item_id', $itemId);
        $this->dao->where(sprintf("TIMESTAMPDIFF(DAY,dt_date,'%s') < %d", date('Y-m-d H:i:s'), osc_get_preference("premium_days", "rbc")));
        $result = $this->dao->get();
        $row = $result->row();
        if(isset($row['dt_date'])) {
            return true;
        }
        return false;
    }
	public function publishFeeIsPaid_rbc($itemId) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_publish());
            $this->dao->where('fk_i_item_id', $itemId);
            $result = $this->dao->get();
            $row = $result->row();
            if($row) {
                if($row['b_paid']==1) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }
		public function purgeExpired_rbc() {
            $this->dao->select("fk_i_item_id");
            $this->dao->from($this->getTable_rbc_premium());
            $this->dao->where(sprintf("TIMESTAMPDIFF(DAY,dt_date,'%s') >= %d", date('Y-m-d H:i:s'), osc_get_preference("premium_days", "rbc")));
            $result = $this->dao->get();
            if($result) {
                $items = $result->result();
                $mItem = new ItemActions(false);
                foreach($items as $item) {
                    $mItem->premium($item['fk_i_item_id'], false);
                    $this->premiumOff_rbc($item['fk_i_item_id']);
                }
            }
			$this->dao->select("pk_i_id");
            $this->dao->from($this->getTable_rbc_item());
            $this->dao->where ( sprintf ( "TIMESTAMPDIFF(DAY,dt_r_colorized,'%s') >= %d", date('Y-m-d H:i:s'), osc_get_preference ( "color_days", "rbc" ) ) );
            $result = $this->dao->get();
            if($result) {
                $items = $result->result();     
                foreach($items as $item) {
	            $this->dao->update ( $this->getTable_rbc_item(), array ( 'dt_r_colorized'=>"0000-00-00 00:00:00" ), array ( 'pk_i_id' => $item['pk_i_id'] ) );
                }
            }
        }



    public function setTopItem_rbc($itemId){
        $this->dao->update($this->getTable_rbc_item(),array('dt_pub_date'=>date("Y-m-d H:i:s")),array('pk_i_id' => $itemId));
    }


  public function setColor_rbc($itemId){
     $this->dao->update($this->getTable_rbc_item(),array('dt_r_colorized'=>date("Y-m-d H:i:s")),array('pk_i_id' => $itemId));
    }




    public function payPremiumFee_rbc($itemId, $paymentId) {
        $paid = $this->getPremiumData_rbc($itemId);
        if(empty($paid)) {
            $this->dao->insert($this->getTable_rbc_premium(), array('dt_date' => date("Y-m-d H:i:s"), 'fk_i_payment_id' => $paymentId, 'fk_i_item_id' => $itemId));
        } else {
            $this->dao->update($this->getTable_rbc_premium(), array('dt_date' => date("Y-m-d H:i:s"), 'fk_i_payment_id' => $paymentId), array('fk_i_item_id' => $itemId));
        }
        $mItem = new ItemActions(false);
        $mItem->premium($itemId, true);
    }
	public function payPublishFee_rbc($itemId, $paymentId) {
            $paid = $this->getPublishData_rbc($itemId);
            if(empty($paid)) {
                $this->createItem_rbc($itemId, 1, date("Y-m-d H:i:s"), $paymentId);
            } else {
                $this->dao->update($this->getTable_rbc_publish(), array('b_paid' => 1, 'dt_date' => date("Y-m-d H:i:s"), 'fk_i_payment_id' => $paymentId), array('fk_i_item_id' => $itemId));
            }
            $mItems = new ItemActions(false);
            $mItems->enable($itemId);
        }

    /**

     *
     * @param string $concept
     * @param string $code
     * @param float $amount
     * @param string $currency
     * @param string $email
     * @param integer $item
     * @param string $product_type (publish fee, premium, pack and which category)
     * @param string $source
     * @return integer $last_id
     */
    public function saveLog_rbc($concept,$currency,$hashTotal,$userid,$itemId,$ist1,$pro2co) {

        $this->dao->insert($this->getTable_rbc_log(), array(
            'dt_date' => date("Y-m-d H:i:s"),
            'f_outsum'=> $hashTotal,
			's_concept'=> $concept,
            's_currency_code' => $currency,
			'fk_i_user_id' => $userid,
            'fk_i_item_id' => $itemId,
			's_source' => $ist1,
			'i_product_type' => $pro2co
        ));
        return $this->dao->insertedId();
    }
	public function getLogs_rbc() {

            $this->dao->select('*') ;
            $this->dao->from($this->getTable_rbc_log());
            $result = $this->dao->get();
            if($result) {
                return $result->result();
            }
            return array();
        }




} 