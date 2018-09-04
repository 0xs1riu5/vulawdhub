(function(){

'use strict';

  angular
  .module('app')
  .filter('formatNickname', function() {
    return function(nickname) {
      return nickname;
    }
  });

})();
