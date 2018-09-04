(function () {

	'use strict';

	angular
		.module('app')
		.controller('InvoiceSelectController', InvoiceSelectController);

	InvoiceSelectController.$inject = ['$scope', '$http', '$rootScope', '$stateParams', '$state', 'API', 'ENUM', 'InvoiceSelectService'];

	function InvoiceSelectController($scope, $http, $rootScope, $stateParams, $state, API, ENUM, InvoiceSelectService) {

		$scope.input = {
			title: InvoiceSelectService.title
		};
		$scope.selectedType = InvoiceSelectService.type;
		$scope.selectedContent = InvoiceSelectService.content;

		$scope.types = null;
		$scope.contents = null;

		$scope.touchType = _touchType;
		$scope.touchContent = _touchContent;
		$scope.touchConfirm = _touchConfirm;
		$scope.touchToggle = _touchToggle;

		$scope.noInvoice = _checkNoInvoice();

		function _touchType(type) {
			// if ( $scope.noInvoice )
			//   return;

			$scope.selectedType = type;
			$scope.noInvoice = false;
		}

		function _touchContent(content) {
			// if ( $scope.noInvoice )
			//   return;

			$scope.selectedContent = content;
			$scope.noInvoice = false;
		}

		function _touchConfirm() {
			if ($scope.noInvoice) {

				$rootScope.$emit('invoiceChanged', {
					title: null,
					type: null,
					content: null
				});
				$scope.goBack();

			} else {

				if (!$scope.types) {
					$scope.toast('请选择发票类型')
					return;
				}

				if (!$scope.contents) {
					$scope.toast('请选择发票明细')
					return;
				}

				if (!$scope.input.title || !$scope.input.title.length) {
					$scope.toast('请填写发票抬头')
					return;
				}

				$rootScope.$emit('invoiceChanged', {
					title: $scope.input.title,
					type: $scope.selectedType,
					content: $scope.selectedContent
				});
				$scope.goBack();

			}
		}

		function _touchToggle() {
			$scope.noInvoice = $scope.noInvoice ? false : true;
			if ($scope.noInvoice) {
				$scope.input.title = null;
				$scope.selectedType = null;
				$scope.selectedContent = null;
			}
		}

		function _reloadInvoiceTypes() {
			API.invoice
				.typeList({})
				.then(function (types) {
					// name没有值的 就不显示
					var currentTtpes = new Array();

					for (var i = 0; i < types.length; i++) 
					{
						var type = types[i];

						if ( type.name.length ) {
							currentTtpes.push(type);
						}
					};

					$scope.types = currentTtpes;

					var selectedType = $scope.selectedType;
					if (selectedType) {
						for (var i = 0; i < types.length; ++i) {
							if (types[i].id == selectedType.id) {
								$scope.selectedType = types[i];
								break;
							}
						}
					}

					$scope.noInvoice = _checkNoInvoice();
				})
		}

		function _reloadInvoiceContents() {
			API.invoice
				.contentList({})
				.then(function (contents) {
					$scope.contents = contents;

					var selectedContent = $scope.selectedContent;
					if (selectedContent) {
						for (var i = 0; i < contents.length; ++i) {
							if (contents[i].id == selectedContent.id) {
								$scope.selectedContents = contents[i];
								break;
							}
						}
					}

					$scope.noInvoice = _checkNoInvoice();
				})
		}

		function _checkNoInvoice() {
			if ($scope.input.title && $scope.input.title.length)
				return false;
			if ($scope.selectedType)
				return false;
			if ($scope.selectedContent)
				return false;
			return true;
		}

		function _reload() {
			_reloadInvoiceTypes();
			_reloadInvoiceContents();
		}

		_reload();
	}

})();