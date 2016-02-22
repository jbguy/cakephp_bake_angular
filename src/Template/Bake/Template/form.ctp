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
    
        foreach ($fields as $field) {
            if (in_array($field, $primaryKey)) {
                continue;
            }
            if (isset($keyFields[$field])) {
                continue;
            }
            if (!in_array($field, ['created', 'modified', 'updated'])) {
                $fieldData = $schema->column($field);
                if (($fieldData['type'] === 'date') && (!empty($fieldData['null']))) {
%>
            echo $this->Form->input('<%= $field %>', array('empty' => true, 'default' => ''));
<%
                } else {
                    if (in_array($schema->columnType($field), ['boolean'])) {
%>
<div class="form-group" ng-class="{ 'has-error' : myForm.<%= $field %>.$invalid && !myForm.<%= $field %>.$pristine }" >
    <label for="<%= $field %>" class="col-sm-2 control-label"><%= Inflector::humanize($field) %></label>
    <div class="col-sm-10">
        <label class="radio-inline">
            <input type="radio" ng-model="<%= $singularVar %>.<%= $field %>" name="<%= $field %>RadioOptions" id="<%= $field %>RadioOui" value="1"> Oui
        </label>
        <label class="radio-inline">
            <input type="radio" ng-model="<%= $singularVar %>.<%= $field %>" name="<%= $field %>RadioOptions" id="<%= $field %>RadioNon" value="0"> Non
        </label>
    </div>
</div>
<%


                    } else {
%>
<div class="form-group" ng-class="{ 'has-error' : myForm.<%= $field %>.$invalid && !myForm.<%= $field %>.$pristine }" >
    <label for="<%= $field %>" class="col-sm-2 control-label"><%= Inflector::humanize($field) %></label>
    <div class="col-sm-10">
        <input type="text" name="<%= $field %>" ng-model="<%= $singularVar %>.<%= $field %>" class="form-control" id="<%= $field %>" placeholder="<%= Inflector::humanize($field) %>" required/>
        <p ng-show="myForm.<%= $field %>.$invalid && !myForm.<%= $field %>.$pristine" class="help-block">The field is required.</p>
    </div>
</div>
<%
                    }
                }
            }
        }
         if (!empty($associations['BelongsTo'])) {
            foreach ($associations['BelongsTo'] as $assocName => $assocData) {
%>
<div class="form-group" ng-class="{ 'has-error' : myForm.<%= $assocData["foreignKey"] %>.$invalid && !myForm.<%= $assocData["foreignKey"] %>.$pristine }" >
    <label for="<%= $assocData["foreignKey"] %>" class="col-sm-2 control-label"><%= Inflector::singularize($assocData["controller"]) %></label>
    <div class="col-sm-10">
        <select class="form-control" ng-model="<%= $singularVar %>.<%= $assocData["foreignKey"] %>">
            <option ng-repeat="obj in <%= Inflector::underscore($assocData["controller"]) %>" value="{{obj.<%= $assocData["primaryKey"][0] %>}}" ng-selected="obj.<%= $assocData["primaryKey"][0] %> == <%= $singularVar %>.<%= $assocData["foreignKey"] %>">{{obj.<%= $assocData["displayField"] %>}}</option>
        </select>
        <p ng-show="myForm.<%= $assocData["foreignKey"] %>.$invalid && !myForm.<%= $assocData["foreignKey"] %>.$pristine" class="help-block">The field is required.</p>
    </div>
</div>
            
<%
            }
        }
        if (!empty($associations['BelongsToMany'])) {
            foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
%>
            echo $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]);
            
<%
            }
        }
%>





<div ng-show="isError" class="alert alert-danger" role="alert"><i class="fa fa-warning"></i>{{errorText}}</div>
