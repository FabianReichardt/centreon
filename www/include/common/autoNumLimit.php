<?php
/*
 * Copyright 2005-2011 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 * 
 * This program is free software; you can redistribute it and/or modify it under 
 * the terms of the GNU General Public License as published by the Free Software 
 * Foundation ; either version 2 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with 
 * this program; if not, see <http://www.gnu.org/licenses>.
 * 
 * Linking this program statically or dynamically with other modules is making a 
 * combined work based on this program. Thus, the terms and conditions of the GNU 
 * General Public License cover the whole combination.
 * 
 * As a special exception, the copyright holders of this program give MERETHIS 
 * permission to link this program with independent modules to produce an executable, 
 * regardless of the license terms of these independent modules, and to copy and 
 * distribute the resulting executable under terms of MERETHIS choice, provided that 
 * MERETHIS also meet, for each linked independent module, the terms  and conditions 
 * of the license of that module. An independent module is a module which is not 
 * derived from this program. If you modify this program, you may extend this 
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 * 
 * For more information : contact@centreon.com
 * 
 * SVN : $URL$
 * SVN : $Id$
 * 
 */
	if (!isset($oreon))
		exit();

	if (isset($_POST["limit"]) && $_POST["limit"])
		$limit = $_POST["limit"];
	else if (isset($_GET["limit"]))
		$limit = $_GET["limit"];
	else if (!isset($_POST["limit"]) && !isset($_GET["limit"]) && isset($oreon->historyLimit[$url]))
		$limit = $oreon->historyLimit[$url];
	else {
		if (($p >= 200 && $p < 300) || ($p >= 20000 && $p < 30000)){
			$DBRESULT = $pearDB->query("SELECT * FROM `options` WHERE `key` = 'maxViewMonitoring'");
			$gopt = $DBRESULT->fetchRow();		
			$limit = myDecode($gopt["value"]);
		} else {
			$DBRESULT = $pearDB->query("SELECT * FROM `options` WHERE `key` = 'maxViewConfiguration'");
			$gopt = $DBRESULT->fetchRow();		
			$limit = myDecode($gopt["value"]);
		}
	}

	if (isset($_POST["num"]) && $_POST["num"])
		$num = $_POST["num"];
	else if (isset($_GET["num"]) && $_GET["num"])
		$num = $_GET["num"];
	else if (!isset($_POST["num"]) && !isset($_GET["num"]) && isset($oreon->historyPage[$url]))
		$num = $oreon->historyPage[$url];
	else 
		$num = 0;
	
	global $search;
?>