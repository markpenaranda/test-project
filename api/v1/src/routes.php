<?php

// -----------------------------------------------------------------------------
// Routes
// -----------------------------------------------------------------------------


$app->group('/users', function () {
	$this->group('/{user_id}', function() {
		$this->map(['GET'], '', 'UserController:show');
		$this->map(['GET'], '/workmates', 'UserController:getUserBySamePage');
	});

});


$app->group('/openday', function(){
	$this->map(['GET'], '', 'OpenDayController:index');
	$this->map(['GET'], '/search', 'OpenDayController:search');
	$this->map(['GET'], '/live', 'OpenDayController:getLiveOpenday');
	$this->map(['GET'], '/my', 'OpenDayController:myOpenDay');
	$this->map(['GET'], '/created', 'OpenDayController:createdOpenday');
	$this->map(['GET'], '/current-rate', 'OpenDayController:currentRate');
	$this->map(['GET'], '/compute-total-hrs', 'OpenDayController:computeTotalHour');
	$this->map(['POST'], '', 'OpenDayController:store');
	$this->group('/{openday_id}', function() {
			$this->map(['GET'], '/candidates', 'OpenDayController:candidates');
			$this->map(['GET'], '/schedule', 'OpenDayController:schedule');
			$this->map(['GET'], '/candidates-id', 'OpenDayController:getListOfCandidateId');
			$this->map(['GET'], '/suggested', 'OpenDayController:suggestedUsers');
			$this->map(['GET'], '', 'OpenDayController:show');
			$this->map(['POST'], '/join', 'OpenDayController:join');
			$this->map(['POST'], '/set-interviewing', 'OpenDayController:setInterviewing');
			$this->map(['POST'], '/stop', 'OpenDayController:stopQueue');
			$this->map(['POST'], '/end', 'OpenDayController:endInterview');
			$this->map(['POST'], '/reject', 'OpenDayController:rejectCandidate');
			$this->map(['PUT'], '', 'OpenDayController:update');
		
	});
});

$app->group('/validate', function () {
	$this->post('/email', 'ExamController:checkIfEmailAvailableUser');
});

$app->group('/resources', function () {

	$this->group('/industry', function() {
		$this->map(['GET'] ,'', 'ResourcesController:getAllIndustry');
		$this->map(['GET'] ,'/{industry_id:[0-9]+}', 'ResourcesController:getAllIndustry');
	});

	$this->group('/country', function() {
		$this->map(['GET'] ,'', 'ResourcesController:getAllCountry');
		$this->group('/{country_id:[0-9]+}', function() {
			$this->map(['GET'] ,'', 'ResourcesController:getAllCountry');
			$this->group('/state', function(){
				$this->map(['GET'] ,'', 'ResourcesController:getStateByCountry');
				$this->group('/{code}', function() {
				$this->map(['GET'] ,'', 'ResourcesController:getSpecificStateByCountry');
					$this->group('/city', function(){
						$this->map(['GET'] ,'', 'ResourcesController:getCityByState');
						$this->map(['GET'] ,'/{city_id:[0-9]+}', 'ResourcesController:getCityByState');
					});
				});
			});
		});
	});

	$this->group('/filterjob', function() {
		$this->map(['GET'] ,'', 'ResourcesController:getFilterJob');
	});

	$this->group('/currency', function() {
		$this->map(['GET'] ,'', 'ResourcesController:getAllCurrency');
		$this->map(['GET'] ,'/{currency_id:[0-9]+}', 'ResourcesController:getAllCurrency');
	});

	$this->group('/poststatus', function() {
		$this->map(['GET'] ,'', 'ResourcesController:getPostStatus');
	});

	$this->group('/employer', function() {
		$this->get('/access-role', 'EmployerUserAccessRoleController:getEmployerUserAccessRole');
		$this->get('/size', 'ResourcesController:getEmployerSize');
		$this->get('/status', 'ResourcesController:getEmployerStatus');
		$this->get('/type', 'ResourcesController:getEmployerType');
	});

	$this->get('/salary-range', 'ResourcesController:getSalaryRange');
	$this->get('/educational-level', 'ResourcesController:getEducationalLevel');
	$this->get('/experience-year', 'ResourcesController:getExperienceYear');
	$this->get('/age-range', 'ResourcesController:getAgeRange');
});
