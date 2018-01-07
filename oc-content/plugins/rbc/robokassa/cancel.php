<?php
		   /*
 * Copyright 2015 osclass-pro.com and osclass-pro.ru
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */

  osc_add_flash_error_message(__("There was a problem processing your Payment. Please contact the administrators",'rbc'));
  rbc_js_redirect_to(osc_search_category_url());

?>








