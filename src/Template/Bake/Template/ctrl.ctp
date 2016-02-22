<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @author        Jean-Baptiste Guy
 * @date          2015-09-09
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field);
    })
    ->take(15);

$pluralHumanName = Inflector::camelize($pluralHumanName);
$singularHumanName = Inflector::camelize($singularHumanName);
%>
angular.module('<%= $singularHumanName %>Ctrl', [])
	.controller('<%= $singularHumanName %>Controller', 
		["$scope", "$http", "$modal", "<%= $singularHumanName %>",<%
				if (!empty($associations['BelongsTo'])) {
		            foreach ($associations['BelongsTo'] as $assocName => $assocData) {
						%>"<%= Inflector::singularize($assocData["controller"]) %>",<%
		            }
		        }
		%> "SweetAlert", "ExportExcel",
		function($scope, $http, $modal, <%= $singularHumanName %>, <%
				if (!empty($associations['BelongsTo'])) {
		            foreach ($associations['BelongsTo'] as $assocName => $assocData) {
						%><%= Inflector::singularize($assocData["controller"]) %>,<%
		            }
		        }
		%> SweetAlert, ExportExcel) {
		
		function load<%= $pluralHumanName %>(){	
			$scope.loading = true;
			<%= $singularHumanName %>.get()
				.success(function(data) {
					$scope.<%= $pluralVar %> = data.data;
					$scope.loading = false;
				});
		}	

		$scope.exportData = function () {

			if (typeof $scope.<%= $pluralVar %> != "undefined") {
		        ExportExcel.creerFichierExcel($scope.<%= $pluralVar %>, 
					[
						<% foreach ($fields as $field) {
							 if (isset($keyFields[$field])) {
				                continue;
				            }
        					if (!in_array($field, ['created', 'modified', 'updated', 'password'])) {
        				%>
							{ "header" : "<%= Inflector::humanize($field) %>", "column" : "<%= $field %>", "type": "<%= $schema->columnType($field)%>" },
        				<%
        					}
        				}
        				%>
        				<% if (!empty($associations['BelongsTo'])) {
        					foreach ($associations['BelongsTo'] as $assocName => $assocData) {
        				%>
							{ "header" : "<%= $assocName . " " .Inflector::humanize($assocData["displayField"]) %>", "column" : "<%= Inflector::singularize(Inflector::underscore($assocName)) . "." . $assocData["displayField"] %>" },
        				<%
        					}
        				}
        				%>
					],
					{"filename": "Export_<%= $singularHumanName%>"},
					null
				);
		    }
	    };

		// Suppression
		$scope.delete = function(id) { 
			SweetAlert.swal({
				title              : "Etes vous sur ?",
				text               : "Le <%= $singularVar %> sera définitevement supprimé !",
				type               : "warning",
				showCancelButton   : true,
				confirmButtonColor : "#DD6B55",confirmButtonText: "Oui !",
				cancelButtonText   : "Annuler",
				closeOnConfirm     : false,
				closeOnCancel      : true }, 
			function(isConfirm){ 
			   if (isConfirm) {
			   		<%= $singularHumanName %>.destroy(id)
						.success(function() {
			     			SweetAlert.swal("Deleted!", "Le <%= $singularVar %> à bien été supprimé.", "success");
							load<%= $pluralHumanName %>();
						});
			   } 
			}); 
	  	};

	  	// Modal
	    $scope.openModal = function(id) {
	        var modalInstance = $modal.open({
	            templateUrl: 'form_modal',
	            controller: $scope.model,
	            resolve: {
	                id: function() {
	                    return id;
	                }
	            }
	        });
	    };

	    $scope.model = function($scope, $modalInstance, id) {
	    	$scope.<%= $singularVar %> = {};
	    	$scope.isError = false;
	    	$scope.loadingModal = true;
			
			function loadModal(){	
<%
				if (!empty($associations['BelongsTo'])) {
		            foreach ($associations['BelongsTo'] as $assocName => $assocData) {
%>
		        <%= Inflector::singularize($assocData["controller"]) %>.get()
					.success(function(data) {
						$scope.<%= Inflector::underscore($assocData["controller"]) %> = data.data;
						$scope.loading = false;
					});
		<%
		            }
		        }
%>
		    	if (typeof id != "undefined") {
			        <%= $singularHumanName %>.show(id)
						.success(function(data) {
		    				console.log(data);
							$scope.<%= $singularVar %> = data.data;
	    					$scope.loadingModal = false;
						});
		    	} else {
	    			$scope.loadingModal = false;
		    	}


			}

	    	function afficherErreur(error){
	    		for (var champ in error) {
					for (var erreur in error[champ]) {
						$scope.errorText = "  " + champ + " : " + error[champ][erreur];
						$scope.isError = true;
						break;
					}
					break;
				}
	    	}

	        // close modal
	        $scope.cancel = function() {
	            $modalInstance.dismiss('cancel');
	        };

	        // Add
	        $scope.add = function() {
	        	<%= $singularHumanName %>.save($scope.<%= $singularVar %>)
					.success(function (data) {
						if (data.success == 1) {
			            	$modalInstance.dismiss('cancel');
			            	load<%= $pluralHumanName %>();
						} else {
							afficherErreur(data.error);
						}
				});
	        };

	        // Edit
	        $scope.edit = function() {
	        	<%= $singularHumanName %>.update($scope.<%= $singularVar %>)
	        		.success(function(data) {
		            	if (data.success == 1) {
			            	$modalInstance.dismiss('cancel');
			            	load<%= $pluralHumanName %>();
						} else {
							afficherErreur(data.error);
						}
				});
	        };

	        loadModal();
	    };

		// Chargement lors de la premiere ouverture	    
		load<%= $pluralHumanName %>();
	}]);