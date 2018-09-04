(function () {

    'use strict';

    angular
    .module('app')
    .controller('MyBalanceController', MyBalanceController);

    MyBalanceController.$inject = ['$scope', '$http', '$window', '$location', '$state', '$rootScope', 'API', 'BalanceModel'];

    function MyBalanceController($scope, $http, $window, $location, $state, $rootScope, API, BalanceModel) {

        $scope.touchBalanceDetail   = _touchBalanceDetail;
        $scope.touchWithDrawHistory = _touchWithDrawHistory;
        $scope.touchWithDraw        = _touchWithDraw;
        $scope.touchDialogCancel    = _touchDialogCancel;
        $scope.touchDialogConfirm   = _touchDialogConfirm;
        $scope.touchWithDrawAll     = _touchWithDrawAll;

        $scope.balanceModel         = BalanceModel;

        $scope.input = {
            withdraw : "",
            memo     : ""
        }

    	function _touchBalanceDetail() {
            $state.go('balance-history', {});
        }

        function _touchWithDrawHistory() {
            $state.go('withdraw-history', {});
        }

        function _touchWithDraw() {
            $scope.showDialog = true;
        }

        function _touchWithDrawAll() {
            $scope.input.withdraw = parseFloat($scope.balanceModel.balanceAmount);
        }


        function _touchDialogCancel() {
            $scope.showDialog = false;
            $scope.input.memo = "";
            $scope.input.withdraw = "";
        }

        function _touchDialogConfirm() {

            var withDraw = parseFloat($scope.input.withdraw);
            if(isNaN(withDraw)){
                $scope.toast('请输入数字');
                return;
            }

            if (withDraw <= 0) {
                $scope.toast('金额不能小于零');
                return;
            }


            if (withDraw > parseFloat($scope.balanceModel.balanceAmount)) {
                $scope.toast('金额超出提现范围');
                return;
            }

            if ($scope.input.memo.length == 0) {
                $scope.toast('Memo不能为空');
                return;
            }

            var params = {};
            params.cash = withDraw;
            params.memo = $scope.input.memo;

            API.withdraw.submit(params).then(function (withdraw) {
                $scope.balanceModel.reload();
                $scope.cancellingOrder = null;
                $scope.showDialog = false;
                $state.go('withdraw-success', {withdraw: withdraw.cash,member_memo:withdraw.member_memo});
            });

        }


        $scope.balanceModel.reload();

    }

})();
