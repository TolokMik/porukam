<?php
		   /*
 * Copyright 2015 osclass-pro.com
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */
function rbc_get_class_color($item_id){
	if (ModelRbc::newInstance()->get_class_color_rbc(osc_item_id())){ 
        return 'normal';
		}
	else {
		return 'colorized';
		}
	}
	
function rbc_premium_get_class_color($item_id){
	if (ModelRbc::newInstance()->get_class_color_rbc(osc_premium_id())){ 
        return 'normal';
		}
	else {
		return 'colorized';
		}
	}
	   function rbc_js_redirect_to($url) { ?>
        <script type="text/javascript">
            window.top.location.href = "<?php echo $url; ?>";
        </script>
    <?php }
	
	function rbc_send_email($item, $category_fee) {

        if(osc_is_web_user_logged_in()) {
            return false;
        }

        $mPages = new Page() ;
        $aPage = $mPages->findByInternalName('email_rbc') ;
        $locale = osc_current_user_locale() ;
        $content = array();
        if(isset($aPage['locale'][$locale]['s_title'])) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }

        $item_url    = osc_item_url( ) ;
        $item_url    = '<a href="' . $item_url . '" >' . $item_url . '</a>';
        $publish_url = osc_route_url('rbc-publish', array('itemId' => $item['pk_i_id']));
        $premium_url = osc_route_url('robokassa-premium', array('itemId' => $item['pk_i_id']));

        $words   = array();
        $words[] = array('{ITEM_ID}', '{CONTACT_NAME}', '{CONTACT_EMAIL}', '{WEB_URL}', '{ITEM_TITLE}',
            '{ITEM_URL}', '{WEB_TITLE}', '{PUBLISH_LINK}', '{PUBLISH_URL}', '{PREMIUM_LINK}', '{PREMIUM_URL}',
            '{START_PUBLISH_FEE}', '{END_PUBLISH_FEE}', '{START_PREMIUM_FEE}', '{END_PREMIUM_FEE}');
        $words[] = array($item['pk_i_id'], $item['s_contact_name'], $item['s_contact_email'], osc_base_url(), $item['s_title'],
            $item_url, osc_page_title(), '<a href="' . $publish_url . '">' . $publish_url . '</a>', $publish_url, '<a href="' . $premium_url . '">' . $premium_url . '</a>', $premium_url, '', '', '', '') ;

        if($category_fee==0) {
            $content['s_text'] = preg_replace('|{START_PUBLISH_FEE}(.*){END_PUBLISH_FEE}|', '', $content['s_text']);
        }

        $premium_fee = ModelRbc::newInstance()->getPremiumPrice_rbc($item['fk_i_category_id']);

        if($premium_fee==0) {
            $content['s_text'] = preg_replace('|{START_PREMIUM_FEE}(.*){END_PREMIUM_FEE}|', '', $content['s_text']);
        }

        $title = osc_mailBeauty($content['s_title'], $words) ;
        $body  = osc_mailBeauty($content['s_text'], $words) ;

        $emailParams =  array('subject'  => $title
        ,'to'       => $item['s_contact_email']
        ,'to_name'  => $item['s_contact_name']
        ,'body'     => $body
        ,'alt_body' => $body);

        osc_sendMail($emailParams);
    }
	
function rbc_prepare_custom($extra_array = null) {
        if($extra_array!=null) {
            if(is_array($extra_array)) {
                $extra = '';
                foreach($extra_array as $k => $v) {
                    $extra .= $k.",".$v."|";
                }
            } else {
                $extra = $extra_array;
            }
        } else {
            $extra = "";
        }
        return $extra;
    }

    function rbc_get_custom($custom) {
        $tmp = array();
        if(preg_match_all('@\|?([^,]+),([^\|]*)@', $custom, $m)){
            $l = count($m[1]);
            for($k=0;$k<$l;$k++) {
                $tmp[$m[1][$k]] = $m[2][$k];
            }
        }
        return $tmp;
    }
	

    /**
     * Create and print a "Wallet" button
     *
     * @param float $amount
     * @param string $description
     * @param string $rpl custom variables
     * @param string $itemnumber (publish fee, premium, pack and which category)
     */
    function rbc_button($hashTotal = '0.00', $concept = '', $invId, $userid, $type , $extra_array = '||') {
        $extra = rbc_prepare_custom($extra_array);
        $extra .= 'concept,'.$concept.'|';
		$extra .= 'itemid,'.$invId.'|';
		$extra .= 'user,'.$userid.'|';
        $extra .= 'product,'.$type.'|';

        echo '<a href="'.osc_route_url('rbc-wallet', array('a' => $hashTotal, 'extra' => $extra)).'"><button>'.__("Pay with your credit", "rbc").'</button></a>';
    }



