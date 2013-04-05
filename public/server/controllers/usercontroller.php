<?php

class UserController {

	public function getUsers(){

		$users = Model::factory('User')->find_many();
	    
	    $data=array();

	    foreach ($users as $user) 
	    {    

	      $users_array = $user->as_array();

	      array_push( $data,$users_array);
	    }
	    
	    return $data;
	}

	function getUser($id){

    	$user = Model::factory('User')->find_one($id);
    
    	if(!empty($user)) return $user->as_array();

    	//return null;
	}

	function addUser($user){

    	$id = 0;
    	return $this->saveOrUpdateUser($id, $user);
   
	}

	function updateUser($id,$user)
	{
	    return $this->saveOrUpdateUser($id, $user);
	}

	function saveOrUpdateUser($id,$user)
	{
	     
	        if(empty($id)){
	        
	            $user_model = Model::factory('User')->create();            
	            
	        }
	        else{
	        
	            $user_model = Model::factory('User')->find_one($id);
	        }
	                
	         
	        if(isset($user->firstname)) $user_model->firstname=$user->firstname;
	        if(isset($user->lastname)) $user_model->lastname=$user->lastname;
	        if(isset($user->email)) $user_model->email=$user->email;
	        if(isset($user->phone)) $user_model->phone=$user->phone;
	        

	        $user_model->save();
	      

	        return $user_model->as_array();
	    
	}

	function deleteUser($id)
	{

	    $user_model = Model::factory('User')->find_one($id);
	    $user_model->delete();


	    return $user_model->as_array;
	}

}
?>