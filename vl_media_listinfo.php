<?php

// Global variable for table object
$vl_media_list = NULL;

//
// Table class for vl_media_list
//
class cvl_media_list extends cTable {
	var $vl_media_id;
	var $vl_media_type;
	var $vl_media_name;
	var $vl_media_file;
	var $vl_media_file_custom;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vl_media_list';
		$this->TableName = 'vl_media_list';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`vl_media_list`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// vl_media_id
		$this->vl_media_id = new cField('vl_media_list', 'vl_media_list', 'x_vl_media_id', 'vl_media_id', '`vl_media_id`', '`vl_media_id`', 3, -1, FALSE, '`vl_media_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->vl_media_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vl_media_id'] = &$this->vl_media_id;

		// vl_media_type
		$this->vl_media_type = new cField('vl_media_list', 'vl_media_list', 'x_vl_media_type', 'vl_media_type', '`vl_media_type`', '`vl_media_type`', 3, -1, FALSE, '`vl_media_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->vl_media_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vl_media_type'] = &$this->vl_media_type;

		// vl_media_name
		$this->vl_media_name = new cField('vl_media_list', 'vl_media_list', 'x_vl_media_name', 'vl_media_name', '`vl_media_name`', '`vl_media_name`', 200, -1, FALSE, '`vl_media_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['vl_media_name'] = &$this->vl_media_name;

		// vl_media_file
		$this->vl_media_file = new cField('vl_media_list', 'vl_media_list', 'x_vl_media_file', 'vl_media_file', '`vl_media_file`', '`vl_media_file`', 200, -1, TRUE, '`vl_media_file`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['vl_media_file'] = &$this->vl_media_file;

		// vl_media_file_custom
		$this->vl_media_file_custom = new cField('vl_media_list', 'vl_media_list', 'x_vl_media_file_custom', 'vl_media_file_custom', '`vl_media_file_custom`', '`vl_media_file_custom`', 200, -1, TRUE, '`vl_media_file_custom`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['vl_media_file_custom'] = &$this->vl_media_file_custom;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`vl_media_list`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('vl_media_id', $rs))
				ew_AddFilter($where, ew_QuotedName('vl_media_id', $this->DBID) . '=' . ew_QuotedValue($rs['vl_media_id'], $this->vl_media_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`vl_media_id` = @vl_media_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->vl_media_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@vl_media_id@", ew_AdjustSql($this->vl_media_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "vl_media_listlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vl_media_listlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("vl_media_listview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("vl_media_listview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "vl_media_listadd.php?" . $this->UrlParm($parm);
		else
			$url = "vl_media_listadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("vl_media_listedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("vl_media_listadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vl_media_listdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "vl_media_id:" . ew_VarToJson($this->vl_media_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->vl_media_id->CurrentValue)) {
			$sUrl .= "vl_media_id=" . urlencode($this->vl_media_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["vl_media_id"]))
				$arKeys[] = ew_StripSlashes($_POST["vl_media_id"]);
			elseif (isset($_GET["vl_media_id"]))
				$arKeys[] = ew_StripSlashes($_GET["vl_media_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->vl_media_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->vl_media_id->setDbValue($rs->fields('vl_media_id'));
		$this->vl_media_type->setDbValue($rs->fields('vl_media_type'));
		$this->vl_media_name->setDbValue($rs->fields('vl_media_name'));
		$this->vl_media_file->Upload->DbValue = $rs->fields('vl_media_file');
		$this->vl_media_file_custom->Upload->DbValue = $rs->fields('vl_media_file_custom');
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// vl_media_id
		// vl_media_type
		// vl_media_name
		// vl_media_file
		// vl_media_file_custom
		// vl_media_id

		$this->vl_media_id->ViewValue = $this->vl_media_id->CurrentValue;
		$this->vl_media_id->ViewCustomAttributes = "";

		// vl_media_type
		if (strval($this->vl_media_type->CurrentValue) <> "") {
			$sFilterWrk = "`vl_media_type_id`" . ew_SearchString("=", $this->vl_media_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `vl_media_type_id`, `vl_media_type_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vl_media_type_list`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->vl_media_type, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `vl_media_type_name` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->vl_media_type->ViewValue = $this->vl_media_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->vl_media_type->ViewValue = $this->vl_media_type->CurrentValue;
			}
		} else {
			$this->vl_media_type->ViewValue = NULL;
		}
		$this->vl_media_type->ViewCustomAttributes = "";

		// vl_media_name
		$this->vl_media_name->ViewValue = $this->vl_media_name->CurrentValue;
		$this->vl_media_name->ViewCustomAttributes = "";

		// vl_media_file
		if (!ew_Empty($this->vl_media_file->Upload->DbValue)) {
			$this->vl_media_file->ImageWidth = 200;
			$this->vl_media_file->ImageHeight = 200;
			$this->vl_media_file->ImageAlt = $this->vl_media_file->FldAlt();
			$this->vl_media_file->ViewValue = $this->vl_media_file->Upload->DbValue;
		} else {
			$this->vl_media_file->ViewValue = "";
		}
		$this->vl_media_file->ViewCustomAttributes = "";

		// vl_media_file_custom
		$this->vl_media_file_custom->UploadPath = "uploads_custom/";
		if (!ew_Empty($this->vl_media_file_custom->Upload->DbValue)) {
			$this->vl_media_file_custom->ImageWidth = 200;
			$this->vl_media_file_custom->ImageHeight = 200;
			$this->vl_media_file_custom->ImageAlt = $this->vl_media_file_custom->FldAlt();
			$this->vl_media_file_custom->ViewValue = $this->vl_media_file_custom->Upload->DbValue;
		} else {
			$this->vl_media_file_custom->ViewValue = "";
		}
		$this->vl_media_file_custom->ViewCustomAttributes = "";

		// vl_media_id
		$this->vl_media_id->LinkCustomAttributes = "";
		$this->vl_media_id->HrefValue = "";
		$this->vl_media_id->TooltipValue = "";

		// vl_media_type
		$this->vl_media_type->LinkCustomAttributes = "";
		$this->vl_media_type->HrefValue = "";
		$this->vl_media_type->TooltipValue = "";

		// vl_media_name
		$this->vl_media_name->LinkCustomAttributes = "";
		$this->vl_media_name->HrefValue = "";
		$this->vl_media_name->TooltipValue = "";

		// vl_media_file
		$this->vl_media_file->LinkCustomAttributes = "";
		if (!ew_Empty($this->vl_media_file->Upload->DbValue)) {
			$this->vl_media_file->HrefValue = ew_GetFileUploadUrl($this->vl_media_file, $this->vl_media_file->Upload->DbValue); // Add prefix/suffix
			$this->vl_media_file->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->vl_media_file->HrefValue = ew_ConvertFullUrl($this->vl_media_file->HrefValue);
		} else {
			$this->vl_media_file->HrefValue = "";
		}
		$this->vl_media_file->HrefValue2 = $this->vl_media_file->UploadPath . $this->vl_media_file->Upload->DbValue;
		$this->vl_media_file->TooltipValue = "";
		if ($this->vl_media_file->UseColorbox) {
			if (ew_Empty($this->vl_media_file->TooltipValue))
				$this->vl_media_file->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->vl_media_file->LinkAttrs["data-rel"] = "vl_media_list_x_vl_media_file";
			ew_AppendClass($this->vl_media_file->LinkAttrs["class"], "ewLightbox");
		}

		// vl_media_file_custom
		$this->vl_media_file_custom->LinkCustomAttributes = "";
		$this->vl_media_file_custom->UploadPath = "uploads_custom/";
		if (!ew_Empty($this->vl_media_file_custom->Upload->DbValue)) {
			$this->vl_media_file_custom->HrefValue = ew_GetFileUploadUrl($this->vl_media_file_custom, $this->vl_media_file_custom->Upload->DbValue); // Add prefix/suffix
			$this->vl_media_file_custom->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->vl_media_file_custom->HrefValue = ew_ConvertFullUrl($this->vl_media_file_custom->HrefValue);
		} else {
			$this->vl_media_file_custom->HrefValue = "";
		}
		$this->vl_media_file_custom->HrefValue2 = $this->vl_media_file_custom->UploadPath . $this->vl_media_file_custom->Upload->DbValue;
		$this->vl_media_file_custom->TooltipValue = "";
		if ($this->vl_media_file_custom->UseColorbox) {
			if (ew_Empty($this->vl_media_file_custom->TooltipValue))
				$this->vl_media_file_custom->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->vl_media_file_custom->LinkAttrs["data-rel"] = "vl_media_list_x_vl_media_file_custom";
			ew_AppendClass($this->vl_media_file_custom->LinkAttrs["class"], "ewLightbox");
		}

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// vl_media_id
		$this->vl_media_id->EditAttrs["class"] = "form-control";
		$this->vl_media_id->EditCustomAttributes = "";
		$this->vl_media_id->EditValue = $this->vl_media_id->CurrentValue;
		$this->vl_media_id->ViewCustomAttributes = "";

		// vl_media_type
		$this->vl_media_type->EditAttrs["class"] = "form-control";
		$this->vl_media_type->EditCustomAttributes = "";

		// vl_media_name
		$this->vl_media_name->EditAttrs["class"] = "form-control";
		$this->vl_media_name->EditCustomAttributes = "";
		$this->vl_media_name->EditValue = $this->vl_media_name->CurrentValue;

		// vl_media_file
		$this->vl_media_file->EditAttrs["class"] = "form-control";
		$this->vl_media_file->EditCustomAttributes = "";
		if (!ew_Empty($this->vl_media_file->Upload->DbValue)) {
			$this->vl_media_file->ImageWidth = 200;
			$this->vl_media_file->ImageHeight = 200;
			$this->vl_media_file->ImageAlt = $this->vl_media_file->FldAlt();
			$this->vl_media_file->EditValue = $this->vl_media_file->Upload->DbValue;
		} else {
			$this->vl_media_file->EditValue = "";
		}
		if (!ew_Empty($this->vl_media_file->CurrentValue))
			$this->vl_media_file->Upload->FileName = $this->vl_media_file->CurrentValue;

		// vl_media_file_custom
		$this->vl_media_file_custom->EditAttrs["class"] = "form-control";
		$this->vl_media_file_custom->EditCustomAttributes = "";
		$this->vl_media_file_custom->UploadPath = "uploads_custom/";
		if (!ew_Empty($this->vl_media_file_custom->Upload->DbValue)) {
			$this->vl_media_file_custom->ImageWidth = 200;
			$this->vl_media_file_custom->ImageHeight = 200;
			$this->vl_media_file_custom->ImageAlt = $this->vl_media_file_custom->FldAlt();
			$this->vl_media_file_custom->EditValue = $this->vl_media_file_custom->Upload->DbValue;
		} else {
			$this->vl_media_file_custom->EditValue = "";
		}
		if (!ew_Empty($this->vl_media_file_custom->CurrentValue))
			$this->vl_media_file_custom->Upload->FileName = $this->vl_media_file_custom->CurrentValue;

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->vl_media_id->Exportable) $Doc->ExportCaption($this->vl_media_id);
					if ($this->vl_media_type->Exportable) $Doc->ExportCaption($this->vl_media_type);
					if ($this->vl_media_name->Exportable) $Doc->ExportCaption($this->vl_media_name);
					if ($this->vl_media_file->Exportable) $Doc->ExportCaption($this->vl_media_file);
					if ($this->vl_media_file_custom->Exportable) $Doc->ExportCaption($this->vl_media_file_custom);
				} else {
					if ($this->vl_media_id->Exportable) $Doc->ExportCaption($this->vl_media_id);
					if ($this->vl_media_type->Exportable) $Doc->ExportCaption($this->vl_media_type);
					if ($this->vl_media_name->Exportable) $Doc->ExportCaption($this->vl_media_name);
					if ($this->vl_media_file->Exportable) $Doc->ExportCaption($this->vl_media_file);
					if ($this->vl_media_file_custom->Exportable) $Doc->ExportCaption($this->vl_media_file_custom);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->vl_media_id->Exportable) $Doc->ExportField($this->vl_media_id);
						if ($this->vl_media_type->Exportable) $Doc->ExportField($this->vl_media_type);
						if ($this->vl_media_name->Exportable) $Doc->ExportField($this->vl_media_name);
						if ($this->vl_media_file->Exportable) $Doc->ExportField($this->vl_media_file);
						if ($this->vl_media_file_custom->Exportable) $Doc->ExportField($this->vl_media_file_custom);
					} else {
						if ($this->vl_media_id->Exportable) $Doc->ExportField($this->vl_media_id);
						if ($this->vl_media_type->Exportable) $Doc->ExportField($this->vl_media_type);
						if ($this->vl_media_name->Exportable) $Doc->ExportField($this->vl_media_name);
						if ($this->vl_media_file->Exportable) $Doc->ExportField($this->vl_media_file);
						if ($this->vl_media_file_custom->Exportable) $Doc->ExportField($this->vl_media_file_custom);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
