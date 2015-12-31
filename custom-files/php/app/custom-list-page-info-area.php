<?php



// $TableName = CurrentPage()->TableName; ## Ex. vl_content_list
// $PageID = CurrentPage()->PageID; ## Ex. list, add, edit
// $PageURL = CurrentPage()->PageUrl();
// $AdvancedSearchWhere = CurrentPage()->AdvancedSearchWhere(); ## vl_content_parent = 85
// $AdvancedSearchValue = $vl_content_list->vl_content_parent->AdvancedSearch->SearchValue; ## 85


// if($TableName == 'vl_content_list' && $PageID == 'list'){
	
// 	$AdvancedSearchWhere = CurrentPage()->AdvancedSearchWhere(); ## vl_content_parent = 85
// 	$AdvancedSearchValue = $vl_content_list->vl_content_parent->AdvancedSearch->SearchValue; ## 85
// 	$ParentID = $AdvancedSearchValue;

// 	if($ParentID != ''){
// 		$PageDesc = ew_ExecuteScalar("SELECT vl_content_description FROM vl_content_list WHERE vl_content_id='$ParentID' ");
// 		if($PageDesc != NULL){
// 			fnCreateDescArea($PageDesc);
// 		}
// 	}

// }


