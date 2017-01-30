<?php
// Define Namespace
namespace App\Helpers;

//Class
class Paginate {

    protected $sizePerPage = 15;
    


    public function init($page) 
    {
    	$page = $page - 1;
    	$page = ($page < 0) ? 0 : $page;
    	return array(
    			'skip' => $page * $this->sizePerPage,
    			'limit' => $this->sizePerPage
		);

    }


}