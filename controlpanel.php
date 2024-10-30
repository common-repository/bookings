<?php

function bookings_options() {
	global $bookings_name, $bookings_shortname, $cc_login_type, $current_user, $wp_roles, $bookingsRegions;
	$bookings_name="Bookings";
	$bookings_shortname="bookings";
	
	if (!get_option('bookings_region')) $nearestRegion=zingiri::findNearestServer($bookingsRegions);
	else $nearestRegion=null;
	if (!get_option('bookings_key')) update_option('bookings_key', zingiri::create_api_key());
	if (!get_option('bookings_secret')) update_option('bookings_secret', zingiri::create_secret());
	
	$regions=array();
	foreach ($bookingsRegions as $id => $region) {
		$regions[$id]=$region[0];
	}
	
	$bookings_options[]=array("name" => "Settings","type" => "heading","desc" => "This section customizes the way the Bookings plugin works.");
	$bookings_options[]=array("name" => "API Key","desc" => 'This plugin uses remote web services to provide mailing list functionality. This API key has been automatically generated for you. Once you click on Install, the API key, in combination with your web site address <strong>' . home_url() . '</strong> will create an account on our servers allowing the plugin to access the remote web services.<br />The combination of API key and your web site address uniquely identifes you so please make sure to keep it in a safe place.',"id" => $bookings_shortname . "_key","type" => "text");
	$bookings_options[]=array("name" => "License Key","desc" => 'If you wish to make use of the <strong>Bookings Pro</strong> features, enter your license key here. You can purchase a license key <a href="https://www.zingiri.com/go/cart.php" target="blank">here</a>. The Pro version provides additional functionality and has no limits to the number of bookings and schedules you can use.',"id" => $bookings_shortname . "_lic","type" => "text");
	$bookings_options[]=array("name" => "Region","desc" => "Region you are connected to. Select the region the server your site is hosted on is closest to.","options" => $regions,"id" => "bookings_region","std" => $nearestRegion,"type" => "selectwithkey");

	//login name
	$login=bookings_login();
	$bookings_options[]=array("name" => "User","desc" => "A user identified with this name will be created when you activate the connection.","id" => "bookings_login","std" => $login,"type" => "text","readonly" => true);
	
	// languages
	$languages=array('ar' => array('ar([-_][[:alpha:]]{2})?|arabic','ar.lang.php','ar','Arabic (&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;)'),'bg' => array('bg([-_][[:alpha:]]{2})?|bulgarian','bg.lang.php','bg','Bulgarian (&#x0411;&#x044a;&#x043b;&#x0433;&#x0430;&#x0440;&#x0441;&#x043a;&#x0438;)'),'zh_CN' => array('zh([-_]cn)?|chinese','zh_CN.lang.php','zh','Chinese Simplified (&#x7b80;&#x4f53;&#x4e2d;&#x6587;)'),'zh_TW' => array('zh([-_]tw)?|chinese','zh_TW.lang.php','zh','Chinese Traditional (&#x6b63;&#x9ad4;&#x4e2d;&#x6587;)'),'cs' => array('cs([-_][[:alpha:]]{2})?|czech','cs.lang.php','cs','Czech (&#x010c;esky)'),'da' => array('da([-_][[:alpha:]]{2})?|danish','da.lang.php','da','Dansk'),'de' => array('de([-_][[:alpha:]]{2})?|german','de.lang.php','de','Deutsch'),'es' => array('es([-_][[:alpha:]]{2})?|spanish','es.lang.php','es','Espa&ntilde;ol'),'fr' => array('fr([-_][[:alpha:]]{2})?|french','fr.lang.php','fr','Fran&ccedil;ais'),'el' => array('el([-_][[:alpha:]]{2})?|greek','el.lang.php','el','Greek (&#x0395;&#x03bb;&#x03bb;&#x03b7;&#x03bd;&#x03b9;&#x03ba;&#x03ac;)'),'en_US' => array('en([-_]us)?|english','en_US.lang.php','en','English US'),'en_GB' => array('en([-_]gb)?|english','en_GB.lang.php','en','English GB'),'it' => array('it([-_][[:alpha:]]{2})?|italian','it.lang.php','it','Italiano'),'ja' => array('ja([-_][[:alpha:]]{2})?|Japanese','ja_JP.lang.php','ja','Japanese'),'ko' => array('ko([-_][[:alpha:]]{2})?|korean','ko_KR.lang.php','ko','Korean (&#54620;&#44397;&#50612;)'),'hu' => array('hu([-_][[:alpha:]]{2})?|hungarian','hu.lang.php','hu','Magyar'),'nl' => array('nl([-_][[:alpha:]]{2})?|dutch','nl.lang.php','nl','Nederlands'),'no' => array('no([-_][[:alpha:]]{2})?|norwegian','no.lang.php','no','Norwegian'),'pl' => array('pl([-_][[:alpha:]]{2})|polish','pl.lang.php','pl','Polski'),'pt_PT' => array('pr([-_]PT)|portuguese','pt_PT.lang.php','pt','Portugu&ecirc;s'),'pt_BR' => array('pr([-_]BR)|portuguese','pt_BR.lang.php','pt','Portugu&ecirc;s Brasileiro'),'ru' => array('ru([-_][[:alpha:]]{2})?|russian','ru.lang.php','ru','Russian (&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439;)'),'sk' => array('sk([-_][[:alpha:]]{2})?|slovakian','sk.lang.php','sk','Slovak (Sloven&#x010d;ina)'),'sl' => array('sl([-_][[:alpha:]]{2})?|slovenian','sl.lang.php','sl','Slovensko'),'fi' => array('fi([-_][[:alpha:]]{2})?|finnish','fi.lang.php','fi','Suomi'),'sv' => array('sv([-_][[:alpha:]]{2})?|swedish','sv.lang.php','sv','Swedish'),'tr' => array('fi([-_][[:alpha:]]{2})?|turkish','tr.lang.php','tr','T&uuml;rk&ccedil;e'));
	
	$options=array();
	foreach ($languages as $lang => $desc) {
		$options[$lang]=$desc[3];
	}
	$bookings_options[]=array("name" => "Language","desc" => "Bookings supports multiple languages, here you can select the language of your choice. The language will affect related settings such as the date format used to display dates. If you see blank screens after changing the language from English, please contact us as some of the language files have some encoding issues. If you see missing translations, please send us the translations and we'll incorporate them into a new version. And if you can't see your language but are interested to add it, contact us so we can see how we can work something out.","id" => $bookings_shortname . "_lang","options" => $options,"std" => get_locale(),"type" => "selectwithkey");

	//business type
	$bookings_options[]=array("name" => "Business type","desc" => "A short description of your business type, for example gym, medical practice, hotel, etc. This will help us improve our default configuration.","id" => "bookings_bustype","type" => "text");
	
	//CSS
	if (bookings_active()) $bookings_options[]=array("name" => "Custom styles","desc" => "You can use css custom styles here to overwrite the Bookings styles.","id" => $bookings_shortname . "_css","type" => "textarea");
	
	$bookings_options[]=array("name" => "Before you install","type" => "heading","desc" => '<div style="text-decoration:underline;display:inline;font-weight:bold">IMPORTANT:</div> Bookings uses web services stored on Zingiri\'s servers. In doing so, personal data is collected and stored on our servers. 
					This data includes amongst others your admin email address as this is used, together with the API key as a unique identifier for your account on Zingiri\'s servers.
					We have a very strict <a href="http://www.zingiri.com/privacy-policy/" target="_blank">privacy policy</a> as well as <a href="http://www.zingiri.com/terms/" target="_blank">terms & conditions</a> governing data stored on our servers.
					<div style="font-weight:bold;display:inline">By installing this plugin you accept these terms & conditions.</div>');
	
	return $bookings_options;
}

function bookings_admin_menu() {
	global $bookings_name, $bookings_shortname, $bookings;
	
	$bookings_options=bookings_options();
	
	if (isset($_GET['page']) && ($_GET['page'] == "bookings")) {
		if (isset($_REQUEST['action']) && 'install' == $_REQUEST['action']) {
			$_SESSION['bookings']['force_license_check']=true;
			delete_option('bookings_log');
			foreach ($bookings_options as $value) {
				if (isset($value['id']) && isset($_REQUEST[$value['id']])) {
					update_option($value['id'], $_REQUEST[$value['id']]);
				} else {
					delete_option($value['id']);
				}
			}
			update_option("bookings_version", BOOKINGS_VERSION);
			header("Location: admin.php?page=bookings&installed=true");
			die();
		}
	}
	
	if ((!get_option('bookings_region') || !get_option('bookings_version')) && current_user_can(BOOKINGS_ADMIN_CAP)) {
		add_menu_page($bookings_name, $bookings_name, BOOKINGS_USER_CAP, 'bookings', 'bookings_main');
		add_submenu_page('bookings', $bookings_name . ' - Setup', 'Setup', BOOKINGS_ADMIN_CAP, 'bookings', 'bookings_main');
	} else {
		if (current_user_can(BOOKINGS_ADMIN_CAP)) {
			add_menu_page($bookings_name, $bookings_name, BOOKINGS_USER_CAP, 'bookings', 'bookings_main');
			add_submenu_page('bookings', $bookings_name . ' - Setup', 'Setup', BOOKINGS_ADMIN_CAP, 'bookings', 'bookings_main');
			$my_admin_page=add_submenu_page('bookings', $bookings_name . ' - Manage', 'Manage', BOOKINGS_ADMIN_CAP, 'bookings_manage', 'bookings_manage');
			add_action('load-' . $my_admin_page, 'bookings_admin_add_help_tab');
		} else {
			add_menu_page($bookings_name, $bookings_name, BOOKINGS_USER_CAP, 'bookings', 'bookings_main');
		}
	}
}

function bookings_main() {
	global $bookings;
	
	if (!isset($_GET['zfaces'])) return bookings_admin();
	
	require (dirname(__FILE__) . '/includes/support-us.inc.php');
	
	echo '<div class="wrap">';
	echo '<div id="bookings" class="bookings aphps">';
	if (isset($bookings['output']['messages']) && is_array($bookings['output']['messages']) && (count($bookings['output']['messages']) > 0)) {
		echo '<div class="error">';
		foreach ($bookings['output']['messages'] as $msg) {
			echo $msg . '<br />';
		}
		echo '</div>';
	}
	if (isset($bookings['output']['mimetype']) && ($bookings['output']['mimetype'] == 'text/plain')) {
		while ( count(ob_get_status(true)) > 0 ) {
			ob_end_clean();
		}
		header('Content-Type: ' . $bookings['output']['mimetype']);
		header('Content-Disposition: attachment; filename="' . $bookings['output']['filename'] . '"');
		if (isset($bookings['output']['body'])) echo trim($bookings['output']['body']);
		die();
	} elseif (isset($bookings['output']['body'])) echo $bookings['output']['body'];
	
	echo '</div>';
	require (dirname(__FILE__) . '/includes/help.inc.php');
	
	bookings_admin_footer('bookings', 'bookings', 'bookings', BOOKINGS_VERSION, false);
	
	echo '</div>';
}

function bookings_admin() {
	global $bookings_name, $bookings_shortname;
	
	$controlpanelOptions=bookings_options();
	
	if (isset($_REQUEST['install'])) echo '<div id="message" class="updated fade"><p><strong>' . $bookings_name . ' settings updated.</strong></p></div>';
	if (isset($_REQUEST['error'])) echo '<div id="message" class="updated fade"><p>The following error occured: <strong>' . zingiri::form_sanitize($_REQUEST['error']) . '</strong></p></div>';
	require (dirname(__FILE__) . '/includes/support-us.inc.php');
	
	echo '<div class="wrap">';
	echo '<h2>';
	echo '<b>' . $bookings_name . '</b>';
	echo '</h2>';
	
	echo '<div class="cc-left">';
	$bookings_version=get_option("bookings_version");
	$submit=bookings_active() ? 'Update':'Connect';
	echo '<form method="post">';
	require (dirname(__FILE__) . '/includes/cpedit.inc.php');
	echo '<p class="submit">';
	echo '<input name="install" type="submit" class="button" value="' . $submit . '" />';
	echo '<input type="hidden" name="action" value="install" />';
	echo '</p>';
	echo '</form>';
	if ($bookings_version && get_option('bookings_debug')) {
		echo '<h2 style="color: green;">Debug log</h2>';
		echo '<textarea rows=10 cols=80>';
		$r=get_option('bookings_log');
		if ($r) {
			$v=$r;
			foreach ($v as $m) {
				echo date('H:i:s', $m[0]) . ' ' . $m[1] . chr(13) . chr(10);
				echo $m[2] . chr(13) . chr(10);
			}
		}
		echo '</textarea><hr />';
	}
	echo '</div>'; // end cc-left
	if (get_option('bookings_key') && current_user_can(BOOKINGS_ADMIN_CAP)) {
		echo '<div class="cc-right">';
		echo '<h3>Administration</h3>';
		echo '<p>The administration of Bookings is done either via the <a href="' . get_site_url() . '/wp-admin/admin.php?page=bookings_manage">Bookings > Manage</a> menu or via the <a href="' . bookings_url(false) . 'index.php?apikey=' . get_option('bookings_key') . '" target="_blank">Bookings Portal</a>.</p>';
		echo '<p>You can login with your Wordpress user <strong>' . bookings_login() . '</strong>.</p>';
		echo '<p>If it is the first time you login, use password <strong>admin</strong>. You will then be asked to choose a new password.</p>';
		echo '</div>';
	}
	
	require (dirname(__FILE__) . '/includes/help.inc.php');
	
	if (bookings_active()) bookings_sharethelove('bookings', 'bookings', 'bookings', BOOKINGS_VERSION, false);
	
	bookings_admin_footer('bookings', 'bookings', 'bookings', BOOKINGS_VERSION, false);
	echo '</div>'; // end wrap
}

function bookings_manage() {
	$url=bookings_url(false) . 'index.php?apikey=' . get_option('bookings_key');
	echo '<iframe src="' . $url . '" id="bookings-frame"></iframe>';
}

function bookings_admin_add_help_tab() {
	$screen=get_current_screen();
	$content='<p>To manage Bookings, please login with user <strong>' . bookings_login() . '</strong>. If it is the first time you login, use password <strong>admin</strong>. You will then be asked to choose a new password. You can also go to the <a href="' . bookings_url(false) . 'index.php?apikey=' . get_option('bookings_key') . '" target="_blank">Bookings portal</a> to manage.</p>';
	$screen->add_help_tab(array('id' => 'bookings','title' => 'Bookings','content' => $content));
}

add_action('admin_menu', 'bookings_admin_menu');