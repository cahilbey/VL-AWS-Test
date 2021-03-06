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

$vl_media_list_list = NULL; // Initialize page object first

class cvl_media_list_list extends cvl_media_list {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{64847E68-7377-4FEE-94E6-69A0E84D0306}";

	// Table name
	var $TableName = 'vl_media_list';

	// Page object name
	var $PageObjName = 'vl_media_list_list';

	// Grid form hidden field names
	var $FormName = 'fvl_media_listlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vl_media_listadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vl_media_listdelete.php";
		$this->MultiUpdateUrl = "vl_media_listupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vl_media_list', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fvl_media_listlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->vl_media_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->vl_media_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->vl_media_id); // vl_media_id
			$this->UpdateSort($this->vl_media_type); // vl_media_type
			$this->UpdateSort($this->vl_media_name); // vl_media_name
			$this->UpdateSort($this->vl_media_file); // vl_media_file
			$this->UpdateSort($this->vl_media_file_custom); // vl_media_file_custom
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->vl_media_id->setSort("");
				$this->vl_media_type->setSort("");
				$this->vl_media_name->setSort("");
				$this->vl_media_file->setSort("");
				$this->vl_media_file_custom->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->vl_media_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fvl_media_listlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fvl_media_listlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fvl_media_listlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("vl_media_id")) <> "")
			$this->vl_media_id->CurrentValue = $this->getKey("vl_media_id"); // vl_media_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->vl_media_file->LinkAttrs["data-rel"] = "vl_media_list_x" . $this->RowCnt . "_vl_media_file";
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
				$this->vl_media_file_custom->LinkAttrs["data-rel"] = "vl_media_list_x" . $this->RowCnt . "_vl_media_file_custom";
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($vl_media_list_list)) $vl_media_list_list = new cvl_media_list_list();

// Page init
$vl_media_list_list->Page_Init();

// Page main
$vl_media_list_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vl_media_list_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fvl_media_listlist = new ew_Form("fvl_media_listlist", "list");
fvl_media_listlist.FormKeyCountName = '<?php echo $vl_media_list_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvl_media_listlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvl_media_listlist.ValidateRequired = true;
<?php } else { ?>
fvl_media_listlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvl_media_listlist.Lists["x_vl_media_type"] = {"LinkField":"x_vl_media_type_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vl_media_type_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($vl_media_list_list->TotalRecs > 0 && $vl_media_list_list->ExportOptions->Visible()) { ?>
<?php $vl_media_list_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $vl_media_list_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vl_media_list_list->TotalRecs <= 0)
			$vl_media_list_list->TotalRecs = $vl_media_list->SelectRecordCount();
	} else {
		if (!$vl_media_list_list->Recordset && ($vl_media_list_list->Recordset = $vl_media_list_list->LoadRecordset()))
			$vl_media_list_list->TotalRecs = $vl_media_list_list->Recordset->RecordCount();
	}
	$vl_media_list_list->StartRec = 1;
	if ($vl_media_list_list->DisplayRecs <= 0 || ($vl_media_list->Export <> "" && $vl_media_list->ExportAll)) // Display all records
		$vl_media_list_list->DisplayRecs = $vl_media_list_list->TotalRecs;
	if (!($vl_media_list->Export <> "" && $vl_media_list->ExportAll))
		$vl_media_list_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vl_media_list_list->Recordset = $vl_media_list_list->LoadRecordset($vl_media_list_list->StartRec-1, $vl_media_list_list->DisplayRecs);

	// Set no record found message
	if ($vl_media_list->CurrentAction == "" && $vl_media_list_list->TotalRecs == 0) {
		if ($vl_media_list_list->SearchWhere == "0=101")
			$vl_media_list_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vl_media_list_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$vl_media_list_list->RenderOtherOptions();
?>
<?php $vl_media_list_list->ShowPageHeader(); ?>
<?php
$vl_media_list_list->ShowMessage();
?>
<div class="ewCustomPageCaptionArea"><div class='ewCustomBreadCrumbArea'><?php echo $vl_media_list->TableCaption() ?></div><div class='ewCustomExtrasArea'></div></div>
<?php include 'custom-files/php/app/custom-list-page-info-area.php'; ?>
<?php if ($vl_media_list_list->TotalRecs > 0 || $vl_media_list->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div class="panel-heading ewGridUpperPanel">
<?php if ($vl_media_list->CurrentAction <> "gridadd" && $vl_media_list->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vl_media_list_list->Pager)) $vl_media_list_list->Pager = new cPrevNextPager($vl_media_list_list->StartRec, $vl_media_list_list->DisplayRecs, $vl_media_list_list->TotalRecs) ?>
<?php if ($vl_media_list_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($vl_media_list_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($vl_media_list_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vl_media_list_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($vl_media_list_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($vl_media_list_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vl_media_list_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vl_media_list_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vl_media_list_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vl_media_list_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vl_media_list_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fvl_media_listlist" id="fvl_media_listlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vl_media_list_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vl_media_list_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vl_media_list">
<div id="gmp_vl_media_list" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($vl_media_list_list->TotalRecs > 0) { ?>
<table id="tbl_vl_media_listlist" class="table ewTable">
<?php echo $vl_media_list->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$vl_media_list_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vl_media_list_list->RenderListOptions();

// Render list options (header, left)
$vl_media_list_list->ListOptions->Render("header", "left");
?>
<?php if ($vl_media_list->vl_media_id->Visible) { // vl_media_id ?>
	<?php if ($vl_media_list->SortUrl($vl_media_list->vl_media_id) == "") { ?>
		<th data-name="vl_media_id"><div id="elh_vl_media_list_vl_media_id" class="vl_media_list_vl_media_id"><div class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vl_media_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vl_media_list->SortUrl($vl_media_list->vl_media_id) ?>',1);"><div id="elh_vl_media_list_vl_media_id" class="vl_media_list_vl_media_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vl_media_list->vl_media_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vl_media_list->vl_media_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vl_media_list->vl_media_type->Visible) { // vl_media_type ?>
	<?php if ($vl_media_list->SortUrl($vl_media_list->vl_media_type) == "") { ?>
		<th data-name="vl_media_type"><div id="elh_vl_media_list_vl_media_type" class="vl_media_list_vl_media_type"><div class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vl_media_type"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vl_media_list->SortUrl($vl_media_list->vl_media_type) ?>',1);"><div id="elh_vl_media_list_vl_media_type" class="vl_media_list_vl_media_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vl_media_list->vl_media_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vl_media_list->vl_media_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vl_media_list->vl_media_name->Visible) { // vl_media_name ?>
	<?php if ($vl_media_list->SortUrl($vl_media_list->vl_media_name) == "") { ?>
		<th data-name="vl_media_name"><div id="elh_vl_media_list_vl_media_name" class="vl_media_list_vl_media_name"><div class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vl_media_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vl_media_list->SortUrl($vl_media_list->vl_media_name) ?>',1);"><div id="elh_vl_media_list_vl_media_name" class="vl_media_list_vl_media_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vl_media_list->vl_media_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vl_media_list->vl_media_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vl_media_list->vl_media_file->Visible) { // vl_media_file ?>
	<?php if ($vl_media_list->SortUrl($vl_media_list->vl_media_file) == "") { ?>
		<th data-name="vl_media_file"><div id="elh_vl_media_list_vl_media_file" class="vl_media_list_vl_media_file"><div class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_file->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vl_media_file"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vl_media_list->SortUrl($vl_media_list->vl_media_file) ?>',1);"><div id="elh_vl_media_list_vl_media_file" class="vl_media_list_vl_media_file">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_file->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vl_media_list->vl_media_file->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vl_media_list->vl_media_file->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vl_media_list->vl_media_file_custom->Visible) { // vl_media_file_custom ?>
	<?php if ($vl_media_list->SortUrl($vl_media_list->vl_media_file_custom) == "") { ?>
		<th data-name="vl_media_file_custom"><div id="elh_vl_media_list_vl_media_file_custom" class="vl_media_list_vl_media_file_custom"><div class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_file_custom->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vl_media_file_custom"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vl_media_list->SortUrl($vl_media_list->vl_media_file_custom) ?>',1);"><div id="elh_vl_media_list_vl_media_file_custom" class="vl_media_list_vl_media_file_custom">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vl_media_list->vl_media_file_custom->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vl_media_list->vl_media_file_custom->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vl_media_list->vl_media_file_custom->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vl_media_list_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vl_media_list->ExportAll && $vl_media_list->Export <> "") {
	$vl_media_list_list->StopRec = $vl_media_list_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vl_media_list_list->TotalRecs > $vl_media_list_list->StartRec + $vl_media_list_list->DisplayRecs - 1)
		$vl_media_list_list->StopRec = $vl_media_list_list->StartRec + $vl_media_list_list->DisplayRecs - 1;
	else
		$vl_media_list_list->StopRec = $vl_media_list_list->TotalRecs;
}
$vl_media_list_list->RecCnt = $vl_media_list_list->StartRec - 1;
if ($vl_media_list_list->Recordset && !$vl_media_list_list->Recordset->EOF) {
	$vl_media_list_list->Recordset->MoveFirst();
	$bSelectLimit = $vl_media_list_list->UseSelectLimit;
	if (!$bSelectLimit && $vl_media_list_list->StartRec > 1)
		$vl_media_list_list->Recordset->Move($vl_media_list_list->StartRec - 1);
} elseif (!$vl_media_list->AllowAddDeleteRow && $vl_media_list_list->StopRec == 0) {
	$vl_media_list_list->StopRec = $vl_media_list->GridAddRowCount;
}

// Initialize aggregate
$vl_media_list->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vl_media_list->ResetAttrs();
$vl_media_list_list->RenderRow();
while ($vl_media_list_list->RecCnt < $vl_media_list_list->StopRec) {
	$vl_media_list_list->RecCnt++;
	if (intval($vl_media_list_list->RecCnt) >= intval($vl_media_list_list->StartRec)) {
		$vl_media_list_list->RowCnt++;

		// Set up key count
		$vl_media_list_list->KeyCount = $vl_media_list_list->RowIndex;

		// Init row class and style
		$vl_media_list->ResetAttrs();
		$vl_media_list->CssClass = "";
		if ($vl_media_list->CurrentAction == "gridadd") {
		} else {
			$vl_media_list_list->LoadRowValues($vl_media_list_list->Recordset); // Load row values
		}
		$vl_media_list->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vl_media_list->RowAttrs = array_merge($vl_media_list->RowAttrs, array('data-rowindex'=>$vl_media_list_list->RowCnt, 'id'=>'r' . $vl_media_list_list->RowCnt . '_vl_media_list', 'data-rowtype'=>$vl_media_list->RowType));

		// Render row
		$vl_media_list_list->RenderRow();

		// Render list options
		$vl_media_list_list->RenderListOptions();
?>
	<tr<?php echo $vl_media_list->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vl_media_list_list->ListOptions->Render("body", "left", $vl_media_list_list->RowCnt);
?>
	<?php if ($vl_media_list->vl_media_id->Visible) { // vl_media_id ?>
		<td data-name="vl_media_id"<?php echo $vl_media_list->vl_media_id->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_list->RowCnt ?>_vl_media_list_vl_media_id" class="vl_media_list_vl_media_id">
<span<?php echo $vl_media_list->vl_media_id->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_id->ListViewValue() ?></span>
</span>
<a id="<?php echo $vl_media_list_list->PageObjName . "_row_" . $vl_media_list_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vl_media_list->vl_media_type->Visible) { // vl_media_type ?>
		<td data-name="vl_media_type"<?php echo $vl_media_list->vl_media_type->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_list->RowCnt ?>_vl_media_list_vl_media_type" class="vl_media_list_vl_media_type">
<span<?php echo $vl_media_list->vl_media_type->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vl_media_list->vl_media_name->Visible) { // vl_media_name ?>
		<td data-name="vl_media_name"<?php echo $vl_media_list->vl_media_name->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_list->RowCnt ?>_vl_media_list_vl_media_name" class="vl_media_list_vl_media_name">
<span<?php echo $vl_media_list->vl_media_name->ViewAttributes() ?>>
<?php echo $vl_media_list->vl_media_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vl_media_list->vl_media_file->Visible) { // vl_media_file ?>
		<td data-name="vl_media_file"<?php echo $vl_media_list->vl_media_file->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_list->RowCnt ?>_vl_media_list_vl_media_file" class="vl_media_list_vl_media_file">
<span>
<?php echo ew_GetFileViewTag($vl_media_list->vl_media_file, $vl_media_list->vl_media_file->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($vl_media_list->vl_media_file_custom->Visible) { // vl_media_file_custom ?>
		<td data-name="vl_media_file_custom"<?php echo $vl_media_list->vl_media_file_custom->CellAttributes() ?>>
<span id="el<?php echo $vl_media_list_list->RowCnt ?>_vl_media_list_vl_media_file_custom" class="vl_media_list_vl_media_file_custom">
<span>
<?php echo ew_GetFileViewTag($vl_media_list->vl_media_file_custom, $vl_media_list->vl_media_file_custom->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vl_media_list_list->ListOptions->Render("body", "right", $vl_media_list_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vl_media_list->CurrentAction <> "gridadd")
		$vl_media_list_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vl_media_list->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vl_media_list_list->Recordset)
	$vl_media_list_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($vl_media_list->CurrentAction <> "gridadd" && $vl_media_list->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vl_media_list_list->Pager)) $vl_media_list_list->Pager = new cPrevNextPager($vl_media_list_list->StartRec, $vl_media_list_list->DisplayRecs, $vl_media_list_list->TotalRecs) ?>
<?php if ($vl_media_list_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($vl_media_list_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($vl_media_list_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vl_media_list_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($vl_media_list_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($vl_media_list_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $vl_media_list_list->PageUrl() ?>start=<?php echo $vl_media_list_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vl_media_list_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vl_media_list_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vl_media_list_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vl_media_list_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vl_media_list_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($vl_media_list_list->TotalRecs == 0 && $vl_media_list->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vl_media_list_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fvl_media_listlist.Init();
</script>
<?php
$vl_media_list_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vl_media_list_list->Page_Terminate();
?>
