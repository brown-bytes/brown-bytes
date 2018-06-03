<?php

	define('INCLUDE_FRONTEND', 'frontend');
	define('INCLUDE_LOGGEDIN','loggedin');
	define('INCLUDE_SESSION','session');

class Template {

	public $title = "";
	public $metas = array();
	public $description = "";


	public static $jsContent = array();
	public static $cssContent = array();

	//These are the css files for the entire website
	public static $defaultCSS = array(
		array(
			'file' => 'bootstrap.css', //bootstrap.min.css
			'path' => '/css/bootstrap/',
			'include'=>true,
			'includeType' => INCLUDE_FRONTEND,
			'mediaRule' => ' '
		),
		array(
			'file' => 'fontawesome-all.min.css',
			'path' => '/img/icons/fontawesome-free-5.0.9/web-fonts-with-css/css/',
			'include'=>true,
			'includeType' => INCLUDE_FRONTEND,
			'mediaRule' => ' '
		),
		array(
			'file' => 'editor2.css',
			'path' => '/css/',
 			'include'=>true,
			'includeType' => INCLUDE_SESSION
		),
		array(
			'file' => 'style2.css',
			'path' => '/css/',
			'include'=>true,
			'includeType' => INCLUDE_FRONTEND,
			'mediaRule' => ' '
		),
		array(
			'file' => 'ui.all.css',
			'path' => '/css/ui/base/',
			'include'=>true,
			'includeType' => INCLUDE_LOGGEDIN
		),
		array(
			'file' => 'cms.css',
			'path' => '/css/',
			'include'=>true,
			'includeType' => INCLUDE_LOGGEDIN
		),
		array(
			'file' => 'validator-engine.css',
			'path' => '/css/validator/',
			'include'=>true,
			'includeType' => INCLUDE_LOGGEDIN
		),
	);

	public static $defaultJS = array(
		array(
			'file' => 'validationEngine.js',
			'path' => '/js/validators/',
			'include'=>true,
			'pos'=>'footer',
			'includeType' => INCLUDE_LOGGEDIN
		),
		array(
			'file' => array(
				array('file' => "jquery.bgiframe.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_LOGGEDIN),
				array('file' => "jquery.dimensions.js", 'include'=>true, 'pos'=>'footer',  'includeType' => INCLUDE_LOGGEDIN),
				array('file' => "jquery.tooltip.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_LOGGEDIN),
			),
			'path' => '/js/tooltip/',
			'includeType' => INCLUDE_LOGGEDIN
		)
		,
		array(
			'file' => array(
				array('file' => "cms.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_FRONTEND),
				array('file' => "editor.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_LOGGEDIN),
				array('file' => "loggedin.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_SESSION),
				array('file' => "ajaxFileUpload.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_LOGGEDIN),
				array('file' => "jquery.alerts.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_FRONTEND),
				array('file' => "slick.js", 'include'=>true, 'pos'=>'footer', 'includeType'=> INCLUDE_FRONTEND),
				array('file' => "jquery.mCustomScrollbar.concat.min.js", 'include'=>true, 'pos'=>'footer', 'includeType'=> INCLUDE_FRONTEND)
			),
			'path' => '/js/',
			'includeType' => INCLUDE_LOGGEDIN
		),
		array(
			'file' => 'ckeditor.js',
			'pos'=>'footer',
			'include'=>true,
			'path' => '/wysiwyg_editor/',
			'includeType' => INCLUDE_LOGGEDIN
		),
		array(
			'file' => array(
				array('file' => "bootstrap.min.js", 'include'=>true, 'pos'=>'footer', 'includeType' => INCLUDE_FRONTEND, 'excludePage' => '/paid2')
			),
			'path' => '/js/bootstrap/',
			'includeType' => INCLUDE_LOGGEDIN
		),
		array(
			'file' => 'layoutv2.js',
			'cache' => false,
			'pos'=>'footer',
			'include'=>true,
			'path' => '/js/',
			'includeType' => INCLUDE_FRONTEND
		),
		array(
			'file' => 'prefixfree.min.js',
			'pos'=>'footer',
			'include'=>true,
			'path' => '/js/',
			'includeType' => INCLUDE_FRONTEND
		)
	);

	private static $hasRights;

	public static function initAccess()
	{
		self::$hasRights =  AuthenticationChecks::adminOnly();
		if(!self::$hasRights)
			self::$hasRights = AuthenticationChecks::hasRight();
	}

	public static function Factory($template) {
		switch ($template) {
			case "tbs":
				require_once($_SERVER['DOCUMENT_ROOT']."/cms/templates/tbs/template.php");
				return new CMSTemplate();
			case "blank":
				require_once($_SERVER['DOCUMENT_ROOT']."/cms/templates/blank/template.php");
				return new CMSTemplate();
			default:
				throw new Exception("invalid template");
		}

	}

	public function buildHeader($params = array()) {
		global $_CMS,$prompt_login,$er;

		?><!DOCTYPE html>
<html>
  <head>
	 <!-- Facebook Pixel Code -->
		<script>
		  !function(f,b,e,v,n,t,s)
		  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		  n.queue=[];t=b.createElement(e);t.async=!0;
		  t.src=v;s=b.getElementsByTagName(e)[0];
		  s.parentNode.insertBefore(t,s)}(window, document,'script',
		  'https://connect.facebook.net/en_US/fbevents.js');
		  fbq('init', '2115050902061933');
		  fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		  src="https://www.facebook.com/tr?id=2115050902061933&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->

   <meta charset="UTF-8">

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-21603124-1', 'auto');

	</script>
			<?php
				echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'; 	//No viewport? Really?
				echo self::includeDefaultJS('header');
				echo self::includeDefaultCSS();


				if (!isset($params['title'])) {
					$title = CMS::getPageTitle();
				} else {
					$title = $params['title'];
				}
			//if ($title == 'Team BlackSheep - Serious Toys'){
				echo '<script type="text/javascript" src="/js/jquery-1.11.3.min.js" ></script>';
				//echo '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js" async></script>';
				if(CMS::isAdmin() || CMS::isVendor()){ echo '<script type="text/javascript" src="/js/ui/jquery-ui.min.js" ></script>';}
				else{
					echo '<script type="text/javascript" src="/js/ui/jquery-ui.min.js" async></script>';
				}
				//echo '<script src="/js/jquery-migrate-1.2.1.min.js" defer></script>';
			//}else{
				//echo '<script src="/js/jquery-1.11.3.min.js"></script>';
			//	echo '<script src="/js/jquery-migrate-1.2.1.min.js"></script>';
	//echo '<script src="/js/ui/jquery-ui-1.7.2.peter.js"></script>';
			//}

			?><title><?php echo $title; ?></title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta name="description" content="%description" />
			<meta name="keywords" content="%metas" />
		</head>
		<body>
		<?php
		echo self::logoutPanel($prompt_login);

	}


	public static function getEditorIcons($id, $type='add')
	{
		if (AuthenticationChecks::adminOnly() == true)
			return '<a href="#" id="'.$id.'"><img src="/img/icons/'.$type.'.png"></a>';

	}

	public static function logoutPanel($pl) {
		global $_CMS;

		self::initAccess();

		$str = "";
		$ref_now = isset($_REQUEST['ref']) ? $_REQUEST['ref'] : "shop";

		// if we have a session
		if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
			// we dont want to show the logout shit at the top right it looks ugly
			// $str = "<div id=\"logout\"><div id=\"InnerLogout\"><b>".$_CMS['user']."</b>&nbsp;<a href=\"".$_SERVER['SCRIPT_NAME']."?a=logout\">Log out</a></div></div>";
			$mysql = "UPDATE `cms__user` SET `lastlogin`='".date('Y-m-d H:i:s')."' WHERE `user`='".$_CMS['user']."'";
			$a = $_CMS['db']->query($mysql);

		// we don't have a session but we have set a $pl flag
		} else if (!isset($_SESSION['user']) && $pl) {
			$str .= "<div id=\"login\" style=\"background-color:red;\"><form method=\"post\" action=\"/\"><input type=\"hidden\" name=\"a\" value=\"login\"><input type=\"hidden\" name=\"action\" value=\"login\"><input type=\"hidden\" name=\"ref\" value=\"".htmlentities($ref_now)."\" /><label for=\"user\">User: </label><input type=\"text\" name=\"user\" id=\"user\" /><br /><label for=\"pass\">Password:</label> <input type=\"password\" name=\"pass\" id=\"pass\" /><br /><input type=\"submit\" value=\"Log in\" /> or <a href=\"#register\" id=\"register\">Apply</a></form><a href=\"#forgotpass\" id=\"forgotpass\">Forgot Password</a></div><script type=\"text/javascript\">$(document).ready(function() {\$('#login').slideDown().css('z-index',9999);});</script>";

		// just a blank login link
		} else if (!isset($_SESSION['user'])) {
			$str .= "<div id=\"login\"><form method=\"post\" action=\"/\"><input type=\"hidden\" name=\"a\" value=\"login\"><input type=\"hidden\" name=\"action\" value=\"loginUser\"><input type=\"hidden\" name=\"ref\" value=\"".htmlentities($ref_now)."\" />";
			$str .= "<table cellspacing=0 cellpadding=0 id=\"login_divider\"><tr><td><label for=\"user\">Email:</label></td><td><label for=\"pass\">Password:</label></td><td rowspan=2 valign=\"bottom\"><input type=\"submit\" value=\"Log in\" /></td></tr>";
			$str .= "<tr><td><input type=\"text\" name=\"user\" id=\"user\" /></td><td><input type=\"password\" name=\"pass\" id=\"pass\" /></td></tr>";
			$str .= "<tr><td><a style=\"color:white;font-size:75%\" href=\"#\" id=\"forgotpass\">Forgot Password</a></td></tr>";
			$str .= "</table>";
			$str .= "</form></div><script type=\"text/javascript\">\$(document).ready(function() {
				\$('#header_bar a.login').click(function(e){
					e.preventDefault();
					\$('#forgot_password_form').remove();
					\$('#login').css(login_setting);
					\$('#login').toggle();
				//}).mouseover(function() {
				//	\$('#login').toggle(false);
				});

			});</script>";
		}
		$str .= "<div>";
		return $str;
	}

	public function buildFooter($params = array()) {
		global $_CMS,$_CONSTANTS;

		echo self::includeDefaultJS('footer');
		//echo self::includeDefaultCSS();
		//if (!CMS::hasLogin()) echo "<script src='/js/ui/jquery-1.11.4.ui.min.js'></script>";
		?>
		<!-- SIDEBAR & Panels -->
		    <!-- jQuery Custom Scroller CDN -->
		    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

		    <script type="text/javascript">
		        $(document).ready(function () {
		            $("#sidebar").mCustomScrollbar({
		                theme: "minimal"
		            });

		        	$("#menu_scroll").height($(window).height());

		            $('#dismiss, .overlay').on('click', function () {
		                $('#sidebar').removeClass('active');
		                $('.overlay').fadeOut();
		                $('html, body').css('overflowY', 'auto');
		                $('body').unbind('touchmove');
		            });

		            $('#sidebarCollapse').on('click', function () {
		            	closeCollapse();
		                $('#sidebar').addClass('active');
		                $('.overlay').fadeIn();
		                $('.collapse.in').toggleClass('in');
		                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
		                $('html, body').css('overflowY', 'hidden');
		                $('#sidebar').css('overflowY', 'auto');
		                $('#sidebar').unbind('touchmove');
		                $('body').bind('touchmove', function(e){e.preventDefault()});
		                $('#menu_scroll').on('touchmove', function (e) {e.stopPropagation();});
		            });

		            $('#collapseSearch').on('show.bs.collapse', function () {
		                //alert($('#search'));
		            	$('#search').focus();
		            });

		            setPanelPos();

		            setCartIconQty(<?php echo (empty($_CMS['s']->get("cart")) ? 0 : count($_CMS['s']->get("cart"))); ?>);

		        });

				function setCartIconQty(i) {
					if (i <= 0) {
		            	$('div#cart_qty').removeClass('cart_qty');
		            } else {
		            	$('#cart_qty').attr('data-count',i);
		            	$('div#cart_qty').addClass('cart_qty');
		            }
				}

				function closeSlidebar() {
					closeCollapse();
	                $('#sidebar').removeClass('active');
	                $('.overlay').fadeOut();
	                $('html, body').css('overflowY', 'auto');
	                $('body').unbind('touchmove');
	            }

				function closeCollapse() {
	                var $allPanel = $('#allPanel');
					$allPanel.find('.collapse.show').collapse('hide');
	            }

	            function showPanel(i) {
	            	$('html,body').animate({
				        scrollTop: 0
				    }, 500);

				    $('#'+i).collapse('show');
	            }

	            function gotoTop() {
	            	$('html,body').animate({
				        scrollTop: 0
				    }, 500);
	            }

	            function setPanelPos() {
	            	if ($(window).width() > 768) {
	            		$('#allPanel').offset({left:Math.min($(window).width(), 1202)+(Math.max(($(window).width()-1202)/2,0))-$('#allPanel').width()});
	        		}
	            	//$('#allPanel').offset({left:Math.min($(window).width(), 1202)+(Math.max(($(window).width()-1202)/2,0))-$('#allPanel').width()});
	            }
	            $(window).on('resize', function(){
				    //closeCollapse();
		    		setPanelPos();
		    		if ($(window).width() <= 767) {
						$('#product_detail').width(window.innerWidth);
						$('#category_listing_detail').width(window.innerWidth);
						//$('#product_detail').width(100%);
					} else {
						$('#product_detail').width(Math.min($('#product').width() - Math.max($('#navigation').width(),300),900));
						$('#category_listing_detail').width(Math.min($('#category_listing').width() - Math.max($('#navigation').width()-10,300),900));
					}
				});


				// Youtube set auto width
				$(function() {
					// Find all YouTube videos
					var $allVideos = $("iframe[src^='http://www.youtube.com']"),
					    // The element that is fluid width
					    $fluidE = $("#product_text"),
					    $fluidEl = $("#product"),
					    $fluidE2 = $("#navigation");
					// Figure out and save aspect ratio for each video
					$allVideos.each(function() {
						$(this)
							.data('aspectRatio', this.height / this.width)
							// and remove the hard coded width/height
							.removeAttr('height')
							.removeAttr('width');
					});

					// When the window is resized
					// (You'll probably want to debounce this)
					$(window).resize(function() {
						var newWidth = $fluidE.width();
						//var newWidth = Math.min($(window).width(), $fluidEl.width())-$fluidE2.width()-30;
						// Resize all videos according to their own aspect ratio
						$allVideos.each(function() {
							var $el = $(this);
							$el
								.width(newWidth)
								.height(newWidth * $el.data('aspectRatio'));
						});
					// Kick off one resize to fix all videos on page load
					}).resize();
				});

		    </script>
    	<!-- END SIDEBAR & Panels -->
    	<?php
		if (AuthenticationChecks::hasRight() ||  AuthenticationChecks::adminOnly()) {
		?>
		 <div style="display: none;" id="constant_template">
			<div class="x-cms-constant-row"><div class="x-cms-constant-key"><input type="text" /><img src="/img/icons/delete.png" class="x-cms-constant-delete" /></div>
					<?php while (list($k,$v) = each($_CONSTANTS['CMS_LANGUAGES'])) {?>
						<div class="x-cms-constant-value" x-cms-lang="<?php echo $k;?>"<?php echo (CMS::isAdmin() ? "x-cms-active=\"".(in_array($k,$_CONSTANTS['CMS_LIVE_LANGUAGES']) ? 1 : 0)."\"" : "");?>><label class='textInputConstants'><?php echo $v;?></label><input type="text" class="text ui-widget-content ui-corner-all" /></div>
					<?php } reset($_CONSTANTS['CMS_LANGUAGES']); ?>
			</div>
			<div id='vcms-edit-constants'> </div>
		</div><?php
		}
		?><script type="text/javascript">var CMS_LANG = '<?php echo $_CMS['lang'];?>';var CMS_AJAX_URL = '<?php echo addslashes($_SERVER['REQUEST_URI']);?>';</script><?php

	}

	public static function includeDefaultJS($header = true)
	{
		self::includeJS(self::$defaultJS, $header);

		return implode("\n", self::$jsContent);
	}

	public static function includeDefaultCSS($header = true)
	{
		self::includeCSS(self::$defaultCSS);
		if($header == true) {
			return implode("\n", self::$cssContent);
		}
	}

	public static function includeJS($jsArr, $pos, $path = null) {
		global $_CMS;
		$ver_num = "1.2";
		$str = "";
		if (count($jsArr) >= 0 ) {
			self::initAccess();

			foreach($jsArr as $v)
			{
				if (isset($v['file']) && is_array($v['file'])) {
					self::includeJS($v['file'], $pos,  $v['path']);
				}
				else {
					$excludePage = (isset($v['excludePage']))?$v['excludePage']:'';

					if(isset($path))
						$v['path'] = $path;

					if( (($excludePage != parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) || strlen($excludePage) == 0) && isset($v['includeType']) && (($v['includeType'] == INCLUDE_FRONTEND) || ($v['includeType'] == INCLUDE_LOGGEDIN && self::$hasRights) || ($v['includeType'] == INCLUDE_SESSION && ((isset($_SESSION['user']) && $_SESSION['user'] !="") || self::$hasRights)))) {

						if(file_exists($_SERVER['DOCUMENT_ROOT'].$v['path'].$v['file']) && $v['include'] == true) {
							if($pos == $v['pos']) {
								//self::$jsContent .= file_get_contents($_SERVER['DOCUMENT_ROOT'].$v['path'].$v['file']);
								CMS::$loaded['js'][] = $v['path'].$v['file'];

								self::$jsContent[] = '<script src="'.$v['path'].$v['file'].(isset($v['cache']) && !$v['cache'] ? "?timeout=".microtime(true) : "").'?v='.$ver_num.'" type="text/javascript" defer></script>';
							}
						}
					}
				}
			}
		}
		return $str;
	}

	public static function includeCSS($cssArr, $path = null) {
		global $_CMS;
		$ver_num = "1.2.1";
		$str = "";
		if(count($cssArr) >= 0 ) {
			self::initAccess();
			foreach($cssArr as $k=>$v)
			{
				if(isset($v['file']) && is_array($v['file'])) {
					self::includeCSS($v['file'], $v['path']);
				}
				else {
					$excludePage = (isset($v['excludePage']))?$v['excludePage']:'';

					if(isset($path))
						$v['path'] = $path;

					if( (($excludePage != parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) || strlen($excludePage) == 0) && isset($v['includeType']) && (($v['includeType'] == INCLUDE_FRONTEND) || ($v['includeType'] == INCLUDE_LOGGEDIN && self::$hasRights) || ($v['includeType'] == INCLUDE_SESSION && (isset($_SESSION['user']) && $_SESSION['user'] !="") || (isset($_REQUEST['a']) && $_REQUEST['a'] == "prompt_login")))) {

						if(file_exists($_SERVER['DOCUMENT_ROOT'].$v['path'].$v['file']) && $v['include'] == true) {
							//self::$cssContent .= file_get_contents($v['path'].$v['file']);
							CMS::$loaded['css'][] = $v['path'].$v['file'];
							$s = '<link rel="stylesheet" href="'.$v['path'].$v['file'].'?v='.$ver_num.'" type="text/css" ';
							//For mediarules if there are any
							if(isset($v['mediaRule'])) $s .= 'media="'.$v['mediaRule'].'"';
							$s .= '/>';
							self::$cssContent[] = $s;
						}
					}
				}
			}
		}
		return $str;
	}

	private static function writeTempJS_CSSFile($path, $filename, $data)
	{
	   if(file_exists($path.$filename)) //If file is alredy there...delete that.
			@unlink($path.$filename);

		$fileinst = fopen($path.$filename, 'w') or die("can't open file");
		fwrite($fileinst, $data);
		fclose($fileinst);

		return true;
	}

	protected function addMetas($arr) {
		foreach ($arr as $v) {
			$this->metas[] = $v;
		}
	}
	protected function startPublish() {
		ob_start();
	}
	public function publish() {
		$str = str_replace("%1",$this->title,ob_get_contents());
		ob_end_clean();
		$str = str_replace("%metas",implode(", ",$this->metas),$str);
		$str = str_replace("%description",$this->description,$str);

		echo $str;
	}

	protected function dump($col) {
		global $er;
		if ($col['type'] == "plugin") {
			$plugin = CMSPlugin::factory($col['name'],$col);
			if ($plugin) {
				$str = $plugin->show($col);
				if (!empty($plugin->title)) {
					$this->title = $plugin->title;
				}

				if (!empty($plugin->metas)) {
					$this->addMetas($plugin->metas);
				}

				if (!empty($plugin->description)) {
					$this->description = $plugin->description;
				}

				return $str;
			}
			return "Plugin not found";
		} else if ($col['type'] == "er") {
			return "<div class=\"content-er\">".$er->show($col['name'])."</div>";
		}
	}
}
?>