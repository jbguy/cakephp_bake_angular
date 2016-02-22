<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field);
    })
    ->take(15);
$pluralVarUnderscore = Inflector::underscore($pluralVar);
%>
<div ng-include="'views/elements/alert.html'"></div>
<div class="row">
    <div class="col-md-5">
        <h4>Liste des <%= $pluralHumanName %></h4>  
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <input type="text" ng-model="search" class="form-control" placeholder="Recherche" aria-describedby="basic-addon">
            <span class="input-group-addon" id="basic-addon"><i class="fa fa-search"></i></span>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-success" ng-click="exportData()">
            <i class="fa fa-table"></i> Export
        </button>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-success pull-right" ng-click="openModal()">
            <i class="fa fa-plus"></i> Ajouter un <%= $singularHumanName %>
        </button>
    </div>
</div>

<p class="text-center" ng-show="loading"><span class="fa fa-refresh fa-spin"></span></p>

<table class="table table-borderer table-striped table-hover table-class col-md-12"  ng-hide="loading" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
<% foreach ($fields as $field): %>
<%
        $isKey = false;
        if (!empty($associations['BelongsTo'])) {
            foreach ($associations['BelongsTo'] as $alias => $details) {
                if ($field === $details['foreignKey']) {
                    $isKey = true;
%> 
            <th width="200"><%= Inflector::singularize($details["controller"]) %></th>
<%
                    break;
                }
            }
        }
        if ($isKey !== true) {
            if ($field != "created" && $field != "modified" && $field != "password") {
%>
            <th width="200"><%= Inflector::humanize($field) %></th>
<%
            }
        }
%>
    <% endforeach; %>
        <th width="75">Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="<%= $singularVar %> in <%= $pluralVar %> | filter:search" ng-animate="'animate'">
<%        foreach ($fields as $field) {
            $isKey = false;
            if (!empty($associations['BelongsTo'])) {
                foreach ($associations['BelongsTo'] as $alias => $details) {
                    if ($field === $details['foreignKey']) {
                        $isKey = true;
%>
            <td>{{<%= $singularVar %>.<%= $details['property'] %>.<%= $details['displayField'] %> }}</td> 
<%
                        break;
                    }
                }
            }
            if ($isKey !== true) {
                if ($field != "created" && $field != "modified" && $field != "password") {
                    if (in_array($schema->columnType($field), ['boolean'])) {
%>
            <td ng-show="<%= $singularVar %>.<%= $field %>=0">Non</td>
            <td ng-show="<%= $singularVar %>.<%= $field %>=1">Oui</td>
<%
                    } else {
%>
            <td>{{<%= $singularVar %>.<%= $field %> }}</td>
<%
                    }
                }
            }
        }
        $pk = $singularVar . '.' . $primaryKey[0];
%>
            <td class="actions">
                <button class="btn btn-danger btn-xs" ng-click="delete(<%= $pk %>)"><i class="fa fa-times"></i></button>
                <button type="button" class="btn btn-primary btn-xs" ng-click="openModal(<%= $pk %>)">
                    <i class="fa fa-pencil"></i>
                </button>
            </td>
        </tr>
    </tbody>
</table>
<!-- Modal -->
<script type="text/ng-template" id="form_modal">
    <p class="text-center" ng-show="loadingModal"><span class="fa fa-refresh fa-spin"></span></p>
    <form name='myForm' class="form-horizontal" ng-hide="loadingModal" novalidate>
        <div class="modal-header">
            <button type="button" class="close" ng-click="cancel()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Formulaire - <%= $singularHumanName %></h4>
        </div>
        <div class="modal-body">
            <div ng-include="'views/<%= $pluralVarUnderscore %>/form.html'"></div>
        </div>
        <div class="modal-footer">
            <button ng-click="edit()" ng-if="<%= $pk %>" ng-disabled="myForm.$invalid" class="btn btn-primary"><i class="fa fa-pencil"></i> Modifier</button>
            <button ng-click="add()" type="submit" ng-if="!<%= $pk %>" ng-disabled="myForm.$invalid" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter</button>
            <button ng-click="cancel()" class="btn">Annuler</button>
        </div>
    </form>
</script>
