<?php 

// TODO : move to root folder and refactor everything

class ACL
{
	

	function hasAccess($controller=null,$action=null) {
		global $db;
		$username=getMyUsername();

		
		if(!hasLoggedIn())
			return false;
		

	    if($action=="index")
	        return true;
	
			
	    if($db->get_var("SELECT COUNT(*) FROM acl WHERE username='".$username."' AND controller='$controller' AND action='$action'"))
		    return true;
		
		return false;
			
	}
	
}