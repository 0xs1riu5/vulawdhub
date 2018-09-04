(function () {

	'use strict';

	angular
		.module('app')
		.controller('ChangePasswordController', ChangePasswordController);

	ChangePasswordController.$inject = ['$scope', '$http', '$window', '$location', '$state', 'API', 'ENUM'];

	function ChangePasswordController($scope, $http, $window, $location, $state, API, ENUM) {

		$scope.oldPassword = "";
		$scope.newPassword = "";

		$scope.touchChangePassword = touchChangePassword;

		function touchChangePassword() {

			var oldPassword = $scope.oldPassword;
			var newPassword = $scope.newPassword;
			var newPassword2 = $scope.newPassword2;

			if (newPassword != newPassword2) {
				$scope.toast("两次密码输入不一致");
				return;
			}

			API.user.passwordUpdate({
					old_password: oldPassword,
					password: newPassword
				})
				.then(function (res) {
					if (res.data.error_code == 0) {
						$scope.toast('密码修改成功');
						$scope.goBack();
					} else {
						$scope.toast(res.data.error_desc);
					}

				});

		}

	}

})();