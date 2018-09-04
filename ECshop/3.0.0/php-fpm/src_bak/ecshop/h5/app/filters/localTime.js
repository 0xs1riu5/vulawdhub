(function(){

'use strict';

  angular
  .module('app')
  .filter('localTime', function() {

        return function(nS) {
          var format = function(time, format){
            var t = new Date(time);
            var tf = function(i){return (i < 10 ? '0' : '') + i};
            return format.replace(/yyyy|MM|dd|HH|mm|ss/g, function(a){
              switch(a){
                case 'yyyy':
                  return tf(t.getFullYear());
                  break;
                case 'MM':
                  return tf(t.getMonth() + 1); // 返回 用世界时表示时的月份，该值是 0（一月） ~ 11（十二月） 之间中的一个整数。所以要加1
                  break;
                case 'mm':
                  return tf(t.getMinutes());
                  break;
                case 'dd':
                  return tf(t.getDate());
                  break;
                case 'HH':
                  return tf(t.getHours());
                  break;
                case 'ss':
                  return tf(t.getSeconds());
                  break;
              };
            });
          };


      return format(parseInt(nS) * 1000, 'yyyy-MM-dd HH:mm:ss');
    }
  });

})();
