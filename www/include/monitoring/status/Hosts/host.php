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

	/*
	 * ACL Actions
	 */
	$GroupListofUser = array();
	$GroupListofUser =  $oreon->user->access->getAccessGroups();

	$allActions = false;
	/*
	 * Get list of actions allowed for user
	 */
	if (count($GroupListofUser) > 0 && $is_admin == 0) {
		$authorized_actions = array();
		$authorized_actions = $oreon->user->access->getActions();
	} else {
	 	/*
	 	 * if user is admin, or without ACL, he cans perform all actions
	 	 */
		$allActions = true;
	}

	include("./include/common/autoNumLimit.php");

	!isset($_GET["sort_types"]) ? $sort_types = 0 : $sort_types = $_GET["sort_types"];
	!isset($_GET["order"]) ? $order = 'ASC' : $order = $_GET["order"];
	!isset($_GET["num"]) ? $num = 0 : $num = $_GET["num"];
	!isset($_GET["host_search"]) ? $search_host = "" : $search_host = $_GET["host_search"];
	!isset($_GET["sort_type"]) ? $sort_type = "" : $sort_type = $_GET["sort_type"];
    
    
    if ($o == "hpb" || $o == "h_unhandled") {
        if (!isset($_GET["sort_type"])) {
            $sort_type = $oreon->optGen["problem_sort_type"];
        } else {
            $sort_type = $_GET["sort_type"];
        }
        if (!isset($_GET["order"])) {
            $order = $oreon->optGen["problem_sort_order"];
        } else {
            $order = $_GET["order"];
        }
    } else {
        if (!isset($_GET["sort_type"])) {
            if (isset($_SESSION['centreon']->optGen["global_sort_type"]) && $_SESSION['centreon']->optGen["global_sort_type"] != "host_name") {
                $sort_type = CentreonDB::escape($_SESSION['centreon']->optGen["global_sort_type"]);
            } else {
                $sort_type = "host_name";
            }
        } else {
            $sort_type = $_GET["sort_type"];
        }
        if (!isset($_GET["order"])) {
            if (isset($_SESSION['centreon']->optGen["global_sort_order"]) && $_SESSION['centreon']->optGen["global_sort_order"] == "") {
                $order = "ASC";
            } else {
                $order = $_SESSION['centreon']->optGen["global_sort_order"];
            }
        } else {
            $order = $_GET["order"];
        }
    }

	/*
	 * Check search value in Host search field
	 */
	if (isset($_GET["host_search"])) {
		$centreon->historySearch[$url] = $_GET["host_search"];
	}

	$tab_class = array("0" => "list_one", "1" => "list_two");
	$rows = 10;

	include_once("./include/monitoring/status/Common/default_poller.php");
	include_once("./include/monitoring/status/Common/default_hostgroups.php");

	include_once("hostJS.php");

	/*
	 *  Smarty template Init
	 */
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl, "/templates/");

	$tpl->assign("p", $p);
	$tpl->assign('o', $o);
	$tpl->assign("sort_type", $sort_type);
	$tpl->assign("num", $num);
	$tpl->assign("limit", $limit);
	$tpl->assign("mon_host", _("Hosts"));
	$tpl->assign("mon_status", _("Status"));
	$tpl->assign("mon_ip", _("IP"));
	$tpl->assign("mon_tries", _("Tries"));
	$tpl->assign("mon_last_check", _("Last Check"));
	$tpl->assign("mon_duration", _("Duration"));
	$tpl->assign("mon_status_information", _("Status information"));


	$form = new HTML_QuickForm('select_form', 'GET', "?p=".$p);

	$tpl->assign("order", strtolower($order));
	$tab_order = array("sort_asc" => "sort_desc", "sort_desc" => "sort_asc");
	$tpl->assign("tab_order", $tab_order);

	?>
	<script type="text/javascript">
	function setO(_i) {
		document.forms['form'].elements['cmd'].value = _i;
		document.forms['form'].elements['o1'].selectedIndex = 0;
		document.forms['form'].elements['o2'].selectedIndex = 0;
	}
	</SCRIPT>
	<?php

	$action_list = array();
	$action_list[]	=	_("More actions...");

	/*
	 * Showing actions allowed for current user
	 */
	if(isset($authorized_actions) && $allActions == false){
		foreach($authorized_actions as $action_name) {
			if($action_name == "host_acknowledgement")
				$action_list[72] = _("Hosts : Acknowledge");
			if($action_name == "host_disacknowledgement")
				$action_list[73] = _("Hosts : Disacknowledge");
			if($action_name == "host_notifications")
				$action_list[82] = _("Hosts : Enable Notification");
			if($action_name == "host_notifications")
				$action_list[83] = _("Hosts : Disable Notification");
			if($action_name == "host_checks")
				$action_list[92] = _("Hosts : Enable Check");
			if($action_name == "host_checks")
				$action_list[93] = _("Hosts : Disable Check");
            if($action_name == "host_schedule_downtime")
				$action_list[75] = _("Hosts : Set Downtime");
		}
	} else {
		$action_list[72] = _("Hosts : Acknowledge");
		$action_list[73] = _("Hosts : Disacknowledge");
		$action_list[82] = _("Hosts : Enable Notification");
		$action_list[83] = _("Hosts : Disable Notification");
		$action_list[92] = _("Hosts : Enable Check");
		$action_list[93] = _("Hosts : Disable Check");
		$action_list[75] = _("Hosts : Set Downtime");
	}

	$attrs = array(	'onchange'=>"javascript: if (cmdCallback(this.value)) { setO(this.value); submit();} else { setO(this.value); }");
    $form->addElement('select', 'o1', NULL, $action_list, $attrs);
	$form->setDefaults(array('o1' => NULL));
	$o1 = $form->getElement('o1');
	$o1->setValue(NULL);

	$attrs = array( 'onchange'=>"javascript: if (cmdCallback(this.value)) { setO(this.value); submit();} else { setO(this.value); }");
    $form->addElement('select', 'o2', NULL, $action_list, $attrs);
	$form->setDefaults(array('o2' => NULL));
	$o2 = $form->getElement('o2');
	$o2->setValue(NULL);
	$o2->setSelected(NULL);

	$keyPrefix = "";
	$statusList = array("" => "",
	                    "up" => _("Up"),
	                    "down" => _("Down"),
	                    "unreachable" => _("Unreachable"),
	                    "pending" => _("Pending"));
	if ($o == "h") {
	    $keyPrefix = "h";
	} elseif ($o == "hpb") {
        $keyPrefix = "h";
        unset($statusList["up"]);
	} elseif ($o == "h_unhandled") {
	    $keyPrefix = "h_unhandled";
	    unset($statusList["up"]);
            unset($statusList["pending"]);
	} elseif (preg_match("/h_([a-z]+)/", $o, $matches)) {
	    if (isset($matches[1])) {
            $keyPrefix = "h";
            $defaultStatus = $matches[1];
        }
	}

	$form->addElement('select', 'statusFilter', _('Status'), $statusList, array('id' => 'statusFilter', 'onChange' => "filterStatus(this.value);"));
        if (isset($defaultStatus)) {
            $form->setDefaults(array('statusFilter' => $defaultStatus));
        }
        
        $criticality = new CentreonCriticality($pearDB);
        $crits = $criticality->getList();
        $critArray = array(0 => "");
        foreach($crits as $critId => $crit) {
            $critArray[$critId] = $crit['hc_name']. " ({$crit['level']})";
        }
        $form->addElement('select', 'criticality', _('Severity'), $critArray, array('id' => 'critFilter', 'onChange' => "filterCrit(this.value);"));
        $form->setDefaults(array('criticality' => isset($_SESSION['criticality_id']) ? $_SESSION['criticality_id'] : "0"));

	$tpl->assign('limit', $limit);
	$tpl->assign('hostStr', _('Host'));
	$tpl->assign('pollerStr', _('Poller'));
	$tpl->assign('poller_listing', $oreon->user->access->checkAction('poller_listing'));
	$tpl->assign('hgStr', _('Hostgroup'));
        $criticality = new CentreonCriticality($pearDB);
        $tpl->assign('criticalityUsed', count($criticality->getList()));
	$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->display("host.ihtml");
?>
<script type='text/javascript'>
var _keyPrefix;
var _originalo = '<?php echo $o;?>';

jQuery(function() {
    preInit();
});

function preInit()
{
	_keyPrefix = '<?php echo $keyPrefix;?>';
	_sid = '<?php echo $sid?>';
	_tm = <?php echo $tM?>;
	filterStatus(document.getElementById('statusFilter').value, 1);
}

function filterStatus(value, isInit)
{
	_o = _originalo;
        if (value) {
		_o = _keyPrefix + '_' + value;
	} else if (!isInit && _o != 'hpb'){
		_o = _keyPrefix;
	}
	window.clearTimeout(_timeoutID);
	initM(_tm, _sid, _o);
}

function filterCrit(value) {
    window.clearTimeout(_timeoutID);
    initM(_tm, _sid, _o);
}
</script>
