<?php
	defined('ABSPATH') or die('Jog on!');

  /*
  // ------------------------------------------------------------------------------------------------------------
  Looking to bypass the license cost?
  // ------------------------------------------------------------------------------------------------------------

  Please Read:

  This file handles the license for the PRO version. Yes, I bundle all the Pro version code within the free plugin. So of course, all you need to do is hack this file to get Pro version for free.
  My faith in human nature would love to think you wouldn't do that and pay the small price for the Pro version. That small fee keeps me developing this plugin.

  - To save you hacking my beautiful code about, simply change the variable below to true to get out of paying a penny (shame on you)

  */

  define('WS_LS_I_DONT_WANT_TO_PAY_A_THING', false); // Don't be that person!

  // ------------------------------------------------------------------------------------------------------------
  // Leave below. Just break above!
  // ------------------------------------------------------------------------------------------------------------

	define('WS_LS_LICENSE_SITE_HASH', 'ws-ls-license-site-hash');
	define('WS_LS_LICENSE', 'ws-ls-license');
	define('WS_LS_LICENSE_VALID', 'ws-ls-license-valid');

  function ws_ls_generate_site_hash()
  {
    $site_hash = get_option(WS_LS_LICENSE_SITE_HASH);

    // Generate a basic site key from URL and plugin slug
    if(false == $site_hash) {
      $site_hash = md5(WE_LS_SLUG . '-' . site_url());
      $site_hash = substr($site_hash, 0, 6);
      update_option(WS_LS_LICENSE_SITE_HASH, $site_hash);
    }
    return $site_hash;
  }

  function ws_ls_has_a_valid_license()
  {
    $valid_license = get_option(WS_LS_LICENSE_VALID);

    // If we have a tight ass
    if(true == WS_LS_I_DONT_WANT_TO_PAY_A_THING) {
      return true;
    }

    if(true == $valid_license) {
      return true;
    }

    return false;
  }

  function ws_ls_generate_license($site_hash)
  {
    // I know, I know. Not secure. However I'm trusting you people not to rip it off for a few pounds!
    return md5('yeken.co.uk' . $site_hash);
  }

  function ws_ls_is_validate_license($license_key_from_yeken)
  {
    $site_hash = ws_ls_generate_site_hash();
    $comparison_license = ws_ls_generate_license($site_hash);

    if ($comparison_license == $license_key_from_yeken){
      update_option(WS_LS_LICENSE, $license_key_from_yeken);
      update_option(WS_LS_LICENSE_VALID, true);
      return true;
    }

    return false;
  }
	function ws_ls_get_license()
  {
		return get_option(WS_LS_LICENSE);
  }
