(function(){

	'use strict';

  	angular
  	.module('app')
  	.filter('trustHtml', ['$sce', '$injector', '$log', function($sce, $injector, $log) {
	    return function (input) {
	        return $sce.trustAsHtml(input);
	    }
	}]);

})();
