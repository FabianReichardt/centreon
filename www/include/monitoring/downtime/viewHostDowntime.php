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

	if (!isset($centreon)) {
		exit();
	}

	include_once $centreon_path."www/class/centreonGMT.class.php";
	include_once "./include/common/autoNumLimit.php";

	if (isset($_POST["hostgroup"]))
	  $hostgroup = $_POST["hostgroup"];
	else if (isset($_GET["hostgroup"]))
	   $hostgroup = $_GET["hostgroup"];
	else if (isset($centreon->hostgroup) && $centreon->hostgroup)
	   $hostgroup = $centreon->hostgroup;
	else
	   $hostgroup = 0;

	if (isset($_POST["search_host"]))
		$host_name = $_POST["search_host"];
	else if (isset($_GET["search_host"]))
		$host_name = $_GET["search_host"];
	else
		$host_name = NULL;

	if (isset($_POST["search_output"]))
		$search_output = $_POST["search_output"];
	else if (isset($_GET["search_output"]))
		$search_output = $_GET["search_output"];
	else
		$search_output = NULL;

	if (isset($_POST["view_all"]))
		$view_all = 1;
	else if (isset($_GET["view_all"]) && !isset($_POST["SearchB"]))
		$view_all = 1;
	else
		$view_all = 0;

	if (isset($_POST["view_downtime_cycle"]))
		$view_downtime_cycle = 1;
	else if (isset($_GET["view_downtime_cycle"]) && !isset($_POST["SearchB"]))
		$view_downtime_cycle = 1;
	else
		$view_downtime_cycle = 0;
	
	if (isset($_POST["search_author"]))
		$search_author = $_POST["search_author"];
	else if (isset($_GET["search_author"]) && !isset($_POST["SearchB"]))
		$search_author = $_GET["search_author"];
	else
		$search_author = NULL;
		
	/*
	 * Init GMT class
	 */
	$centreonGMT = new CentreonGMT($pearDB);
	$centreonGMT->getMyGMTFromSession(session_id(), $pearDB);

	include_once("./class/centreonDB.class.php");

	if ($oreon->broker->getBroker() == "ndo") {
		$pearDBndo = new CentreonDB("ndo");
		$ndo_base_prefix = getNDOPrefix();
	} else {
	    $pearDBndo = new CentreonDB("centstorage");
	}

	/*
	 * Smarty template Init
	 */
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl, "template/");

	/*
	 * Pear library
	 */
	require_once "HTML/QuickForm.php";
	require_once 'HTML/QuickForm/advmultiselect.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form = new HTML_QuickForm('select_form', 'GET', "?p=".$p);

	$hostStr = $centreon->user->access->getHostsString("NAME", ($oreon->broker->getBroker() == "ndo" ? $pearDBndo : $pearDBO));

	/************************************
	 * Hosts Downtimes
	 */

	if ($view_all == 1) {
		$downtimeTable = "downtimehistory";
		if ($oreon->broker->getBroker() == "ndo") {
		    $extrafields = ", UNIX_TIMESTAMP(dtm.actual_end_time) as actual_end_time, was_cancelled ";
		} else {
		    $extrafields = ", actual_end_time, cancelled as was_cancelled ";
		}
	} else {
		$downtimeTable = "scheduleddowntime";
		$extrafields = "";
	}

	if ($oreon->broker->getBroker() == "ndo") {
		$request =	"SELECT SQL_CALC_FOUND_ROWS DISTINCT dtm.internal_downtime_id, unix_timestamp(dtm.entry_time), " .
				"dtm.duration, dtm.author_name, dtm.comment_data, dtm.is_fixed, unix_timestamp(dtm.scheduled_start_time) AS scheduled_start_time, ".
				"unix_timestamp(dtm.scheduled_end_time) AS scheduled_end_time, obj.name1 host_name, was_started " . $extrafields .
				"FROM ".$ndo_base_prefix.$downtimeTable." dtm, ".$ndo_base_prefix."objects obj " .
				(isset($hostgroup) && $hostgroup != 0 ? ", ".$ndo_base_prefix."hostgroup_members mb " : "") .
				"WHERE obj.name1 IS NOT NULL " .
				"AND obj.name2 IS NULL ";
		$request .= (isset($host_name) && $host_name != "" ? " AND obj.name1 LIKE '%$host_name%'" : "") .
				(isset($search_output) && $search_output != "" ? " AND dtm.comment_data LIKE '%$search_output%'" : "") .
				(isset($hostgroup) && $hostgroup != 0 ? " AND dtm.object_id = mb.host_object_id AND mb.hostgroup_id = $hostgroup " : "") .
				(isset($search_author) && $search_author != "" ? " AND dtm.author_name LIKE '%$search_author%'" : "") .
		   		(isset($view_downtime_cycle) && $view_downtime_cycle == 0 ? " AND dtm.comment_data NOT LIKE '%Downtime cycle%' " : "") .
				"AND obj.object_id = dtm.object_id " .
				$centreon->user->access->queryBuilder("AND", "obj.name1", $hostStr) .
				(isset($view_all) && $view_all == 0 ? "AND dtm.scheduled_end_time > '".date("Y-m-d G:i:s", time())."' " : "") .
				"ORDER BY dtm.scheduled_start_time DESC " .
				"LIMIT ".$num * $limit.", ".$limit;
		$DBRESULT_NDO = $pearDBndo->query($request);
		$tab_downtime_host = array();
		for ($i = 0; $data = $DBRESULT_NDO->fetchRow(); $i++){
			$tab_downtime_host[$i] = $data;
			$tab_downtime_host[$i]["scheduled_start_time"] = $centreonGMT->getDate("m/d/Y H:i" , $tab_downtime_host[$i]["scheduled_start_time"])." ";
			$tab_downtime_host[$i]["scheduled_end_time"] = $centreonGMT->getDate("m/d/Y H:i" , $tab_downtime_host[$i]["scheduled_end_time"])." ";
			$tab_downtime_host[$i]["host_name_link"] = urlencode($tab_downtime_host[$i]["host_name"]);
		}
		$DBRESULT_NDO->free();
		unset($data);
	} else {
        $request =	"SELECT SQL_CALC_FOUND_ROWS d.internal_id as internal_downtime_id, d.entry_time, " .
				"d.duration, d.author as author_name, d.comment_data, d.fixed as is_fixed, d.start_time AS scheduled_start_time, ".
				"d.end_time as scheduled_end_time, h.name as host_name, d.started as was_started " . $extrafields .
				"FROM downtimes d, hosts h " .
				(isset($hostgroup) && $hostgroup != 0 ? ", ".$ndo_base_prefix."hostgroup_members mb " : "") .
				"WHERE d.type = 2 " .
				(isset($view_all) && !$view_all ? "AND d.cancelled = 0 " : "") .
				(isset($host_name) && $host_name != "" ? " AND h.name LIKE '%$host_name%'" : "") .
				(isset($search_output) && $search_output != "" ? " AND d.comment_data LIKE '%$search_output%'" : "") .
				(isset($hostgroup) && $hostgroup != 0 ? " AND dtm.object_id = mb.host_object_id AND mb.hostgroup_id = $hostgroup " : "") .
				"AND d.host_id = h.host_id " .
        		"AND h.enabled = 1 ";
        if (!$is_admin) {
            $request .= " AND EXISTS(SELECT 1 FROM centreon_acl WHERE d.host_id = centreon_acl.host_id AND group_id IN (" . $oreon->user->access->getAccessGroupsString() . ")) ";
        }
		$request .= $centreon->user->access->queryBuilder("AND", "h.name", $hostStr) .
           		    (isset($view_downtime_cycle) && $view_downtime_cycle == 0 ? " AND d.comment_data NOT LIKE '%Downtime cycle%' " : "") .
        		    (isset($search_author) && $search_author != "" ? " AND d.author LIKE '%$search_author%'" : "") .
				    (isset($view_all) && $view_all == 0 ? "AND d.end_time > '".time()."' " : "") .
				    "ORDER BY d.start_time DESC " .
				    "LIMIT ".$num * $limit.", ".$limit;
		$DBRESULT_NDO = $pearDBndo->query($request);
		$tab_downtime_host = array();
		for ($i = 0; $data = $DBRESULT_NDO->fetchRow(); $i++){
			$tab_downtime_host[$i] = $data;
			$tab_downtime_host[$i]["scheduled_start_time"] = $centreonGMT->getDate("m/d/Y H:i" , $tab_downtime_host[$i]["scheduled_start_time"])." ";
			$tab_downtime_host[$i]["scheduled_end_time"] = $centreonGMT->getDate("m/d/Y H:i" , $tab_downtime_host[$i]["scheduled_end_time"])." ";
			$tab_downtime_host[$i]["host_name_link"] = urlencode($tab_downtime_host[$i]["host_name"]);
		}
		$DBRESULT_NDO->free();
		unset($data);
	}
	$rows = $pearDBndo->numberRows();
	include("./include/common/checkPagination.php");

	$en = array("0" => _("No"), "1" => _("Yes"));
	foreach ($tab_downtime_host as $key => $value) {
		$tab_downtime_host[$key]["is_fixed"] = $en[$tab_downtime_host[$key]["is_fixed"]];
		$tab_downtime_host[$key]["was_started"] = $en[$tab_downtime_host[$key]["was_started"]];
	    if ($view_all == 1) {
            if (!isset($tab_downtime_host[$key]["actual_end_time"]) || !$tab_downtime_host[$key]["actual_end_time"]) {
                if ($tab_downtime_host[$key]["was_cancelled"] == 0) {
                    $tab_downtime_host[$key]["actual_end_time"] = _("N/A");
                } else {
                    $tab_downtime_host[$key]["actual_end_time"] = _("Never Started");
                }
            } else {
                $tab_downtime_host[$key]["actual_end_time"] = $centreonGMT->getDate("m/d/Y H:i" , $tab_downtime_host[$key]["actual_end_time"]);
            }
            $tab_downtime_host[$key]["was_cancelled"] = $en[$tab_downtime_host[$key]["was_cancelled"]];
	    }
	}

	/*
	 * Element we need when we reload the page
	 */
	$form->addElement('hidden', 'p');
	$tab = array ("p" => $p);
	$form->setDefaults($tab);

	if ($centreon->user->access->checkAction("host_schedule_downtime")) {
		$tpl->assign('msgh', array ("addL"            => "?p=".$p."&o=ah",
									"addT"            => _("Add a downtime"),
									"delConfirm"      => _("Do you confirm the deletion ?"),
		                            "cancelConfirm"   => _("Do you confirm the cancelation?")));
	}

	$tpl->assign("p", $p);

	$tpl->assign("tab_downtime_host", $tab_downtime_host);
	$tpl->assign("nb_downtime_host", count($tab_downtime_host));

	$tpl->assign("dtm_host_name", _("Host Name"));
	$tpl->assign("dtm_start_time", _("Start Time"));
	$tpl->assign("dtm_end_time", _("End Time"));
	$tpl->assign("dtm_author", _("Author"));
	$tpl->assign("dtm_comment", _("Comments"));
	$tpl->assign("dtm_fixed", _("Fixed"));
	$tpl->assign("dtm_duration", _("Duration"));
	$tpl->assign("dtm_started", _("Started"));
	$tpl->assign("dtm_host_downtime", _("Hosts Downtimes"));
	$tpl->assign("dtm_host_cancelled", _("Cancelled"));
	$tpl->assign("dtm_host_actual_end", _("Actual End"));

	$tpl->assign("secondes", _("s"));

	$tpl->assign("no_host_dtm", _("No downtime scheduled for hosts"));
	$tpl->assign("view_svc_dtm", _("View downtimes of services"));
	$tpl->assign("svc_dtm_link", "./main.php?p=".$p."&o=vs");
	$tpl->assign("limit", $limit);
	$tpl->assign("cancel", _("Cancel"));
	$tpl->assign("delete", _("Delete"));

	$tpl->assign("Host", _("Host Name"));
	$tpl->assign("Output", _("Output"));
	$tpl->assign("user", _("Users"));
	$tpl->assign('Hostgroup', _("Hostgroup"));
	$tpl->assign('Search', _("Search"));
	$tpl->assign("ViewAll", _("Display Finished Downtime"));
	$tpl->assign("ViewDowntimeCycle", _("Display Downtime Cycle"));
	$tpl->assign("Author", _("Author"));
	$tpl->assign("search_output", $search_output);
	$tpl->assign('search_host', $host_name);
	$tpl->assign('view_all', $view_all);
	$tpl->assign('view_downtime_cycle', $view_downtime_cycle);
	$tpl->assign('search_author', $search_author);

	/**
	 * Get Hostgroups
	 */
	if ($oreon->broker->getBroker() == "ndo") {
		$DBRESULT = $pearDBndo->query("SELECT hostgroup_id, alias FROM ".$ndo_base_prefix."hostgroups ORDER BY alias");
		$options = "<option value='0'></options>";
		while ($data = $DBRESULT->fetchRow()) {
	        $options .= "<option value='".$data["hostgroup_id"]."' ".(($hostgroup == $data["hostgroup_id"]) ? 'selected' : "").">".$data["alias"]."</option>";
	    }
	    $DBRESULT->free();
	} else {
		$DBRESULT = $pearDBO->query("SELECT hostgroup_id, name FROM hostgroups ORDER BY name");
		$options = "<option value='0'></options>";
		while ($data = $DBRESULT->fetchRow()) {
	        $options .= "<option value='".$data["hostgroup_id"]."' ".(($hostgroup == $data["hostgroup_id"]) ? 'selected' : "").">".$data["name"]."</option>";
	    }
	    $DBRESULT->free();
	}

	$tpl->assign('hostgroup', $options);
	unset($options);

	$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->display("hostDowntime.ihtml");
?>
<script type='text/javascript'>
var msgArr = new Array();
msgArr['ch'] = '<?php echo addslashes(_("Do you confirm the cancellation ?")); ?>';
msgArr['dh'] = '<?php echo addslashes(_("Do you confirm the deletion ?")); ?>';

function doAction(slt, act) {
	if (confirm(msgArr[act])) {
            jQuery('input[name=o]').attr('value', act);
            document.form.submit();
	} else {
            slt.value = 0;
	}
}
</script>