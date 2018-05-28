<?php
class ConstantsManager {
	public function __construct($cms, $load=true){
		$this->cms = $cms;
		if ($load) {
			//$this->loadConstants();
			$this->loadSiteMap();
		}
	}
	public function loadSiteMap() {
		global $CMS_SITEMAP;

		//level of user access
		$access = $this->cms['admin'];

		$sql = "SELECT * FROM cms__sitemap WHERE access<='".$access."' ORDER BY id ASC ";//"
		$result = $this->cms['db']->query($sql);
		if($result->num_rows() > 0 ) {
			while ($page = $result->fetch_array(patDBC_TYPEASSOC)) {
				$sitemap[] = $page;
			}
			$CMS_SITEMAP = $this->populateSiteMap($sitemap);
		}
	}
	public function populateSiteMap($sitemap) {
		$ret = array();
		while(list(,$p) = each($sitemap)) {
			$ret[] = $p;
		}
		return $ret;
	}
}
?>