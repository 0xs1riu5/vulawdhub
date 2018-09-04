(function () {

	'use strict';

	angular
		.module('app')
		.directive('uiValidInput', [function () {

			return {
				restrict: 'A', // only activate on element attribute
				require: '?ngModel', // get a hold of NgModelController
				link: function (scope, element, attrs, ngModel) {

					if (!ngModel) return; // do nothing if no ng-model

					var options = scope.$eval(attrs.uiValidInput)

					var regex = options.pattern

					if (angular.isString(regex) && regex.length > 0) {
						regex = new RegExp('^' + regex + '$');
					}

					// 处理中文输入，输入框内先上拼音的情况
					// http://frontenddev.org/article/compatible-with-processing-and-chinese-input-method-to-optimize-the-input-events.html
					//
					// xiao'ming'tong'xue'hao'peng'you
					// 小明同学的好朋友
					//

					var cpLock = null;

					element.bind('compositionstart', function () {
						cpLock = true;
					})

					element.bind('compositionend', function () {
						cpLock = false;
					})

					scope.$watch(attrs.ngModel, function (newVal, oldVal) {
						if (cpLock == null || cpLock == false) {
							check(newVal);
						}
					});

					function check(input) {

						if (!angular.isString(input)) return;

						var valid = true;

						if (valid && options.max) {

							valid = input.length <= options.max
						}

						if (valid && options.min) {
							valid = input.length >= options.min
						}

						if (valid && regex) {
							valid = regex.test(input);
						}

						// console.log(valid)
						ngModel.$setValidity(attrs.name, valid)
					}
				}
			}
		}]);

})();