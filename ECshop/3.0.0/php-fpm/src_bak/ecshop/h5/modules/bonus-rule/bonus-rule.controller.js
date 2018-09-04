/**
 * Created by howiezhang on 16/9/27.
 */
(function () {

    'use strict';

    angular
        .module('app')
        .controller('BonusRuleController', BonusRuleController);

    BonusRuleController.$inject = ['$scope', '$http','$rootScope', '$window','$state', '$location', '$stateParams', 'BonusModel', 'API', '$sce'];

    function BonusRuleController($scope, $http,$rootScope, $window, $state, $location, $stateParams, BonusModel, API, $sce) {

        $scope.bonusModel = BonusModel;

        if($scope.bonusModel.bonus_info){
            $scope.rule_desc = $sce.trustAsHtml($scope.bonusModel.bonus_info.rule_desc);
        }

        API.bonus.get().then(function (bonus_info) {
            $scope.rule_desc = $sce.trustAsHtml(bonus_info.rule_desc);

        });

    }

})();
