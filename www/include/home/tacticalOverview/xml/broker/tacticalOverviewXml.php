<?php
/**
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
 * SVN : $URL
 * SVN : $Id
 *
 */

    require_once "@CENTREON_ETC@/centreon.conf.php";

	require_once $centreon_path . "www/class/centreonDuration.class.php";
	require_once $centreon_path . "www/include/common/common-Func.php";
	require_once $centreon_path . "www/class/centreonDB.class.php";
    require_once $centreon_path . "www/class/centreonSession.class.php";
    require_once $centreon_path . "www/class/centreonXML.class.php";
    require_once $centreon_path . "www/class/centreon.class.php";
    require_once $centreon_path . 'www/class/centreonACL.class.php';
    require_once $centreon_path . 'www/class/centreonUser.class.php';
    require_once $centreon_path . 'www/class/centreonDuration.class.php';
    require_once $centreon_path . 'www/class/centreonLang.class.php';
    require_once $centreon_path . 'www/class/centreonInstance.class.php';
    include_once $centreon_path . "www/class/centreonCriticality.class.php";
    require_once $centreon_path . "www/class/centreonMedia.class.php";

	session_start();

    if (!isset($_SESSION['centreon'])) {
		exit();
	}

	$db	 	= new CentreonDB();
	$pearDB = $db;
	$dbb 	= new CentreonDB("centstorage");
    $centreon = $_SESSION['centreon'];

    $criticality = new CentreonCriticality($db);
    $media = new CentreonMedia($db);
    $instanceObj = new CentreonInstance($db);

    $centreonLang = new CentreonLang($centreon_path, $centreon);
	$centreonLang->bindLang();

    /**
     * Options
     */
    $hostLimit = 100;
    if (isset($centreon->optGen['tactical_host_limit'])) {
        $hostLimit = $centreon->optGen['tactical_host_limit'];
    }
    $svcLimit = 100;
    if (isset($centreon->optGen['tactical_service_limit'])) {
        $svcLimit = $centreon->optGen['tactical_service_limit'];
    }

	$acl_host_id_list = $centreon->user->access->getHostsString("ID", $dbb);
	$acl_access_group_list = $centreon->user->access->getAccessGroupsString();

	$is_admin = $centreon->user->access->admin;

	$ndo_base_prefix = getNDOPrefix();
	$general_opt = getStatusColor($db);

	// Get Status Globals for hosts
	$rq1 = 	" SELECT count(state), state" .
			" FROM hosts " .
			" WHERE enabled = 1 " .
            " AND state_type = 1 " .
			$centreon->user->access->queryBuilder("AND", "host_id", $acl_host_id_list) .
			" AND name NOT LIKE '_Module_%' " .
			" GROUP BY state " .
			" ORDER BY state";

	$resNdo1 = $dbb->query($rq1);

	$hostStatus = array(0=>0, 1=>0, 2=>0, 3=>0);
	while ($ndo = $resNdo1->fetchRow()) {
		$hostStatus[$ndo["state"]] = $ndo["count(state)"];
	}
	$resNdo1->free();

	// Get Hosts Problems
	$rq1 = 	" SELECT DISTINCT h.host_id, h.name, h.notes, h.notes_url, h.action_url, h.state, h.last_check, h.output, h.icon_image, h.address, h.last_state_change AS lsc, i.name as instance_name " .
			" FROM hosts h, instances i," .
            " (hosts nh LEFT JOIN hosts_hosts_parents hph".
            " ON hph.parent_id = nh.host_id)".
			" WHERE h.enabled = 1" .
            " AND h.state_type = 1" .
            " AND h.instance_id = i.instance_id" .
			$centreon->user->access->queryBuilder("AND", "h.host_id", $acl_host_id_list) .
			" AND h.state != 0" .
			" AND h.state != 4" .
			" AND h.acknowledged = 0" .
			" AND h.scheduled_downtime_depth = 0" .
			" AND h.name NOT LIKE '_Module_%'" .
			" ORDER by h.state LIMIT ". $hostLimit;
	$resNdoHosts = $dbb->query($rq1);

    $tab_macros = array('/\$hostid\$/i',
                        '/\$hostname\$/i',
    			'/\$HOSTNOTES\$/i',
                        '/\$HOSTNOTESURL\$/i',
    			'/\$HOSTACTIONURL\$/i',
	                '/\$hoststate\$/i',
        	        '/\$LASTHOSTCHECK\$/i',
                	'/\$hostoutput\$/i',
                        '/\$hosticon\$/i',
	                '/\$hostaddress\$/i',
        	        '/\$LASTHOSTSTATECHANGE\$/i',
                        '/\$INSTANCENAME\$/i');

	$nbhostpb = 0;
    $tab_hostprobname[$nbhostpb] = "";
    $tab_hostcriticality[$nbhostpb] = "";
    $availableHostCriticalities = 0;
    $tab_hostprobstate[$nbhostpb] = "";
    $tab_hostnotesurl[$nbhostpb] = "";
    $tab_hostnotes[$nbhostpb] = "";
    $tab_hostactionurl[$nbhostpb] = "";
    $tab_hostproblast[$nbhostpb] = "";
    $tab_hostprobduration[$nbhostpb] = "";
    $tab_hostproboutput[$nbhostpb] = "";
    $tab_hostprobip[$nbhostpb] = "";
    $tab_hosticone = array();
    $tab_hostobjectid = array(0=>0, 1=>0, 2=>0, 3=>0);

    while ($ndo = $resNdoHosts->fetchRow()) {
	    $tab_hostprobname[$nbhostpb] = $ndo["name"];
        
        $tab_hostprobstate[$nbhostpb] = $ndo["state"];
        $tab_hostnotesurl[$nbhostpb] = preg_replace($tab_macros,$ndo,$ndo["notes_url"]);
        $tab_hostnotesurl[$nbhostpb] = str_replace("\$INSTANCEADDRESS\$",
			                                       $instanceObj->getParam($ndo['instance_name'], "ns_ip_address"),
			                                       $tab_hostnotesurl[$nbhostpb]);
        $tab_hostnotes[$nbhostpb] = preg_replace($tab_macros,$ndo,$ndo["notes"]);
        $tab_hostactionurl[$nbhostpb] = preg_replace($tab_macros,$ndo,$ndo["action_url"]);
        $tab_hostactionurl[$nbhostpb] = str_replace("\$INSTANCEADDRESS\$",
			                                       $instanceObj->getParam($ndo['instance_name'], "ns_ip_address"),
			                                       $tab_hostactionurl[$nbhostpb]);
        $tab_hostproblast[$nbhostpb] = $centreon->CentreonGMT->getDate(_("Y/m/d G:i"), $ndo["last_check"], $centreon->user->getMyGMT());
        $tab_hostprobduration[$nbhostpb] = CentreonDuration::toString(time() - $ndo["lsc"]);
        
        $ndo["output"] = str_replace("\n", '\n', $ndo["output"]);
        $outputTmp = explode('\n', $ndo["output"]);
        if (count($outputTmp)) {
            $tab_hostproboutput[$nbhostpb] = $outputTmp[0];
        } else {
            $tab_hostproboutput[$nbhostpb] = $ndo["output"];
        }
            
    	$tab_hostprobip[$nbhostpb] = $ndo["address"];
    	$tab_hosticone[$nbhostpb] = $ndo["icon_image"];
    	$tab_hostobjectid[$nbhostpb] = $ndo['host_id'];
        
        // Check if host has criticality
        $tab_hostcriticality[$nbhostpb] = '';
        $rqCriticality = "SELECT cvs.value as criticality ".
                         "FROM customvariables cvs ".
                         "WHERE cvs.host_id = '".$ndo['host_id']."' AND ISNULL(cvs.service_id) ".
                         "AND cvs.name='CRITICALITY_ID'";
        
        $resCriticality = $dbb->query($rqCriticality);
        while ($crit = $resCriticality->fetchRow()){
            $infoC = $criticality->getData($crit["criticality"]);
            if (isset($infoC))
            {
                $availableHostCriticalities = 1;
                $tab_hostcriticality[$nbhostpb] = './img/media/'.$media->getFilename($infoC["icon_id"]);
            }
        }
        
		$nbhostpb++;
	}
	$resNdoHosts->free();

	$hostUnhand = array(0=>$hostStatus[0], 1=>$hostStatus[1], 2=>$hostStatus[2], 3=>$hostStatus[3]);
	/*
	 * Get the id's of problem hosts
	*/
	$rq1 = 	" SELECT h.host_id, h.state ".
			" FROM services s, hosts h " .
			" WHERE s.host_id = h.host_id " .
			" AND h.enabled = 1 " .
                        " AND h.state_type = 1 " .
			$centreon->user->access->queryBuilder("AND", "h.host_id", $acl_host_id_list) .
			" AND h.name NOT LIKE '_Module_%' " .
			" GROUP BY s.host_id";
	$resNdo1 = $dbb->query($rq1);
	$pbCount = 0;
	while ($ndo = $resNdo1->fetchRow()) {
		if ($ndo["state"] != 0) {
			$hostPb[$pbCount] = $ndo["host_id"];
			$pbCount++;
		}
	}
	$resNdo1->free();

	/*
	 * Get Host Ack  UP(0), DOWN(1),  UNREACHABLE(2)
	 */
	$rq1 = 	" SELECT name, ".
	        " state, acknowledged, ".
	        " scheduled_downtime_depth " .
		" FROM hosts " .
                " WHERE enabled = 1 " .
                " AND state_type = 1 " .
	        " AND name NOT LIKE '_Module_%' " .
		" AND (acknowledged = 1 OR " .
	        " scheduled_downtime_depth > 0) ".
		$centreon->user->access->queryBuilder("AND", "host_id", $acl_host_id_list) .
		" ORDER by state";

	$hostAck = array(0=>0, 1=>0, 2=>0, 3=>0);
	$hostDt = array(0=>0, 1=>0, 2=>0, 3=>0);
	$resNdo1 = $dbb->query($rq1);
	while ($ndo = $resNdo1->fetchRow()) {
		if ($ndo['acknowledged']) {
		    $hostAck[$ndo["state"]]++;
		}
		if ($ndo['scheduled_downtime_depth']) {
		    $hostDt[$ndo['state']]++;
		}
		$hostUnhand[$ndo["state"]]--;
	}
	$resNdo1->free();

	/*
	 * Get Host inactive objects
	 */
	$rq1 = 	" SELECT count(state), state" .
		" FROM hosts " .
		" WHERE enabled = 1 ".
                " AND state_type = 1 ".
                " AND active_checks = 0 " .
		$centreon->user->access->queryBuilder("AND", "host_id", $acl_host_id_list) .
		" AND name NOT LIKE '_Module_%' " .
		" GROUP BY state " .
		" ORDER BY state";

	$resNdo1 = $dbb->query($rq1);
	$hostInactive = array(0=>0, 1=>0, 2=>0, 3=>0);
	while ($ndo = $resNdo1->fetchRow())	{
		$hostInactive[$ndo["state"]] = $ndo["count(state)"];
		$hostUnhand[$ndo["state"]] -= $hostInactive[$ndo["state"]];
	}
	$resNdo1->free();

	/*
	 * Get Host Unrea Not Unhandled
	 */

	/*
	 * Get Status global for Services
	 */
	if (!$is_admin) {
		$rq2 = 	" SELECT count(DISTINCT CONCAT(h.host_id,';',s.service_id)) AS count, s.state" .
                        " FROM services s, hosts h, centreon_acl" .
			" WHERE h.host_id = s.host_id".
			" AND h.name NOT LIKE '_Module_%' ".
			" AND h.host_id = centreon_acl.host_id ".
			" AND s.service_id = centreon_acl.service_id " .
		        " AND h.enabled = 1 " .
                        " AND s.state_type = 1 " .
			" AND centreon_acl.group_id IN (".$acl_access_group_list.") " .
			" AND s.enabled = 1 GROUP BY s.state ORDER BY s.state";
	}
	else {
		$rq2 = 	" SELECT count(s.state) AS count, s.state".
			" FROM services s, hosts h " .
			" WHERE h.host_id = s.host_id".
			" AND h.name not like '_Module_%' ".
			" AND h.enabled = 1 " .
                        " AND s.state_type = 1 " .
			" AND s.enabled = 1 GROUP BY s.state ORDER BY s.state";
	}
	$resNdo2 = $dbb->query($rq2);
	$SvcStat = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);

	while ($ndo = $resNdo2->fetchRow()) {
		$SvcStat[$ndo["state"]] = $ndo["count"];
	}
	$resNdo2->free();

	/*
	 * Get on pb host
	 */
	if (!$is_admin) {
		$rq2 = 	" SELECT s.state, s.host_id".
                        " FROM services s, hosts h, centreon_acl " .
                        " WHERE h.host_id = s.host_id".
			" AND h.name NOT LIKE '_Module_%' ".
			" AND h.host_id = centreon_acl.host_id ".
			" AND s.service_id = centreon_acl.service_id " .
			" AND centreon_acl.group_id IN (".$acl_access_group_list.") " .
			" AND s.enabled = 1 " .
                        " AND s.state_type = 1 ".
			" AND s.acknowledged = 0" .
			" AND s.state != 0 AND s.state != 4 GROUP BY s.service_id";
	} else {
		$rq2 = 	" SELECT s.state, s.host_id".
			" FROM services s, hosts h " .
			" WHERE h.host_id = s.host_id".
			" AND h.name NOT LIKE '_Module_%' ".
			" AND s.enabled = 1 " .
                        " AND s.state_type = 1 ".
			" AND s.acknowledged = 0" .
			" AND s.state != 0 AND s.state != 4 GROUP BY s.service_id";
	}
	$onPbHost = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);

	$resNdo1 = $dbb->query($rq2);
	while($ndo = $resNdo1->fetchRow())	{
		if ($ndo["state"] != 0) {
			for ($i = 0; $i < $pbCount; $i++) {
				if (isset($hostPb[$i]) && ($hostPb[$i] == $ndo["host_id"])) {
					$onPbHost[$ndo["state"]]++;
				}
			}
		}
	}
	$resNdo1->free();


	/*
	 * Get Service Acknowledgements and Downtimes OK(0), WARNING(1),  CRITICAL(2), UNKNOWN(3)
	 */
	if (!$is_admin) {
		$rq1 = 	" SELECT DISTINCT s.state, " .
		        " s.service_id, " .
                        " s.acknowledged, " .
		        " s.scheduled_downtime_depth " .
			" FROM services s, centreon_acl, hosts h" .
			" WHERE h.host_id = s.host_id " .
			" AND (s.acknowledged = 1 OR " .
			" s.scheduled_downtime_depth > 0) " .
			" AND s.enabled = 1 " .
                        " AND s.state_type = 1 ".
			" AND s.host_id = centreon_acl.host_id ".
			" AND s.service_id = centreon_acl.service_id " .
			" AND centreon_acl.group_id IN (".$acl_access_group_list.") " .
			" AND h.name NOT LIKE '_Module_%' ";
	} else {
		$rq1 = 	" SELECT DISTINCT s.state, " .
		        " s.service_id, " .
                        " s.acknowledged, " .
		        " s.scheduled_downtime_depth " .
                        " FROM services s, hosts h" .
			" WHERE h.host_id = s.host_id " .
			" AND (s.acknowledged = 1 OR " .
			" s.scheduled_downtime_depth > 0) " .
			" AND s.enabled = 1 " .
                        " AND s.state_type = 1 ".
			" AND h.name NOT LIKE '_Module_%' ";
	}
	$resNdo1 = $dbb->query($rq1);

	$svcAckDt = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);
	$svcAck = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);
	$svcDt = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);
	while ($ndo = $resNdo1->fetchRow()) {
	    $svcAckDt[$ndo["state"]]++;
	    if ($ndo['acknowledged']) {
	        $svcAck[$ndo["state"]]++;
	    }
	    if ($ndo['scheduled_downtime_depth']) {
	        $svcDt[$ndo["state"]]++;
	    }
	}
	$resNdo1->free();


	/*
	 * Get Services Inactive objects
	 */
	if (!$is_admin) {
		$rq2 = 	" SELECT count(s.state), s.state" .
                " FROM services s, hosts h, centreon_acl " .
                " WHERE h.host_id = s.host_id".
                " AND h.name NOT LIKE '_Module_%' ".
                " AND s.host_id = centreon_acl.host_id ".
                " AND s.service_id = centreon_acl.service_id " .
                " AND centreon_acl.group_id IN (".$acl_access_group_list.") ".
                " AND s.enabled = 1 ".
                " AND s.state_type = 1 ".
                " AND s.active_checks = '0' GROUP BY s.state ORDER BY s.state";
	}
	else {
		$rq2 = 	" SELECT count(s.state), s.state" .
                " FROM services s, hosts h" .
                " WHERE h.host_id = s.host_id".
                " AND h.name NOT LIKE '_Module_%' ".
                " AND s.enabled = 1 ".
                " AND s.state_type = 1 ".
                " AND s.active_checks = '0' GROUP BY s.state ORDER BY s.state";
	}
	$resNdo2 = $dbb->query($rq2);

	$svcInactive = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);
	while ($ndo = $resNdo2->fetchRow()) {
		$svcInactive[$ndo["state"]] = $ndo["count(s.state)"];
	}
	$resNdo2->free();

	/*
	 * Get Undandled Services
	 */
	$svcUnhandled = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0);
	for ($i=0; $i<=4; $i++) {
		$svcUnhandled[$i] = $SvcStat[$i] - $svcAckDt[$i] - $svcInactive[$i] - $onPbHost[$i];
	}

	/*
	 * Get problem table
	 */
	if (!$is_admin) {
		$rq1 = 	" SELECT DISTINCT h.name, s.host_id, s.service_id, s.description, s.notes, s.notes_url, s.action_url, s.state, s.last_check as last_check, s.output, s.last_state_change as last_state_change, h.address, h.icon_image, i.name as instance_name" .
                " FROM services s, hosts h, centreon_acl, instances i " .
                " WHERE h.host_id = s.host_id " .
                " AND h.instance_id = i.instance_id " .
                " AND s.state != 0" .
                " AND s.state != 4" .
                " AND s.acknowledged = 0" .
                " AND s.scheduled_downtime_depth = 0" .
                " AND s.enabled = 1" .
                " AND s.state_type = 1 " .
                " AND h.enabled = 1" .
                " AND h.name NOT LIKE '_Module_%' " .
                " AND s.host_id = centreon_acl.host_id ".
                " AND s.service_id = centreon_acl.service_id " .
                " AND centreon_acl.group_id IN (".$acl_access_group_list.") " .
                " ORDER BY FIELD(s.state,2,1,3), s.last_state_change DESC, h.name LIMIT " . $svcLimit;
	} else {
		$rq1 = 	" SELECT DISTINCT h.name, s.host_id, s.service_id, s.description, s.notes, s.notes_url, s.action_url, s.state, s.last_check as last_check, s.output, s.last_state_change as last_state_change, h.address, h.icon_image, i.name as instance_name" .
                " FROM hosts h, instances i, services s" .
                " WHERE h.host_id = s.host_id " .
		        " AND h.instance_id = i.instance_id " .
                " AND s.state != 0" .
				" AND s.state != 4" .
                " AND s.acknowledged = 0" .
		        " AND s.scheduled_downtime_depth = 0" .
                " AND s.enabled = 1".
                " AND s.state_type = 1".
                " AND h.enabled = 1" .
                " AND h.name NOT LIKE '_Module_%' " .
                " ORDER BY FIELD(s.state,2,1,3), s.last_state_change DESC, h.name LIMIT " . $svcLimit;
	}

    
	$j = 0;
	$tab_hostname[$j] = "";
    $tab_svccriticality[$j] = "";
    $availableSvcCriticalities = 0;
	$tab_svcname[$j] = "";
	$tab_state[$j] = "";
	$tab_notes_url[$j] = "";
	$tab_notes[$j] = "";
	$tab_action_url[$j] = "";
	$tab_last[$j] = "";
	$tab_duration[$j] = "";
	$tab_output[$j] = "";
	$tab_ip[$j] = "";
	$tab_icone[$j] = "";
	$tab_objectid[$j] = "";
	$tab_hobjectid[$j] = "";

	$tab_macros = array('/\$hostname\$/i',
                        '/\$hostid\$/i',
                        '/\$serviceid$/i',
                        '/\$servicedesc\$/i',
                        '/\$SERVICENOTES\$/i',
                        '/\$SERVICENOTESURL\$/i',
                        '/\$SERVICEACTIONURL\$/i',
                        '/\$servicestate\$/i',
                        '/\$LASTSERVICECHECK\$/i',
                        '/\$serviceoutput\$/i',
                        '/\$LASTSERVICESTATECHANGE\$/i',
                        '/\$hostaddress\$/i',
                        '/\$hosticon\$/i',
	                '/\$INSTANCENAME\$/i');
    $resNdo1 = $dbb->query($rq1);
    
	while ($ndo = $resNdo1->fetchRow()){
		$is_unhandled = 1;

		for ($i = 0; $i < $pbCount && $is_unhandled; $i++){
			if (isset($hostPb[$i]) && ($hostPb[$i] == $ndo["host_id"]))
				$is_unhandled = 0;
		}

		if ($is_unhandled) {
			$tab_hostname[$j] = $ndo["name"];
			$tab_svcname[$j] = $ndo["description"];
            
			$tab_state[$j] = $ndo["state"];
			$tab_notes_url[$j] = preg_replace($tab_macros,$ndo,$ndo["notes_url"]);
			$tab_notes_url[$j] = str_replace("\$INSTANCEADDRESS\$",
			                                 $instanceObj->getParam($ndo['instance_name'], "ns_ip_address"),
			                                 $tab_notes_url[$j]);
			$tab_notes[$j] = preg_replace($tab_macros,$ndo,$ndo["notes"]);
			$tab_action_url[$j] = preg_replace($tab_macros,$ndo,$ndo["action_url"]);
			$tab_action_url[$j] = str_replace("\$INSTANCEADDRESS\$",
			                                 $instanceObj->getParam($ndo['instance_name'], "ns_ip_address"),
			                                 $tab_action_url[$j]);
			$tab_last[$j] = $centreon->CentreonGMT->getDate(_("Y/m/d G:i"), $ndo["last_check"], $centreon->user->getMyGMT());
			$tab_ip[$j] = $ndo["address"];
			$tab_duration[$j] = " - ";
			if ($ndo["last_state_change"] > 0 && time() > $ndo["last_state_change"]) {
	    		$tab_duration[$j] = CentreonDuration::toString(time() - $ndo["last_state_change"]);
			}
            
            $ndo["output"] = str_replace("\n", '\n', $ndo["output"]);
            $outputTmp = explode('\n', $ndo["output"]);
            if (count($outputTmp)) {
                $tab_output[$j] = $outputTmp[0];
            } else {
                $tab_output[$j] = $ndo["output"];
            }
            
                
            
			$tab_icone[$j] = $ndo["icon_image"];
			$tab_objectid[$j] = $ndo['host_id'] . "_" . $ndo['service_id'];
			$tab_hobjectid[$j] = $ndo['host_id'];
            
            // Check if service has criticality
            $tab_svccriticality[$j] = '';
            $rqCriticality = "SELECT cvs.value as criticality ".
                             "FROM customvariables cvs ".
                             "WHERE cvs.service_id = '".$ndo['service_id']."' ".
                             "AND cvs.name='CRITICALITY_ID'";
            
            $resCriticality = $dbb->query($rqCriticality);
            while ($crit = $resCriticality->fetchRow()){
                $infoC = $criticality->getData($crit["criticality"]);
                if (isset($infoC))
                {
                    $availableSvcCriticalities = 1;
                    $tab_svccriticality[$j] = './img/media/'.$media->getFilename($infoC["icon_id"]);
                }
            }
            
			$j++;
		}
	}
	$resNdo1->free();
	$nb_pb = $j;

	$xml = new CentreonXML();
	$xml->startElement('root');

    $xml->writeElement('availableSvcCriticalities', $availableSvcCriticalities);
    $xml->writeElement('availableHostCriticalities', $availableHostCriticalities);
	$xml->writeElement('nbHostPb', $nbhostpb);
	$xml->writeElement('nbSvcPb', $nb_pb);
	if (is_array($general_opt)) {
	    foreach ($general_opt as $key => $val) {
	        $xml->writeElement($key, $val);
	    }
	}

	/*
	 *  Hosts
	 */
	$xml->writeElement('hostUp', $hostStatus[0]);
	$xml->writeElement('hostUpInactive', $hostInactive[0]);

	$xml->writeElement('hostDown', $hostStatus[1]);
	$xml->writeElement('hostDownAck', $hostAck[1]);
	$xml->writeElement('hostDownInact', $hostInactive[1]);
	$xml->writeElement('hostDownUnhand', $hostUnhand[1]);

	$xml->writeElement('hostUnreach', $hostStatus[2]);
	$xml->writeElement('hostUnreachAck', $hostAck[2]);
	$xml->writeElement('hostUnreachInact', $hostInactive[2]);
	$xml->writeElement('hostUnreachUnhand', $hostUnhand[2]);

	$xml->writeElement('hostPending', $hostStatus[3]);
	$xml->writeElement('hostPendingAck', $hostAck[3]);
	$xml->writeElement('hostPendingInact', $hostInactive[3]);
	$xml->writeElement('hostPendingUnhand', $hostUnhand[3]);

	/*
	 *  Services
	 */
	$xml->writeElement('svcOk', $SvcStat[0]);
	$xml->writeElement('svcOkInactive', $svcInactive[0]);

	$xml->writeElement('svcWarning', $SvcStat[1]);
	$xml->writeElement('svcWarningAck', $svcAck[1]);
	$xml->writeElement('svcWarningInact', $svcInactive[1]);
	$xml->writeElement('svcWarningUnhand', $svcUnhandled[1]);
	$xml->writeElement('svcWarningOnpbHost', $onPbHost[1]);

	$xml->writeElement('svcCritical', $SvcStat[2]);
	$xml->writeElement('svcCriticalAck', $svcAck[2]);
	$xml->writeElement('svcCriticalInact', $svcInactive[2]);
	$xml->writeElement('svcCriticalUnhand', $svcUnhandled[2]);
	$xml->writeElement('svcCriticalOnpbHost', $onPbHost[2]);

	$xml->writeElement('svcUnknown', $SvcStat[3]);
	$xml->writeElement('svcUnknownAck', $svcAck[3]);
	$xml->writeElement('svcUnknownInact', $svcInactive[3]);
	$xml->writeElement('svcUnknownUnhand', $svcUnhandled[3]);
	$xml->writeElement('svcUnknownOnpbHost', $onPbHost[3]);

	$xml->writeElement('svcPending', $SvcStat[4]);
	$xml->writeElement('svcPendingAck', $svcAck[4]);
	$xml->writeElement('svcPendingInact', $svcInactive[4]);
	$xml->writeElement('svcPendingUnhand', $svcUnhandled[4]);
	$xml->writeElement('svcPendingOnpbHost', $onPbHost[4]);

	/*
	 *  Unhandled hosts
	 */
	$style = 'list_two';
	$domId = 0;
	foreach ($tab_hostprobname as $key => $val) {
	    $domId++;
	    $style = ($style == 'list_two') ? 'list_one' : 'list_two';
	    $xml->startElement('unhandledHosts');
	    $xml->writeElement('hostname', $val, false);
        $xml->writeElement('hostcriticality', (isset($tab_hostcriticality[$key])) ? $tab_hostcriticality[$key] : '');
	    $xml->writeElement('host_notesurl',$tab_hostnotesurl[$key]);
	    $xml->writeElement('host_notes',$tab_hostnotes[$key]);
	    $xml->writeElement('host_actionurl',$tab_hostactionurl[$key]);
	    $xml->writeElement('ip', $tab_hostprobip[$key]);
	    $xml->writeElement('duration', $tab_hostprobduration[$key]);
	    $xml->writeElement('last', $tab_hostproblast[$key]);
	    $xml->writeElement('output', $tab_hostproboutput[$key]);
	    $xml->writeElement('icon', (isset($tab_hosticone[$key]) ? $tab_hosticone[$key] : ""));
	    $xml->writeElement('hid', (isset($tab_hostobjectid[$key]) ? $tab_hostobjectid[$key] : ""));
	    $xml->writeElement('domId', $tab_hostobjectid[$key] + '_' + $domId);
	    $xml->writeElement('class', $style);
	    if ($tab_hostprobstate[$key] == 1) {
	        $xml->writeElement('state', _('Down'));
	        $xml->writeElement('bgcolor', $general_opt['color_critical']);
	    } elseif ($tab_hostprobstate[$key] == 2) {
	        $xml->writeElement('state', _('Unreachable'));
	        $xml->writeElement('bgcolor', $general_opt['color_unreachable']);
	    } elseif ($tab_hostprobstate[$key] == 3) {
	        $xml->writeElement('state', _('Pending'));
	        $xml->writeElement('bgcolor', $general_opt['color_pending']);
	    }
	    $xml->endElement();
	}

	/*
	 *  Unhandled services
	 */
	$style = 'list_two';
	foreach($tab_svcname as $key => $val) {
	    $domId++;
	    $style = ($style == 'list_two') ? 'list_one' : 'list_two';
        $xml->startElement('unhandledServices');
        $xml->writeElement('servicecriticality', (isset($tab_svccriticality[$key])) ? $tab_svccriticality[$key] : '');
	    $xml->writeElement('servicename', $val, false);
	    $xml->writeElement('notes_url',$tab_notes_url[$key]);
	    $xml->writeElement('notes',$tab_notes[$key]);
	    $xml->writeElement('action_url',$tab_action_url[$key]);
	    $xml->writeElement('hostname', $tab_hostname[$key], false);
	    $xml->writeElement('ip', $tab_ip[$key]);
	    $xml->writeElement('duration', $tab_duration[$key]);
	    $xml->writeElement('last', $tab_last[$key]);
	    $xml->writeElement('output', $tab_output[$key]);
	    $xml->writeElement('icon', $tab_icone[$key]);
	    $xml->writeElement('sid', $tab_objectid[$key]);
	    $xml->writeElement('hid', $tab_hobjectid[$key]);
	    $xml->writeElement('domId', $tab_hobjectid[$key] . "_" . $domId);
	    $xml->writeElement('class', $style);
	    
	    if ($tab_state[$key] == 1) {
	        $xml->writeElement('state', _('Warning'));
	        $xml->writeElement('bgcolor', $general_opt['color_warning']);
	    } elseif ($tab_state[$key] == 2) {
	        $xml->writeElement('state', _('Critical'));
	        $xml->writeElement('bgcolor', $general_opt['color_critical']);
	    } elseif ($tab_state[$key] == 3) {
	        $xml->writeElement('state', _('Unknown'));
	        $xml->writeElement('bgcolor', $general_opt['color_unknown']);
	    } elseif ($tab_state[$key] == 4) {
	        $xml->writeElement('state', _('Pending'));
	        $xml->writeElement('bgcolor', $general_opt['color_pending']);
	    }
	    $xml->endElement();
	}


	$xml->startElement('main');
	/*
	 * URL
	 */
	$xml->writeElement("url_hostPb",     "main.php?p=20103&o=hpb&search=");
	$xml->writeElement("url_hostOK",     "main.php?p=20102&o=h_up&search=");
	$xml->writeElement("url_host_unhand","main.php?p=20105&o=h_unhandled&search=");
	$xml->writeElement("url_svc_unhand", "main.php?p=20215&o=svc_unhandled&search=");
	$xml->writeElement("url_svc_ack",    "main.php?p=2020402&o=svcOV&acknowledge=1&search=");
	$xml->writeElement("url_ok",         "main.php?p=20201&o=svc_ok&search=");
	$xml->writeElement("url_critical",   "main.php?p=20201&o=svc_critical&search=");
	$xml->writeElement("url_warning",    "main.php?p=20201&o=svc_warning&search=");
	$xml->writeElement("url_unknown",    "main.php?p=20201&o=svc_unknown&search=");
	$xml->writeElement("url_hostdetail", "main.php?p=201&o=hd&host_name=");
	$xml->writeElement("url_svcdetail",  "main.php?p=202&o=svcd&host_name=");
	$xml->writeElement("url_svcdetail2", "&service_description=");


	/*
	 *  Strings for the host part
	 */
	$xml->writeElement("str_hosts", _("Hosts"));
	$xml->writeElement("str_up", _("Up"));
	$xml->writeElement("str_down", _("Down"));
	$xml->writeElement("str_unreachable", _("Unreachable"));

	/*
	 *  Strings for the service part
	 */
	$xml->writeElement("str_services", _("Services"));
	$xml->writeElement("str_ok", _("OK"));
	$xml->writeElement("str_warning", _("Warning"));
	$xml->writeElement("str_critical", _("Critical"));
	$xml->writeElement("str_unknown", _("Unknown"));
	$xml->writeElement("str_pbhost", _("On Problem Host"));
	$xml->writeElement("str_unhandledpb", _("Unhandled"));

	/*
	 *  Common Strings for both the host and service parts
	 */
	$xml->writeElement("str_pending", _("Pending"));
	$xml->writeElement("str_disabled", _("Disabled"));
	$xml->writeElement("str_acknowledged", _("Acknowledged"));

	/*
	 *  Strings for service problems
	 */
	$xml->writeElement("str_unhandled", sprintf(_("Unhandled Service problems (last %s)"), $svcLimit));
	$xml->writeElement("str_no_unhandled", _("No unhandled service problem"));
	$xml->writeElement("str_hostname", _("Host Name"));
	$xml->writeElement("str_servicename", _("Service Name"));
    $xml->writeElement("str_criticality", _("C"));
	$xml->writeElement("str_status", _("Status"));
	$xml->writeElement("str_lastcheck", _("Last Check"));
	$xml->writeElement("str_duration", _("Duration"));
	$xml->writeElement("str_output", _("Status Output"));
	$xml->writeElement("str_actions", _("Actions"));
	$xml->writeElement("str_ip", _("IP Address"));

	/*
	 *  Strings for hosts problems
	 */
	$xml->writeElement("str_hostprobunhandled", sprintf(_("Unhandled Host problems (last %s)"), $hostLimit));
	$xml->writeElement("str_hostprobno_unhandled", _("No unhandled host problem"));
	$xml->writeElement("str_hostprobhostname", _("Host Name"));
	$xml->writeElement("str_hostprobcriticality", _("C"));
    $xml->writeElement("str_hostprobstatus", _("Status"));
	$xml->writeElement("str_hostproblastcheck", _("Last Check"));
	$xml->writeElement("str_hostprobduration", _("Duration"));
	$xml->writeElement("str_hostproboutput", _("Status Output"));
	$xml->writeElement("str_hostprobip", _("IP Address"));
	$xml->endElement();

	$xml->endElement();
	header('Content-Type: text/xml');
	header('Pragma: no-cache');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate');
	$xml->output();
?>
