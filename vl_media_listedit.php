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

$vl_media_list_edit = NULL; // Initialize page object first

class cvl_media_list_edit extends cvl_media_list {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{64847E68-7377-4FEE-94E6-69A0E84D0306}";

	// Table name
	var $TableName = 'vl_media_list';

	// Page object name
	var $PageObjName = 'vl_media_list_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["vl_media_id"] <> "") {
			$this->vl_media_id->setQueryStringValue($_GET["vl_media_id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->vl_media_id->CurrentValue == "")
			$this->Page_Terminate("vl_media_listlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("vl_media_listlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "vl_media_listlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->vl_media_file->Upload->Index = $objForm->Index;
		$this->vl_media_file->Upload->UploadFile();
		$this->vl_media_file->CurrentValue = $this->vl_media_file->Upload->FileName;
		$this->vl_media_file_custom->Upload->Index = $objForm->Index;
		$this->vl_media_file_custom->Upload->UploadFile();
		$this->vl_media_file_custom->CurrentValue = $this->vl_media_file_custom->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->vl_media_id->FldIsDetailKey)
			$this->vl_media_id->setFormValue($objForm->GetValue("x_vl_media_id"));
		if (!$this->vl_media_type->FldIsDetailKey) {
			$this->vl_media_type->setFormValue($objForm->GetValue("x_vl_media_type"));
		}
		if (!$this->vl_media_name->FldIsDetailKey) {
			$this->vl_media_name->setFormValue($objForm->GetValue("x_vl_media_name"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->vl_media_id->CurrentValue = $this->vl_media_id->FormValue;
		$this->vl_media_type->CurrentValue = $this->vl_media_type->FormValue;
		$this->vl_media_name->CurrentValue = $this->vl_media_name->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// vl_media_id
			$this->vl_media_id->EditAttrs["class"] = "form-control";
			$this->vl_media_id->EditCustomAttributes = "";
			$this->vl_media_id->EditValue = $this->vl_media_id->CurrentValue;
			$this->vl_media_id->ViewCustomAttributes = "";

			// vl_media_type
			$this->vl_media_type->EditAttrs["class"] = "form-control";
			$this->vl_media_type->EditCustomAttributes = "";
			if (trim(strval($this->vl_media_type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`vl_media_type_id`" . ew_SearchString("=", $this->vl_media_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `vl_media_type_id`, `vl_media_type_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `vl_media_type_list`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->vl_media_type, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `vl_media_type_name` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->vl_media_type->EditValue = $arwrk;

			// vl_media_name
			$this->vl_media_name->EditAttrs["class"] = "form-control";
			$this->vl_media_name->EditCustomAttributes = "";
			$this->vl_media_name->EditValue = ew_HtmlEncode($this->vl_media_name->CurrentValue);

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->vl_media_file);

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->vl_media_file_custom);

			// Edit refer script
			// vl_media_id

			$this->vl_media_id->LinkCustomAttributes = "";
			$this->vl_media_id->HrefValue = "";

			// vl_media_type
			$this->vl_media_type->LinkCustomAttributes = "";
			$this->vl_media_type->HrefValue = "";

			// vl_media_name
			$this->vl_media_name->LinkCustomAttributes = "";
			$this->vl_media_name->HrefValue = "";

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
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->vl_media_name->FldIsDetailKey && !is_null($this->vl_media_name->FormValue) && $this->vl_media_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vl_media_name->FldCaption(), $this->vl_media_name->ReqErrMsg));
		}
		if ($this->vl_media_file->Upload->FileName == "" && !$this->vl_media_file->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vl_media_file->FldCaption(), $this->vl_media_file->ReqErrMsg));
		}
		if ($this->vl_media_file_custom->Upload->FileName == "" && !$this->vl_media_file_custom->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vl_media_file_custom->FldCaption(), $this->vl_media_file_custom->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->vl_media_file_custom->OldUploadPath = "uploads_custom/";
			$this->vl_media_file_custom->UploadPath = $this->vl_media_file_custom->OldUploadPath;
			$rsnew = array();

			// vl_media_type
			$this->vl_media_type->SetDbValueDef($rsnew, $this->vl_media_type->CurrentValue, NULL, $this->vl_media_type->ReadOnly);

			// vl_media_name
			$this->vl_media_name->SetDbValueDef($rsnew, $this->vl_media_name->CurrentValue, NULL, $this->vl_media_name->ReadOnly);

			// vl_media_file
			if ($this->vl_media_file->Visible && !$this->vl_media_file->ReadOnly && !$this->vl_media_file->Upload->KeepFile) {
				$this->vl_media_file->Upload->DbValue = $rsold['vl_media_file']; // Get original value
				if ($this->vl_media_file->Upload->FileName == "") {
					$rsnew['vl_media_file'] = NULL;
				} else {
					$rsnew['vl_media_file'] = $this->vl_media_file->Upload->FileName;
				}
			}

			// vl_media_file_custom
			if ($this->vl_media_file_custom->Visible && !$this->vl_media_file_custom->ReadOnly && !$this->vl_media_file_custom->Upload->KeepFile) {
				$this->vl_media_file_custom->Upload->DbValue = $rsold['vl_media_file_custom']; // Get original value
				if ($this->vl_media_file_custom->Upload->FileName == "") {
					$rsnew['vl_media_file_custom'] = NULL;
				} else {
					$rsnew['vl_media_file_custom'] = $this->vl_media_file_custom->Upload->FileName;
				}
			}
			if ($this->vl_media_file->Visible && !$this->vl_media_file->Upload->KeepFile) {
				if (!ew_Empty($this->vl_media_file->Upload->Value)) {
					$rsnew['vl_media_file'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->vl_media_file->UploadPath), $rsnew['vl_media_file']); // Get new file name
				}
			}
			if ($this->vl_media_file_custom->Visible && !$this->vl_media_file_custom->Upload->KeepFile) {
				$this->vl_media_file_custom->UploadPath = "uploads_custom/";
				if (!ew_Empty($this->vl_media_file_custom->Upload->Value)) {
					$rsnew['vl_media_file_custom'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->vl_media_file_custom->UploadPath), $rsnew['vl_media_file_custom']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->vl_media_file->Visible && !$this->vl_media_file->Upload->KeepFile) {
						if (!ew_Empty($this->vl_media_file->Upload->Value)) {
							$this->vl_media_file->Upload->SaveToFile($this->vl_media_file->UploadPath, $rsnew['vl_media_file'], TRUE);
						}
					}
					if ($this->vl_media_file_custom->Visible && !$this->vl_media_file_custom->Upload->KeepFile) {
						if (!ew_Empty($this->vl_media_file_custom->Upload->Value)) {
							$this->vl_media_file_custom->Upload->SaveToFile($this->vl_media_file_custom->UploadPath, $rsnew['vl_media_file_custom'], TRUE);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// vl_media_file
		ew_CleanUploadTempPath($this->vl_media_file, $this->vl_media_file->Upload->Index);

		// vl_media_file_custom
		ew_CleanUploadTempPath($this->vl_media_file_custom, $this->vl_media_file_custom->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("vl_media_listlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vl_media_list_edit)) $vl_media_list_edit = new cvl_media_list_edit();

// Page init
$vl_media_list_edit->Page_Init();

// Page main
$vl_media_list_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vl_media_list_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fvl_media_listedit = new ew_Form("fvl_media_listedit", "edit");

// Validate form
fvl_media_listedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_vl_media_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vl_media_list->vl_media_name->FldCaption(), $vl_media_list->vl_media_name->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_vl_media_file");
			elm = this.GetElements("fn_x" + infix + "_vl_media_file");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $vl_media_list->vl_media_file->FldCaption(), $vl_media_list->vl_media_file->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_vl_media_file_custom");
			elm = this.GetElements("fn_x" + infix + "_vl_media_file_custom");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $vl_media_list->vl_media_file_custom->FldCaption(), $vl_media_list->vl_media_file_custom->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fvl_media_listedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvl_media_listedit.ValidateRequired = true;
<?php } else { ?>
fvl_media_listedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvl_media_listedit.Lists["x_vl_media_type"] = {"LinkField":"x_vl_media_type_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vl_media_type_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $vl_media_list_edit->ShowPageHeader(); ?>
<?php
$vl_media_list_edit->ShowMessage();
?>
<div class="ewCustomPageCaptionArea"><div class='ewCustomBreadCrumbArea'><?php echo $vl_media_list->TableCaption() ?></div><div class='ewCustomExtrasArea'></div></div>
<form name="fvl_media_listedit" id="fvl_media_listedit" class="<?php echo $vl_media_list_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vl_media_list_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vl_media_list_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vl_media_list">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($vl_media_list->vl_media_id->Visible) { // vl_media_id ?>
	<div id="r_vl_media_id" class="form-group">
		<label id="elh_vl_media_list_vl_media_id" class="col-sm-2 control-label ewLabel"><?php echo $vl_media_list->vl_media_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vl_media_list->vl_media_id->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_id">
<span<?php echo $vl_media_list->vl_media_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vl_media_list->vl_media_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="vl_media_list" data-field="x_vl_media_id" name="x_vl_media_id" id="x_vl_media_id" value="<?php echo ew_HtmlEncode($vl_media_list->vl_media_id->CurrentValue) ?>">
<?php echo $vl_media_list->vl_media_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vl_media_list->vl_media_type->Visible) { // vl_media_type ?>
	<div id="r_vl_media_type" class="form-group">
		<label id="elh_vl_media_list_vl_media_type" for="x_vl_media_type" class="col-sm-2 control-label ewLabel"><?php echo $vl_media_list->vl_media_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vl_media_list->vl_media_type->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_type">
<select data-table="vl_media_list" data-field="x_vl_media_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($vl_media_list->vl_media_type->DisplayValueSeparator) ? json_encode($vl_media_list->vl_media_type->DisplayValueSeparator) : $vl_media_list->vl_media_type->DisplayValueSeparator) ?>" id="x_vl_media_type" name="x_vl_media_type"<?php echo $vl_media_list->vl_media_type->EditAttributes() ?>>
<?php
if (is_array($vl_media_list->vl_media_type->EditValue)) {
	$arwrk = $vl_media_list->vl_media_type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($vl_media_list->vl_media_type->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $vl_media_list->vl_media_type->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($vl_media_list->vl_media_type->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($vl_media_list->vl_media_type->CurrentValue) ?>" selected><?php echo $vl_media_list->vl_media_type->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vl_media_list->vl_media_type->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_vl_media_type',url:'vl_media_type_listaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_vl_media_type"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vl_media_list->vl_media_type->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `vl_media_type_id`, `vl_media_type_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vl_media_type_list`";
$sWhereWrk = "";
$vl_media_list->vl_media_type->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$vl_media_list->vl_media_type->LookupFilters += array("f0" => "`vl_media_type_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$vl_media_list->Lookup_Selecting($vl_media_list->vl_media_type, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `vl_media_type_name` ASC";
if ($sSqlWrk <> "") $vl_media_list->vl_media_type->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_vl_media_type" id="s_x_vl_media_type" value="<?php echo $vl_media_list->vl_media_type->LookupFilterQuery() ?>">
</span>
<?php echo $vl_media_list->vl_media_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vl_media_list->vl_media_name->Visible) { // vl_media_name ?>
	<div id="r_vl_media_name" class="form-group">
		<label id="elh_vl_media_list_vl_media_name" for="x_vl_media_name" class="col-sm-2 control-label ewLabel"><?php echo $vl_media_list->vl_media_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vl_media_list->vl_media_name->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_name">
<input type="text" data-table="vl_media_list" data-field="x_vl_media_name" name="x_vl_media_name" id="x_vl_media_name" size="30" maxlength="255" value="<?php echo $vl_media_list->vl_media_name->EditValue ?>"<?php echo $vl_media_list->vl_media_name->EditAttributes() ?>>
</span>
<?php echo $vl_media_list->vl_media_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vl_media_list->vl_media_file->Visible) { // vl_media_file ?>
	<div id="r_vl_media_file" class="form-group">
		<label id="elh_vl_media_list_vl_media_file" class="col-sm-2 control-label ewLabel"><?php echo $vl_media_list->vl_media_file->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vl_media_list->vl_media_file->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_file">
<div id="fd_x_vl_media_file">
<span title="<?php echo $vl_media_list->vl_media_file->FldTitle() ? $vl_media_list->vl_media_file->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($vl_media_list->vl_media_file->ReadOnly || $vl_media_list->vl_media_file->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="vl_media_list" data-field="x_vl_media_file" name="x_vl_media_file" id="x_vl_media_file"<?php echo $vl_media_list->vl_media_file->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_vl_media_file" id= "fn_x_vl_media_file" value="<?php echo $vl_media_list->vl_media_file->Upload->FileName ?>">
<?php if (@$_POST["fa_x_vl_media_file"] == "0") { ?>
<input type="hidden" name="fa_x_vl_media_file" id= "fa_x_vl_media_file" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_vl_media_file" id= "fa_x_vl_media_file" value="1">
<?php } ?>
<input type="hidden" name="fs_x_vl_media_file" id= "fs_x_vl_media_file" value="255">
<input type="hidden" name="fx_x_vl_media_file" id= "fx_x_vl_media_file" value="<?php echo $vl_media_list->vl_media_file->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_vl_media_file" id= "fm_x_vl_media_file" value="<?php echo $vl_media_list->vl_media_file->UploadMaxFileSize ?>">
</div>
<table id="ft_x_vl_media_file" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $vl_media_list->vl_media_file->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vl_media_list->vl_media_file_custom->Visible) { // vl_media_file_custom ?>
	<div id="r_vl_media_file_custom" class="form-group">
		<label id="elh_vl_media_list_vl_media_file_custom" class="col-sm-2 control-label ewLabel"><?php echo $vl_media_list->vl_media_file_custom->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vl_media_list->vl_media_file_custom->CellAttributes() ?>>
<span id="el_vl_media_list_vl_media_file_custom">
<div id="fd_x_vl_media_file_custom">
<span title="<?php echo $vl_media_list->vl_media_file_custom->FldTitle() ? $vl_media_list->vl_media_file_custom->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($vl_media_list->vl_media_file_custom->ReadOnly || $vl_media_list->vl_media_file_custom->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="vl_media_list" data-field="x_vl_media_file_custom" name="x_vl_media_file_custom" id="x_vl_media_file_custom"<?php echo $vl_media_list->vl_media_file_custom->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_vl_media_file_custom" id= "fn_x_vl_media_file_custom" value="<?php echo $vl_media_list->vl_media_file_custom->Upload->FileName ?>">
<?php if (@$_POST["fa_x_vl_media_file_custom"] == "0") { ?>
<input type="hidden" name="fa_x_vl_media_file_custom" id= "fa_x_vl_media_file_custom" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_vl_media_file_custom" id= "fa_x_vl_media_file_custom" value="1">
<?php } ?>
<input type="hidden" name="fs_x_vl_media_file_custom" id= "fs_x_vl_media_file_custom" value="255">
<input type="hidden" name="fx_x_vl_media_file_custom" id= "fx_x_vl_media_file_custom" value="<?php echo $vl_media_list->vl_media_file_custom->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_vl_media_file_custom" id= "fm_x_vl_media_file_custom" value="<?php echo $vl_media_list->vl_media_file_custom->UploadMaxFileSize ?>">
</div>
<table id="ft_x_vl_media_file_custom" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $vl_media_list->vl_media_file_custom->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $vl_media_list_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fvl_media_listedit.Init();
</script>
<?php
$vl_media_list_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vl_media_list_edit->Page_Terminate();
?>
