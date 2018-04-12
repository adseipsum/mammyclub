<?php defined('BASEPATH') or exit('No direct script access allowed');

class WordHTMLCleaner {
	
	private $text = '';
	private $config = array('empty_tags'  			=> 'p|em|strong|div|a',
													'remove_tags' 			=> '',
													'replacement_tags' 	=> array ('i' => 'em', 'b' => 'strong'),
													'remove_attributes' => array ('class'   => "",
																												'lang'    => "",
																												//'style'   => array('img' => 'width, height ,src'),																												
																												'size'    => "",
																												'face'    => "",
																												'[ovwxp]' => ""	
																												));
	
	public function WordHTMLCleaner() {
	}	

	public function setText($text) {
		$this->text = $text;		
	}
	
	public function setConfig($config) {
		if(empty($config)) {
			$this->config = $config;
	 }
	}
	
	public function getCleanText() {
		$this->cleaningProcess();		
		return $this->text;
	}	
	
	/**	 
	 * remove all tags with empty value
	 */
	private function removeTagsWithEmptyVal() {		
		if(!empty($this->config['empty_tags'])) {
			$empty_tags = $this->config['empty_tags'];			
			$empty_tags = explode('|', $empty_tags);						
			foreach ($empty_tags as $empty_tag) {
				 $this->removeTagProccessWithEmptyVal($empty_tag);				 												 							
			}			
		}		 			 	
	}
	
	/**	 
	 * remove one tag with empty value
	 * Example: remove <p><em><br /></em></p>   
	 * @param string $tag
	 */
	private function removeTagProccessWithEmptyVal($tag) {							
		$pattern = '#<' . $tag . '[^>]*(?:/>|>(?:\s)</' . $tag . '>)#im';			
		$this->text = preg_replace($pattern, '', $this->text);		
	}
	
	/**	 
	 * remove attributes   
	 */
	private function removeAttributes() {
		if(!empty($this->config['remove_attributes'])) {
		  $remove_attributes = $this->config['remove_attributes'];
		  foreach ($remove_attributes as $attibute => $tags) {
		  	if(!empty($tags)) {		  				  	
		  		  foreach ($tags as $tag => $attibutes) {		  		  	
		  		  	if($tag ==  "img") {
		  		  		$this->text = $this->stripAttr($this->text, $tag, array('style', 'src', 'width', 'height'));
		  		  }		  		  
		  		} 		  		 
		  	} else {		  	
		  		$pattern = '/(' . $attibute .')=".*?"/';
					$this->text = preg_replace($pattern, "", $this->text);
		  	}		  	
		  }		  
		}
	}
	
	/*
	 * remove all tags and attributes
	 */
	public function removeUnsafeAttributesAndGivenTags($input, $validTags = '') {
    $regex = '#\s*<(/?\w+)\s+(?:on\w+\s*=\s*(["\'\s])?.+?\(\1?.+?\1?\);?\1?|style=["\'].+?["\'])\s*>#is';
    return preg_replace($regex, '<${1}>',strip_tags($input, $validTags));
	}
	
	/**
	 * 	 
	 * @param string $msg
	 * @param string $tag
	 * @param string $attr
	 * @param string $suffix
	 */
  private function stripAttr($msg, $tag, $attr, $suffix = "")  {
   $lengthfirst = 0;
   while (strstr(substr($msg, $lengthfirst), "<$tag ") != "") {
    $tag_start = $lengthfirst + strpos(substr($msg, $lengthfirst), "<$tag ");
    $partafterwith = substr($msg, $tag_start);
    $img = substr($partafterwith, 0, strpos($partafterwith, ">") + 1);    
    $img = str_replace(" =", "=", $img);
    $out = "<$tag";
    for($i = 0; $i < count($attr); $i++) {
    	if (empty($attr[$i])) {
    		continue;
    	}

    	$pos	= strpos($img, $attr[$i] . '=');    	
    	if(strpos($img, '" ', strpos($img, $attr[$i] . "=")) === false) {    		
    		$long_val =	strpos($img, ">", strpos($img, $attr[$i] . "=")) - ($pos + strlen($attr[$i]) + 1);
    	} else {    	 
    		$long_val = strpos($img, '" ', strpos($img, $attr[$i] . "=")) - ($pos + strlen($attr[$i]) );
    	}

    	$val = substr($img, strpos($img, $attr[$i] . "=") + strlen($attr[$i]) + 1, $long_val);
    	if (!empty($val)) {
    		$out .= " " . $attr[$i] . "=" . $val;
    	}
    }

    if (!empty($suffix)) {
    	$out .= " " . $suffix;
    }

    $out .= ">";
    $partafter = substr($partafterwith, strpos($partafterwith, ">") + 1);
    $msg = substr($msg, 0, $tag_start) . $out . $partafter;
    $lengthfirst = $tag_start + 3;
   }
   return $msg;
  }
   
	/**	 
	 * remove tags
	 */				
	private function stripSelectedTags($stripContent = false) {
		if(!empty($this->config['remove_tags'])) {
			$remove_tags = $this->config['remove_tags'];
			$tags = explode('|', $remove_tags);
			preg_match_all("/<([^>]+)>/i", $tags, $allTags, PREG_PATTERN_ORDER);
			foreach ($allTags[1] as $tag) {
				$replace = "%(<$tag.*?>)(.*?)(<\/$tag.*?>)%is";
				$replace2 = "%(<$tag.*?>)%is";
				if ($stripContent) {
					$this->text = preg_replace($replace,'',$this->text);
					$this->text = preg_replace($replace2,'',$this->text);
				}
				$this->text = preg_replace($replace,'${2}',$this->text);
				$this->text = preg_replace($replace2,'${2}',$this->text);
			}
			return $str;
		}
  }
		
	/**	 
	 * replace tags
	 */
	private function replaceSelectedTags() {		
		if(!empty($this->config['replacement_tags'])) {		 
		 $replacement_tags = $this->config['replacement_tags'];		 		
		  foreach ($replacement_tags as $current_tags => $replacement) {		  	 
		  	$this->text = str_replace(array( "<" . $current_tags . ">", "</" . $current_tags . ">"),
		  	 													array( "<" . $replacement . ">", "</" . $replacement . ">"),  
		  	 													$this->text);		  	 
		  }		  			
		}		
	}
	
	//curl -d html=<Dirty HTML>
	private function api() {				
		$url = "http://wordoff.org/api/clean";
		$data = array('html' => $this->text);		
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_POST, TRUE);		
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);		
		curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);		
		$this->text = curl_exec($c);
		curl_close($c);						
	}	
	
	private function addReference() {
		$this->text = preg_replace("{<img\\s*(.*?)src=('.*?'|\".*?\"|[^\\s]+)(.*?)\\s*/?>}ims", '<a href=$2 class="fancybox" rel="group"><img $1src=$2 $3/></a>', $this->text);		
	}
	
	private function deleteEmptyReference() {
		$this->text = preg_replace('{<a  href="(\'.*?\'|\".*?\"|[^\\s]+)" rel="group">}ims', '', $this->text);			
	}
		
	/**	 
	 * cleaning text process
	 */
	private function cleaningProcess() {						
		  $this->removeAttributes();
			$this->removeTagsWithEmptyVal();
			$this->replaceSelectedTags();
			$this->stripSelectedTags();
			$this->addReference();
			$this->deleteEmptyReference();
		/* $this->api(); */
	}
}