<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "vl_media_listinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$vl_media_list_delete = NULL; // Initialize page object first

class cvl_media_list_delete extends cvl_media_list {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{64847E68-7377-4FEE-94E6-69A0E84D0306}";

	// Table name
	var $TableName = 'vl_media_list';

	// Page object name
	var $PageObjName = 'vl_media_list_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (vl_media_list)
		if (!isset($GLOBALS["vl_media_list"]) || get_class($GLOBALS["vl_media_list"]) == "cvl_media_list") {
			$GLOBALS["vl_media_list"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vl_media_list"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vl_media_list', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->vl_media_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $vl_media_list;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vl_media_list);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("vl_media_listlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in vl_media_list class, vl_media_listinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->vl_media_id->setDbValue($rs->fields('vl_media_id'));
		$this->vl_media_type->setDbValue($rs->fields('vl_media_type'));
		$this->vl_media_name->setDbValue($rs->fields('vl_media_name'));
		$this->vl_media_file->Upload->DbValue = $rs->fields('vl_media_file');
		$this->vl_media_file->CurrentValue = $this->vl_media_file->Upload->DbValue;
		$this->vl_media_file_custom->Upload->DbValue = $rs->fields('vl_media_file_custom');
		$this->vl_media_file_custom->CurrentValue = $this->vl_media_file_custom->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->vl_media_id->DbValue = $row['vl_media_id'];
		$this->vl_media_type->DbValue = $row['vl_media_type'];
		$this->vl_media_name->DbValue = $row['vl_media_name'];
		$this->vl_media_file->Upload->DbValue = $row['vl_media_file'];
		$this->vl_media_file_custom->Upload->DbValue = $row['vl_media_file_custom'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// vl_media_id
		// vl_media_type
		// vl_media_name
		// vl_media_file
		// vl_media_file_custom

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['vl_media_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("vl_media_listlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vl_media_list_delete)) $vl_media_list_delete = new cvl_media_list_delete();

// Page init
$vl_media_list_delete->Page_Init();

// Page main
$vl_media_list_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vl_media_list_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fvl_media_listdelete = new ew_Form("fvl_media_listdelete", "delete");

// Form_CustomValidate event
fvl_media_listdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvl_media_listdelete.ValidateRequired = true;
<?php } else { ?>
fvl_media_listdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvl_media_listdelete.Lists["x_vl_media_type"] = {"LinkField":"x_vl_media_type_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vl_media_type_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($vl_media_list_delete->Recordset = $vl_media_list_delete->LoadRecordset())
	$vl_media_list_deleteTotalRecs = $vl_media_list_delete->Recordset->RecordCount(); // Get record count
if ($vl_media_list_deleteTotalRecs <= 0) { // No record found, exit
	if ($vl_media_list_delete->Recordset)
		$vl_media_list_delete->Recordset->Close();
	$vl_media_list_delete->Page_Terminate("vl_media_listlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $vl_media_list_delete->ShowPageHeader(); ?>
<?php
$vl_media_list_delete->ShowMessage();
?>
<form name="fvl_media_listdelete" id="fvl_media_listdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vl_media_list_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vl_media_list_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vl_media_list">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($vl_media_list_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $vl_media_list->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($vl_media_list->vl_media_id->Visible) { // vl_media_id ?>
		<th><span id="elh_vl_media_list_vl_media_id" class="vl_media_list_vl_media_id"><?php echo $vl_media_list->vl_media_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vl_media_list->vl_media_type->Visible) { // vl_media_type ?>
		<th><span id="elh_vl_media_list_vl_media_type" class="vl_media_list_vl_media_type"><?php echo $vl_media_list->vl_media_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vl_media_list->vl_media_name->Visible) { // vl_media_name ?>
		<th><span id="elh_vl_media_list_vl_media_name" class="vl_media_list_vl_media_name"><?php echo $vl_media_list->vl_media_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vl_media_list->vl_media_file->Visible) { // vl_media_file ?>
		<th><span id="elh_vl_media_list_vl_media_file" class="vl_media_list_vl_media_file"><?php echo $vl_media_list->vl_media_file->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vl_media_list->vl_media_file_custom->Visible) { // vl_media_file_custom ?>
		<th><span id="elh_vl_media_list_vl_media_file_custom" class="vl_media_list_vl_media_file_custom"><?php echo $vl_media_list->vl_media_file_custom->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$vl_media_list_delete->RecCnt = 0;
$i = 0;
while (!$vl_media_list_delete->Recordset->EOF) {
	$vl_media_list_delete->RecCnt++;
	$vl_media_list_delete->RowCnt++;

	// Set row properties
	$vl_media_list->ResetAttrs();
	$vl_media_list->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$vl_media_list_delete->LoadRowValues($vl_media_list_delete->Recordset);

	// Render row
	$vl_media_list_delete->RenderRow();
?>
	<tr<?php echo $vl_media_list->RowAttributes() ?>>
<?php if ($vl_media_list->vl_media_id->Visible) { // vl_media_id ?>
		<td<?php echo $vl_media_list->vl_media_id->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_delete->RowCnt ?>_vl_media_list_vl_media_id" class="vl_media_list_vl_media_id">
<span<?php echo $vl_media_list->vl_media_id->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vl_media_list->vl_media_type->Visible) { // vl_media_type ?>
		<td<?php echo $vl_media_list->vl_media_type->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_delete->RowCnt ?>_vl_media_list_vl_media_type" class="vl_media_list_vl_media_type">
<span<?php echo $vl_media_list->vl_media_type->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vl_media_list->vl_media_name->Visible) { // vl_media_name ?>
		<td<?php echo $vl_media_list->vl_media_name->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_delete->RowCnt ?>_vl_media_list_vl_media_name" class="vl_media_list_vl_media_name">
<span<?php echo $vl_media_list->vl_media_name->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vl_media_list->vl_media_file->Visible) { // vl_media_file ?>
		<td<?php echo $vl_media_list->vl_media_file->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_delete->RowCnt ?>_vl_media_list_vl_media_file" class="vl_media_list_vl_media_file">
<span>
<?php echo ew_GetFileViewTag($vl_media_list->vl_media_file, $vl_media_list->vl_media_file->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($vl_media_list->vl_media_file_custom->Visible) { // vl_media_file_custom ?>
		<td<?php echo $vl_media_list->vl_media_file_custom->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_delete->RowCnt ?>_vl_media_list_vl_media_file_custom" class="vl_media_list_vl_media_file_custom">
<span>
<?php echo ew_GetFileViewTag($vl_media_list->vl_media_file_custom, $vl_media_list->vl_media_file_custom->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$vl_media_list_delete->Recordset->MoveNext();
}
$vl_media_list_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $vl_media_list_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fvl_media_listdelete.Init();
</script>
<?php
$vl_media_list_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vl_media_list_delete->Page_Terminate();
?>
