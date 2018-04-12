<?php
/**
 * Created by IntelliJ IDEA.
 * User: victor
 * Date: 5/18/11
 * Time: 4:55 PM
 * To change this template use File | Settings | File Templates.
 */
 
class SphinxSearch {
    private $ci;
    private $cfg;
    private $indexes;
    private $source2manager;
    private $search_scope = 'for_page';
    
    const MAX_SEARH_RESULT = 900;
    const MIN_OFFSET_SEARH_RESULT = 0;

    public function SphinxSearch($params) {       	  	     
       $this->setSearchScope($params);
       $this->inizialization();
    }       
    
    /**     
     * Search for page or autocomplete
     * @param string $search_scope
     */
    public function inizialization() {
    	switch($this->search_scope) {
    		case 'for_page':
    		 require_once './lib/sphinx/sphinxapi.php';
    		 $this->ci = & get_instance();    		 
    		 $this->ci->load->config('sphinx_search', TRUE);
    		 $this->cfg     = $this->ci->config->item('sphinx_search');
    		 $this->ci->load->helper('common/itirra_language_helper');
    		 break;
    		case 'for_autocomplete':
    		 require_once '../lib/sphinx/sphinxapi.php';
    		 require_once '../app/helpers/common/itirra_language_helper.php';
    		 require '../app/config/sphinx_search.php';
    		 $this->cfg = $config;
    		 break;
    	}
    	
    	$this->indexes = array_keys($this->cfg['indexes']);
    	$this->source2manager = array();
    	foreach ($this->cfg['indexes'] as $ind) {
    		$this->source2manager[$ind['sql_attr_uint_value']] = $ind['manager'];
    	}
    }
    
    public function setSearchScope($params) {
    	 if(is_array($params)) {   	     
    	 	 $this->search_scope = $params['search_scope'];
    	 } else {
    	   $this->search_scope = $params;
    	 }
    }
    
    public function getSourceManagers() {
    	return $this->source2manager;
    }

    /**
     * Do search.
     * @throws Exception
     * @param  $query
     * @param string $index
     * @param int $page
     * @param int $perPage
     * @return bool
     */
    public function search($query, $index = "*", $page = 0, $perPage = 20) {
    	 
    	$cl = new SphinxClient();
    	$cl->SetServer($this->cfg['ip'], $this->cfg['port']);

    	// TODO think about pagination results
    	if($this->search_scope === 'for_page') {
    		  $cl->setLimits(self::MIN_OFFSET_SEARH_RESULT, self::MAX_SEARH_RESULT);
    	} else {
    		  $cl->setLimits($page, $perPage);
    	}
    	        	    
    	$cl->SetRankingMode(SPH_RANK_PROXIMITY_BM25);
    	$cl->SetMatchMode(SPH_MATCH_EXTENDED);
    	$cl->SetSortMode(SPH_SORT_RELEVANCE);
    	if (is_array($w = $this->prepareFieldWeights($index))) {
    	 $cl->SetFieldWeights($w);
    	}

    	//$query  = $this->compareAsLikeQuery($query);
    	$query  = $this->GetSphinxKeyword($query);
    	$result = $cl->Query($query, $index);
    
    	if ( $result === false ) {
    		return false;
    	}

    	else if ($cl->GetLastWarning()) {
    		return false;
    	} else {    		
    		if(empty($result['matches'])) {
    			return NULL;
    		}
    		return $result;
    	}
    }

    /**
     * Gets field weights from config.
     * Group weights if search is in multiple indexes
     * @param  $index
     * @return
     */
    private function prepareFieldWeights($index) {
        // set weights for index
        // TODO make for '*' and csv of indexes

        $indexes = array();
        if ($index == "*") {
            $indexes = $this->indexes;
        } else {
            $index = preg_replace("/,/", "", $index);
            $index = preg_replace("/  /", " ", $index);
            $indexes = mb_split(" ", $index);
        }
        $weights = array();
        foreach ($indexes as $subIndex) {
            if (isset($this->cfg['indexes'][$subIndex]) && !empty($this->cfg['indexes'][$subIndex]['weights']))
                $weights = array_merge($weights, $this->cfg['indexes'][$subIndex]['weights']);
        }
        return $weights;
    }

    /**
     * ADD stars to query according to prefix_len
     * @param  $query
     * @return string
     */
    private function compareAsLikeQuery($query) {
    	if($query != '') {
        $array = explode(" ", $query);
        $parsedQuery = "";
        foreach ($array as $ind=>$term) {
            $parsedQuery .= $term ;
            if ($ind == count($array) - 1 && mb_strlen($term, "UTF-8") > $this->cfg['prefix_len']) {
                $parsedQuery  .=  "* ";
            } else {
                $parsedQuery .=  " ";
            }
          }        
        return trim($parsedQuery);
    	}
    }
    
  private function GetSphinxKeyword($sQuery) {    
    $aRequestString=preg_split('/[\s,-]+/', $sQuery, 5);
    if ($aRequestString) {
        $aKeyword = array();
        foreach ($aRequestString as $sValue) {                        	            
                $aKeyword[] .= "(".$sValue." | ". to_translit($sValue)." | ".from_translit($sValue).")";                
        }
        $sSphinxKeyword = implode(" & ", $aKeyword);
    }
    return $sSphinxKeyword;
	}    
}
