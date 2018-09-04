(function () {

    'use strict';

    angular
        .module('app')
        .controller('BonusController', BonusController);

    BonusController.$inject = ['$scope', '$rootScope', '$window', '$timeout', '$location', '$state', '$stateParams', 'API', 'ENUM', 'BonusModel','ConfigModel'];

    function BonusController($scope, $rootScope, $window, $timeout, $location, $state, $stateParams, API, ENUM, BonusModel,ConfigModel) {
        $scope.cancellingOrder  = null;
        $scope.showDialog       = false;
        $scope.bonus_amount     = 0;
        $scope.total_bonus      = 0;
        $scope.myBonusModel     = BonusModel;

        $scope.touchDialogCancel    = _touchDialogCancel;
        $scope.touchDialogConfirm   = _touchDialogConfirm;
        $scope.touchRecommend       = _touchRecommend;
        $scope.touchMyBalance       = _touchMyBalance;
        $scope.touchRules           = _touchRules;

        $scope.touchCancel          = _touchCancel;
        $scope.getBonusStatus       = _getBonusStatus;
        $scope.getBonusType         = _getBonusType;

        function _touchCancel(order) {
            $scope.cancellingOrder = order;
            $scope.showDialog = true;
        }

        function _touchDialogCancel() {
            $scope.cancellingOrder = null;
            $scope.showDialog = false;
        }

        function _touchDialogConfirm() {
            API.order.cancel({
                order: $scope.cancellingOrder.id,
                reason: 1
            }).then(function (order) {
                $scope.myOrderModel.reload();
                $scope.cancellingOrder = null;
                $scope.showDialog = false;
            });
        }

        function _getBonusStatus(status) {
            if (status == ENUM.BONUS_STATUS.WAIT) {
                return "审核中";
            }
            else if (status == ENUM.BONUS_STATUS.FINISH) {
                return "已分成";
            }
            else if (status == ENUM.BONUS_STATUS.CANCEL) {
                return "已取消";
            }
            else if (status == ENUM.BONUS_STATUS.REVOKE) {
                return "已撤销";
            }
            return "";
        }

        function _getBonusType(type) {
            if (type == ENUM.BONUS_TYPE.SIGNUP) {
                return "注册分成";
            }
            else {
                return "订单分成";
            }
        }

        function _touchRecommend() {
            $state.go('my-recommend', {});
        }

        function _touchMyBalance(){
            $state.go('my-balance', {});
        }

        function _touchRules(){
            $state.go('bonus-rule', {
            });
        }

        $scope.myBonusModel.reload();

    }

})();