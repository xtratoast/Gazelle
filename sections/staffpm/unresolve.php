<?
if ($ID = (int)($_GET['id'])) {
	// Check if conversation belongs to user
	$DB->query("SELECT UserID, Level, AssignedToUser FROM staff_pm_conversations WHERE ID=$ID");
	list($UserID, $Level, $AssignedToUser) = $DB->next_record();
	
	if ($UserID == $LoggedUser['ID'] || $IsFLS || $AssignedToUser == $LoggedUser['ID']) {
		if($Level != 0 && $IsStaff == false)  {
			error(403);
		}

		// Conversation belongs to user or user is staff, unresolve it
		$DB->query("UPDATE staff_pm_conversations SET Status='Unanswered' WHERE ID=$ID");
		// Clear cache for user
		$Cache->delete_value('num_staff_pms_'.$LoggedUser['ID']);
		
		header('Location: staffpm.php');
	} else {
		// Conversation does not belong to user
		error(403);
	}
} else {
	// No id
	header('Location: staffpm.php');
}
?>
