var app = angular.module('appLinuxourse',[]);

//DASHBOARD CONTROLLER
app.controller('ctrlDashboard', ['$scope','$http','$timeout','$location',
	function($scope,$http,$timeout,$location){
	$scope.listLoader = true;
	// $scope.new.testType = 'public';
	//GET JOINED COURSE/TEST LIST
	$scope.getList = function(type)
	{
		$scope.listLoaderText = 'Loading...';
		$scope.listLoader=false;
		$scope.alertUniqueBox=true;
		var url = rooturl+'CourseAPI/getCourse/'+type;
		var ajax = $http.post(url);
		ajax.success(function(response)
			{
				if(response.length < 1){$scope.listLoaderText = 'Data kosong';}
				else{$scope.listLoader = true;}
				$scope.courseList = response;
				$scope.$apply;
			});
		ajax.error(function()
			{
				alert('terjadi masalah');
				$scope.listLoader = true;
			});
	};
	//GET MY TEST
	$scope.getMyTest = function(status)
	{
		$scope.listLoader = false;
		$scope.listLoaderText = 'loading...';
		var url = rooturl+'CourseAPI/getMyTest';
		var ajax = $http.post(url,{status:status});
		ajax.success(function(response)
			{
				console.log(response);
				if(response.length < 1)
				{
					$scope.listLoaderText = 'Data kosong';
				}else
				{
					$scope.listLoader = true;
				}
				$scope.testList = response;
				$scope.$apply;
			});
		ajax.error(function(){alert('Something Wrong');});
	};
	//ADD NEW TEST
	$scope.newTest = function()
	{
		url = rooturl+'CourseAPI/newTest';
		var ajax = $http.put(url,$scope.new);
		ajax.success(function(response)
			{
				$scope.new = [];
				//redirect to my test
				var url = rooturl+'m/mytest';
				window.location = url;
			});
		ajax.error(function()
			{
				alert('something wrong');
			});
	};
	//IS UNIQUE LINK EXIST
	$scope.checkUniqueLink = function()
	{
		$scope.btnSubmitBox = true;//hidden submit button
        var unique = $scope.new.testUniqueLink;
        if(!unique){$scope.alertUniqueBox=true;$scope.btnSubmitBox = false;}
        else{
			$scope.alertUniqueBox=false;
	    	$scope.alertUniqueText='loading';
	        //cheking data
	        var url = rooturl+'CourseAPI/checkUniqueLink?q='+unique;
	        var ajax = $http.get(url);
	        ajax.success(function(response){//true or false
	            console.log(response);
	            if(response=='true')
	            {
	            	$scope.btnSubmitBox=true;
	            	$scope.alertUniqueBox=false;
	            	$scope.alertUniqueText='unique link is exist, change to other';
	            }//is exist
	        	else
	        	{
	        		$scope.alertUniqueBox=true;
	        		$scope.btnSubmitBox = false;
	        	}//ready to use
	            //results list
	            $scope.searchresults = response;
	        });
	        ajax.error(function(){$scope.searchloader=true;alert('terjadi masalah');});
        }
	};
	//AUTORUN
	$scope.getList('onprogress');
}]);

//MANAGE TEST CONTROLLER
app.controller('ctrlManageTest', ['$scope','$http','$timeout','$location',
	function($scope,$http,$timeout,$location){
		$scope.alertUniqueBox = $scope.alertBox = true;
		//UPDATE TEST DATA
		$scope.updateTest = function()
		{
			var url = rooturl+'CourseAPI/updateTest';
			var ajax = $http.patch(url,{test:$scope.test});
			ajax.success(function(){alert('Success updating data');});
			ajax.error(function(){alert('Error updating data')});
		};
		//NEW STEP MODAL
		$scope.newStepModal = function()
		{
			$('#newModal').foundation('reveal', 'open');
		};
		//UPDATE STEP MODAL
		$scope.updateStepModal = function(step)
		{
			$('#updateModal').foundation('reveal', 'open');
			//get data
			var url = rooturl+'CourseAPI/detailStep';
			var ajax = $http.post(url,{idtest:idtest,step:step});
			ajax.success(function(response){
				console.log(response);
				$scope.update = response;
				$scope.update.testCaseStep = parseInt(response.testCaseStep);
				$scope.update.estimate = parseInt(response.estimate);
				$scope.$apply;
			});
			ajax.error(function(){

			});
		};
		//CHECK STEP
		$scope.checkStep = function(step)
		{
			$scope.alertBox=false;
			$scope.alertText='chek avability...';
			if(step=='new'){var step = $scope.new.testCaseStep;}
			else{var step = $scope.update.testCaseStep}
			var url = rooturl+'CourseAPI/checkStep';
			var ajax = $http.post(url,{idtest:idtest,step:step});
			ajax.success(function(response)
				{
					if(response=='true'){//step is exist try another one
						$scope.alertText='Step is exist, try another one';
					}else{$scope.alertBox=true;}
				});
			ajax.error(function(){alert('something wrong');});
		}
		//NEW STEP ACTION
		$scope.newStepAction = function()
		{
			console.log('work');
			var laststep =  $('#caseList').children().last().attr('id');
			//insert to database
			var url = rooturl+'CourseAPI/newStepTest';
			var ajax = $http.put(url,{newstep:$scope.new,idtest:idtest});
			ajax.success(function(response){
				$scope.new = '';
				$scope.getCase();
				// $scope.getLatestCase(laststep);
				$('#newModal').foundation('reveal', 'close');
				// alert('Add Step Success');
			});
			ajax.error(function(){alert('Error adding step');});
		};
		//UPDATE STEP ACTION
		$scope.updateStepAction = function(idtestcase)
		{
			console.log(idtestcase);
			console.log($scope.update);
			//update database
			var url = rooturl+'CourseAPI/updateCase';
			var ajax = $http.patch(url,{case:$scope.update});
			ajax.success(function(){
				$('#updateModal').foundation('reveal', 'close');
				//refresh data
				$scope.getCase();
			});
			ajax.error(function(){alert('Error update data');});
		};
		//DELETE STEP ACTION
		$scope.deleteStepAction = function(idcase)
		{
			// alert(idcase);
			var agree = confirm('Are you sure !');
			if(agree==1){
				var url = rooturl+'CourseAPI/deleteCase';
				var ajax = $http.post(url,{idcase:idcase});
				ajax.success(function()
					{
						$('#updateModal').foundation('reveal', 'close');
						//refresh data
						$scope.getCase();
					});
				ajax.error(function(){alert('error deleting data');});
			}
		};
		//GET DETAIL TEST
		$scope.detailTest = function()
		{
			var url = rooturl+'CourseAPI/detailTest';
			var ajax = $http.post(url,{idtest:idtest});
			ajax.success(function(response){$scope.test=response});
			ajax.error(function(){alert('something wrong');});
		}
		//GET TEST CASE
		$scope.getCase = function()
		{
			var url = rooturl+'CourseAPI/getCase';
			var ajax = $http.post(url,{idtest:idtest});
			ajax.success(function(response){console.log(response);$scope.cases=response;$scope.$apply;});//get all case list to be model on ng-repeat
			ajax.error(function(){alert('something wrong');});
		}
		//GET LATEST TEST CASE
		$scope.getLatestCase = function(laststep)
		{
			var url = rooturl+'CourseAPI/getCase?act=latest';
			var ajax = $http.post(url,{idtest:idtest,laststep:laststep});
			ajax.success(function(response){
				console.log(response);
				//push data to recent ng-repeat
				$scope.cases.push(response);
				$scope.$apply;
				});//get all case list to be model on ng-repeat
			ajax.error(function(){alert('something wrong');});
		}

		//AUTOSTART
		//DETAIL TEST
		$scope.detailTest();
		$scope.getCase();
	}]);