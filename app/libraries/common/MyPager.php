<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * My Pager
 *
 * With blackjack and hookers
 *
 */
class MyPager {

  /** Total results count */
  private $_totalResults = NULL;

  /** Page */
  private $_page;

  /** Per page limit */
  private $_perPage;

  /** Last page */
  private $_lastPage = NULL;


  /**
   * Constructor
   * @param $totalResults
   * @param $page
   * @param $perPage
   */
  public function MyPager($page, $perPage, $totalResults = NULL) {
    $this->_page = $page;
    $this->_perPage = $perPage;
    if($totalResults !== NULL) {
      $this->setTotalResults($totalResults);
    }
  }


  /**
   * setTotalResults
   * @param $totalResults
   */
  public function setTotalResults($totalResults) {
    $this->_totalResults = $totalResults;
    $this->_lastPage = ceil($this->_totalResults / $this->_perPage);
  }


  /**
   * haveToPaginate
   *
   * Return true if it's necessary to paginate or false if not
   *
   * @return bool true if it is necessary to paginate, false otherwise
   */
  public function haveToPaginate() {
    $this->validateTotalResults();
    return $this->_totalResults > $this->_perPage;
  }


  /**
   * getPage
   *
   * Returns the current page
   *
   * @return int current page
   */
  public function getPage() {
    return $this->_page;
  }

  /**
   * setPage
   *
   * Defines the current page and automatically adjust offset and limits
   *
   * @param $page       current page
   * @return void
   */
  public function setPage($page)
  {
    $this->_page = $page;
  }

  /**
   * getLastPage
   *
   * Returns the last page (total of pages)
   *
   * @return int last page (total of pages)
   */
  public function getLastPage() {
    $this->validateTotalResults();
    return $this->_lastPage;
  }

  /**
   * _setLastPage
   *
   * Defines the last page (total of pages)
   *
   * @param $page       last page (total of pages)
   * @return void
   */
  public function setLastPage($page)
  {
    $this->_lastPage = $page;

    if ($this->getPage() > $page) {
      $this->setPage($page);
    }
  }

  /**
   * getNextPage
   *
   * Returns the next page
   *
   * @return int next page
   */
  public function getNextPage() {
    return min($this->getPage() + 1, $this->getLastPage());
  }


  /**
   * getNumResults
   *
   * Returns the number of results found
   *
   * @return int        the number of results found
   */
  public function getNumResults() {
    $this->validateTotalResults();
    return $this->_totalResults;
  }


  /**
   * getFirstIndice
   *
   * Return the first indice number for the current page
   *
   * @return int First indice number
   */
  public function getFirstIndice() {
    return ($this->_page - 1) * $this->_perPage + 1;
  }


  /**
   * getRange
   *
   * Builds and return a Doctrine_Pager_Range_* based on arguments
   *
   * @param string $rangeStyle Pager Range style
   * @param array $options     Custom subclass implementation options.
   *                           Default is a blank array
   * @return Doctrine_Pager_Range Pager Range
   */
  public function getRange($rangeStyle, $options = array()) {
    $class = 'Doctrine_Pager_Range_' . ucfirst($rangeStyle);

    return new $class($options, $this);
  }


  /**
   * getExecuted
   *
   * Returns the check if Pager was already executed at least once
   *
   * @return boolen        Pager was executed
   */
  public function getExecuted() {
    return TRUE;
  }


  /**
   * getFirstPage
   *
   * Returns the first page
   *
   * @return int        first page
   */
  public function getFirstPage() {
    return 1;
  }


  /**
   * getOffset
   */
  public function getOffset() {
    return ($this->_page - 1) * $this->_perPage;
  }


  /**
   * validateTotalResults
   * @throws Exception
   */
  public function validateTotalResults() {
    if(!is_int($this->_totalResults)) {
      throw new Exception('MyPager::totalResults not set!');
    }
  }

}