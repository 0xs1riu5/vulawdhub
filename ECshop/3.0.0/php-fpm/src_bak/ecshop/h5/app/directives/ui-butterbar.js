angular.module('app')
	.directive('uiButterbar', ['$rootScope', '$anchorScroll', function ($rootScope, $anchorScroll) {
		return {
			restrict: 'AC',
			template: '<span class="bar"></span>',
			link: function (scope, el, attrs) {

				init();

				// Routing
				scope.$on('$stateChangeStart', function (event) {
					show()
				});
				scope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState) {
					event.targetScope.$watch('$viewContentLoaded', function () {
						hide()
					})
				});

				// Networking
				scope.$watch('activeCalls', function (newVal, oldVal) {
					if (newVal == 0) {
						hide()
					} else {
						show()
					}
				});

				function init() {
					el.addClass('butterbar active');
				}

				function show() {
					// $anchorScroll();
					el.removeClass('hide').addClass('active');
				}

				function hide() {
					el.addClass('hide').removeClass('active');
				}
			}
		};
	}]);