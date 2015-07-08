<span ng-controller="ctrlManageTest">
	<section class="row" style="background-color:#fff">
		<br/>
		<br/>
		<ul class="breadcrumbs">
			<li><a href="#">Dashboard</a></li>
			<li class="unavailable"><a href="#">Test</a></li>
			<li class="current"><a href="#">Manage Test : {{test.testName}}</a></li>
		</ul>
		<span style="text-align:center">
			<h1>Manage Test</h1>
			<p>{{test.testName}}<br/>
			<small>Created : {{test.testCreated}} , Updated : {{test.testUpdated}}</small>
			</p>
			<br/>
		</span>
		<center>
			<div class="large-12 columns">
				<dl class="tabs" data-tab>
					<dd style="width:33.33%" class="active"><a ng-click="" href="#case">Test Step ({{cases.length}})</a></dd>
					<dd style="width:33.33%"><a ng-click="" href="#participant">Participant (25)</a></dd>
					<dd style="width:33.33%"><a ng-click="" href="#updatedata">Update Data</a></dd>
				</dl>
			</div>
		</center>
	</section>
	<section id="completion">
		<center>		
			<div class="row">			
				<div class="large-12 collapse" columns>
					<!-- skill completion -->
					<div class="row">						
						<div class="tabs-content">
							<!-- Manage Case and Question Here -->
							<div class="content active" id="case">	
								<p>
									<a style="margin-right:15px" href="#newstep" ng-click="newStepModal()">+ New Step</a> 
									<a style="margin-right:15px" href="#refresh" ng-click="getCase()"><span class="fi-refresh"></span> Refresh List</a> 
									<a style="margin-right:15px" href="#">Test Preview</a>
									<a onclick="return prompt('Copy this link and share to participant','http://linuxourse.me/test/join/')" style="margin-right:15px" href="#getlink">Get Link</a>
								</p>
								<small>click to update/delete step</small>		
								<!-- list -->
								<div id="caseList">
								<a id="{{case.testCaseStep}}" ng-repeat="case in cases" href="#update" ng-click="updateStepModal(case.testCaseStep)">
									<div style="vertical-align:middle" class="joinmateri row">									
										<div class="small-1 columns"><p>{{case.testCaseStep}}</p></div>
										<div class="small-7 columns"><p><strong>Case</strong><br/>{{case.testCaseQuestion  | limitTo:500}}...</p></div>
										<div class="small-3 columns">
											<p>
												<strong>Command</strong><br/>
												{{case.command | limitTo:500 }}...
											</p>										
										</div>															
										<div class="small-1 columns"><p><strong>Estimate</strong><br/>{{case.estimate}} min</p></div>
									</div>
								</a>
								</div>
								<!-- end of list -->
							</div>	
							<!-- Manage Case and Question Here -->
							<div class="content" id="participant">						
							</div>	
							<!-- Manage Case and Question Here -->
							<div class="content" id="updatedata">
								<p>Update Test</p>
								<form name="UpdateTest" ng-submit="updateTest()">
									<div class="row">
										<div class="small-12 columns">
											<div class="row">
												<div class="small-2 columns">
													<label for="testname" class="right inline">Test Name</label>
												</div>
												<div class="small-9 columns">
													<input type="text" id="testname" ng-model="test.testName" required>
												</div>
											</div>
											<div class="row">
												<div class="small-2 columns">
													<label for="notes" class="right inline">Notes To Participant</label>
												</div>
												<div class="small-9 columns">
													<textarea type="text" id="notes" row="4" ng-model="test.testNotes" required></textarea>
												</div>
											</div>
											<br/>
											<div class="row">
												<div class="small-2 columns">
													<label for="organization" class="right inline">Organization</label>
												</div>
												<div class="small-9 columns">
													<input type="text" id="organization"  ng-model="test.organization">
												</div>
											</div>
											<div class="row">
												<div class="small-2 columns">
													<label for="unique" class="right inline">Unique Name</label>
												</div>
												<div class="small-9 columns">
													<input ng-keyup="checkUniqueLink()" type="text" id="unique" ng-model="test.testUniqueLink" placeholder="input custom unique name without space" readonly>
													<small ng-hide="alertUniqueBox" class="error">{{alertUniqueText}}</small>
												</div>
											</div>
											<div class="row">
												<div class="small-2 columns">
													<label for="open" class="right inline">Open Test (Y:m:d H:i:s)</label>
												</div>
												<div class="small-9 columns">
													<input type="text" id="open" ng-model="test.testOpen">
												</div>
											</div>
											<div class="row">
												<div class="small-2 columns">
													<label for="close" class="right inline">Close Test (Y:m:d H:i:s)</label>
												</div>
												<div class="small-9 columns">
													<input type="text" id="close" ng-model="test.testClose">
												</div>
											</div>
											<div class="row">
												<div class="small-2 columns">
													<label for="type" class="right inline">Test Type</label>
												</div>
												<div class="small-9 columns">
													<select id="type" ng-model="test.testType" required>
														<option value="public">Public</option>
														<option value="private">Private</option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="small-2 columns">
													<p></p>
												</div>
												<div class="small-9 columns">
													<br/>
													<button style="float:left" class="button" type="submit">Update Data</button>
												</div>
											</div>
										</div>
									</div>
								</form>						
							</div>						
						</div>
					</div>			
				</div>
			</div>
		</center>
	</section>
<!-- new step modal -->
<div id="newModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h4 style="text-align:center;color:#000" id="modalTitle">New Step</h4>
	<br/>
	<!-- form add case -->
	<form name="NewStep" ng-submit="newStepAction()">
		<div class="row">
			<div class="small-12 columns">
				<div class="row">
					<div class="small-2 columns">
						<label for="newstep" class="right inline">Step</label>
					</div>
					<div class="small-9 columns">
						<input ng-keyup="checkStep('new')" type="number" min="1" max="1000" id="newstep" ng-model="new.testCaseStep" required>
						<div ng-hide="alertBox" data-alert class="alert-box alert">
						  {{alertText}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="small-2 columns">
						<label for="newcase" class="right inline">Cases / Question</label>
					</div>
					<div class="small-9 columns">
						<textarea type="case" id="newcase" row="4" ng-model="new.testCaseQuestion" required></textarea>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="small-2 columns">
						<label for="newcommand" class="right inline">Command</label>
					</div>
					<div class="small-9 columns">
						<textarea type="case" id="newcommand" row="4" ng-model="new.command" required></textarea>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="small-2 columns">
						<label for="newestimate" class="right inline">estimate (in minutes)</label>
					</div>
					<div class="small-9 columns">
						<input type="number" min="1" id="newestimate" ng-model="new.estimate" required>
					</div>
				</div>
				<div class="row">
					<div class="small-2 columns">
						<p></p>
					</div>
					<div class="small-9 columns">
						<button style="float:left" class="button" type="submit">+ Add Step</button>
					</div>
				</div>
			</div>
		</div>
	</form>	
	<!-- end form add case -->
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
<!-- update step modal -->
<div id="updateModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h4 style="color:#000" id="modalTitle">Update Step</h4>
	<p><small>created:{{update.addTestCase}} , last updated:{{update.updatedTestCase}}</small></p>
	<br/>
	<!-- form add case -->
	<form name="UpdateStep" ng-submit="updateStepAction(update.idTestCase)">
		<div class="row">
			<div class="small-12 columns">
				<div class="row">
					<div class="small-2 columns">
						<label for="step" class="right inline">Step</label>
					</div>
					<div class="small-9 columns">
						<input ng-keyup="checkStep('new')" type="number" min="1" max="1000" id="step" ng-model="update.testCaseStep" required>
						<div ng-hide="alertBox" data-alert class="alert-box alert">
						  {{alertText}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="small-2 columns">
						<label for="case" class="right inline">Cases / Question</label>
					</div>
					<div class="small-9 columns">
						<textarea type="case" id="notes" row="4" ng-model="update.testCaseQuestion" required></textarea>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="small-2 columns">
						<label for="command" class="right inline">Command</label>
					</div>
					<div class="small-9 columns">
						<textarea type="case" id="command" row="4" ng-model="update.command" required></textarea>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="small-2 columns">
						<label for="estimate" class="right inline">estimate (in minutes)</label>
					</div>
					<div class="small-9 columns">
						<input type="number" min="1" id="estimate" ng-model="update.estimate" required>
					</div>
				</div>
				<div class="row">
					<div class="small-2 columns">
						<p></p>
					</div>
					<div class="small-9 columns">
						<button style="float:left" class="button" type="submit">Update</button>
						<button ng-click="deleteStepAction(update.idTestCase)" style="float:left" class="button alert" type="button">Delete</button>
					</div>
				</div>
			</div>
		</div>
	</form>	
	<!-- end form add case -->
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
</span>
<script type="text/javascript">
	var idtest = <?php echo $this->uri->segment(3);?>
</script>