(function () {

	'use strict';

	angular
		.module('app')
		.directive('uiValidLink', ['LinksService', function (LinksService) {

			return {
				restrict: 'A', // only activate on element attribute
				require: '?ngModel', // get a hold of NgModelController
				link: function (scope, element, attrs, ngModel) {
					if (!ngModel) return; // do nothing if no ng-model
					scope.$watch(attrs.ngModel, function (newVal, oldVal) {
						check(newVal);
					});

					function check(newVal) {
						if (!newVal) return;
						var result = LinksService.parseLink(newVal);
						scope.$evalAsync(function () {
							ngModel.$setValidity(attrs.name, result.valid)
						})
					}
				}
			};

		}]);

})();