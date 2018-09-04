(function () {

	'use strict';

	angular
		.module('app')
		.directive('uiBack', ['$window', function ($window) {

			return {
				restrict: 'EA',
				link: function (scope, el, attrs) {
					el.click(function () {
						window.history.go(-1)
					});
				}
			};

		}]);

})();