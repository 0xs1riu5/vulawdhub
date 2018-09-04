(function () {

	'use strict';

	angular
		.module('app')
		.directive('uiScrollTo', ['$location', '$anchorScroll', function ($location, $anchorScroll) {

			return {
				restrict: 'AC',
				link: function (scope, el, attr) {
					el.on('click', function (e) {
						$location.hash(attr.uiScrollTo);
						$anchorScroll();
					});
				}
			};

		}]);

})();