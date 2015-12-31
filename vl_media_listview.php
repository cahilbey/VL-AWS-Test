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

$vl_media_list_view = NULL; // Initialize page object first

class cvl_media_list_view extends cvl_media_list {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{64847E68-7377-4FEE-94E6-69A0E84D0306}";

	// Table name
	var $TableName = 'vl_media_list';

	// Page object name
	var $PageObjName = 'vl_media_list_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["vl_media_id"] <> "") {
			$this->RecKey["vl_media_id"] = $_GET["vl_media_id"];
			$KeyUrl .= "&amp;vl_media_id=" . urlencode($this->RecKey["vl_media_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vl_media_list', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["vl_media_id"] <> "") {
				$this->vl_media_id->setQueryStringValue($_GET["vl_media_id"]);
				$this->RecKey["vl_media_id"] = $this->vl_media_id->QueryStringValue;
			} elseif (@$_POST["vl_media_id"] <> "") {
				$this->vl_media_id->setFormValue($_POST["vl_media_id"]);
				$this->RecKey["vl_media_id"] = $this->vl_media_id->FormValue;
			} else {
				$sReturnUrl = "vl_media_listlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "vl_media_listlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "vl_media_listlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("vl_media_listlist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vl_media_list_view)) $vl_media_list_view = new cvl_media_list_view();

// Page init
$vl_media_list_view->Page_Init();

// Page main
$vl_media_list_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vl_media_list_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fvl_media_listview = new ew_Form("fvl_media_listview", "view");

// Form_CustomValidate event
fvl_media_listview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvl_media_listview.ValidateRequired = true;
<?php } else { ?>
fvl_media_listview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvl_media_listview.Lists["x_vl_media_type"] = {"LinkField":"x_vl_media_type_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vl_media_type_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $vl_media_list_view->ExportOptions->Render("body") ?>
<?php
	foreach ($vl_media_list_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $vl_media_list_view->ShowPageHeader(); ?>
<?php
$vl_media_list_view->ShowMessage();
?>
<form name="fvl_media_listview" id="fvl_media_listview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vl_media_list_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vl_media_list_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vl_media_list">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($vl_media_list->vl_media_id->Visible) { // vl_media_id ?>
	<tr id="r_vl_media_id">
		<td><span id="elh_vl_media_list_vl_media_id"><?php echo $vl_media_list->vl_media_id->FldCaption() ?></span></td>
		<td data-name="vl_media_id"<?php echo $vl_media_list->vl_media_id->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_id">
<span<?php echo $vl_media_list->vl_media_id->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($vl_media_list->vl_media_type->Visible) { // vl_media_type ?>
	<tr id="r_vl_media_type">
		<td><span id="elh_vl_media_list_vl_media_type"><?php echo $vl_media_list->vl_media_type->FldCaption() ?></span></td>
		<td data-name="vl_media_type"<?php echo $vl_media_list->vl_media_type->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_type">
<span<?php echo $vl_media_list->vl_media_type->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($vl_media_list->vl_media_name->Visible) { // vl_media_name ?>
	<tr id="r_vl_media_name">
		<td><span id="elh_vl_media_list_vl_media_name"><?php echo $vl_media_list->vl_media_name->FldCaption() ?></span></td>
		<td data-name="vl_media_name"<?php echo $vl_media_list->vl_media_name->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_name">
<span<?php echo $vl_media_list->vl_media_name->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($vl_media_list->vl_media_file->Visible) { // vl_media_file ?>
	<tr id="r_vl_media_file">
		<td><span id="elh_vl_media_list_vl_media_file"><?php echo $vl_media_list->vl_media_file->FldCaption() ?></span></td>
		<td data-name="vl_media_file"<?php echo $vl_media_list->vl_media_file->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_file">
<span>
<?php echo ew_GetFileViewTag($vl_media_list->vl_media_file, $vl_media_list->vl_media_file->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($vl_media_list->vl_media_file_custom->Visible) { // vl_media_file_custom ?>
	<tr id="r_vl_media_file_custom">
		<td><span id="elh_vl_media_list_vl_media_file_custom"><?php echo $vl_media_list->vl_media_file_custom->FldCaption() ?></span></td>
		<td data-name="vl_media_file_custom"<?php echo $vl_media_list->vl_media_file_custom->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_file_custom">
<span>
<?php echo ew_GetFileViewTag($vl_media_list->vl_media_file_custom, $vl_media_list->vl_media_file_custom->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fvl_media_listview.Init();
</script>
<?php
$vl_media_list_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vl_media_list_view->Page_Terminate();
?>
