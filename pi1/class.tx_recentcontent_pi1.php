<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Philip Oliveira Almeida <philip.almeida@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Menu of recently updated pages' for the 'recentcontent' extension based on Steffen Muller sm_recentcontent.
 *
 * @author	Philip Oliveira Almeida <philip.almeida@gmail.com>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_recentcontent_pi1 extends tslib_pibase {
	var $prefixId = 'tx_recentcontent_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_recentcontent_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'recentcontent';	// The extension key.
	var $pi_checkCHash = TRUE;


	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website (Menu)
	 */
	function main($content,$conf)	{

		$this->conf = $conf; //store configuration
		#print_r($this->conf);

		// Get the PID from which to make the menu.
		if ($this->cObj->data['pages']) {
			// If a page is set as reference in the 'Startingpoint' field, use that
			$menuPid = $this->cObj->data['pages'];
		}else{
			// Otherwise use the page's id-number from TSFE
			$menuPid = $GLOBALS['TSFE']->id;
		}

		// Define the list of pages to search for recently changed content (999 depth level).
		$search_list = $this->pi_getPidList($menuPid,999);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	't.pid, p.title, p.subtitle, t.tstamp', 
								'tt_content AS t, pages AS p', 
								'(t.pid = p.uid) AND t.deleted=0 AND p.deleted=0 AND t.hidden=0 AND p.hidden=0 AND t.pid IN('.$search_list.')', 
								't.pid', 
								't.tstamp DESC', 
								$this->conf[numberResults]);
		/*
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($res))        {
                        $tRows[]='<li><a href="'.$this->pi_getPageLink(	$row[0],'').'" title="'.$row[1].'">'.$row[1].' ('.date($this->conf[strftime], $row[3]).')</a></li>';

		}
		*/
		#
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)){
		#
		$tRows1='<dt><a href="'.$this->pi_getPageLink($row[0],'').'" title="'.$row[1].'">'.$row[1].'</a></dt>';
		#
		$tRows2='<dd>'.date($this->conf[strftime], $row[3]).'</dd>';
		#
		$tRows[] = $tRows1 . $tRows2;
		#
		}		
		
		// Build final display menu
                $totalMenu = '<div class="'.$this->conf[cssClassName].'"><dl>'.implode('',$tRows).'</dl></div>';

                return $totalMenu;
	}
	

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/recentcontent/pi1/class.tx_recentcontent_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/recentcontent/pi1/class.tx_recentcontent_pi1.php']);
}

?>
