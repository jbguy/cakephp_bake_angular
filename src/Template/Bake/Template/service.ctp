<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @author        Jean-Baptiste Guy
 * @date          2015-05-02
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$singularHumanName = Inflector::camelize($singularHumanName);
%>
angular.module('<%= $singularHumanName %>Service', [])

    .factory('<%= $singularHumanName %>', 
        ["$http",
        function($http) {

        var modelRoute = "<%= strtolower($pluralVar) %>";

        return {
            get : function() {
                return $http.get('api/' + modelRoute + '.json');
            },
            show : function(id) {
                return $http.get('api/' + modelRoute + '/' + id  + '.json');
            },
            save : function(data) {
                return $http({
                    method: 'POST',
                    url: 'api/' + modelRoute  + '.json',
                    headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                    data: $.param(data)
                });
            },
            update : function(data) {
                return $http({
                    method: 'PUT',
                    url: 'api/' + modelRoute + '/' + data.id  + '.json',
                    headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                    data: $.param(data)
                });
            },
            destroy : function(id) {
                return $http.delete('api/' + modelRoute + '/' + id + '.json');
            }
        };

    }]);
