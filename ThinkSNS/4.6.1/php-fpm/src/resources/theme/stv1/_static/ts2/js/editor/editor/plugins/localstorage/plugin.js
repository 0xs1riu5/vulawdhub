/**
 * localStorage support for ie<8
 * @author:yiminghe@gmail.com
 */
KISSY.Editor.add("localstorage", function() {
    var S = KISSY, KE = S.Editor,STORE;
    STORE = KE.STORE = /*S.UA.ie ? "localStorageKEFake" :*/ "localStorage";
    if (!KE['storeReady']) {
        KE.storeReady = function(run) {
            KE.on("storeReady", run);
        };
        function rewrite() {
            KE.storeReady = function(run) {
                run();
            };
            KE.detach("storeReady");
        }

        KE.on("storeReady", rewrite);
    } else {
        S.log("localstorage attach more", "warn");
        return;
    }
    function complete() {
        KE.fire("storeReady");
    }

    //原生或者已经定义过立即返回
    if (window[STORE]) {
        //原生的立即可用
        if (!window[STORE]._ke) {
            complete();
        }
        return;
    }

    //国产浏览器用随机数/时间戳试试 ! 是可以的
    var movie = KE.Utils.debugUrl("localstorage/swfstore.swf?t=" + (+new Date()));


    window[STORE] = new KE.FlashBridge({
        movie:movie,
        flashVars:{
            useCompression :true
        },
        methods:["setItem","removeItem","getValueOf"]
    });

    S.mix(window[STORE], {
        _ke:1,
        getItem:function(key) {
            return this['getValueOf'](key);
        }
    });

    //非原生，等待flash通知
    window[STORE].on("contentReady", function() {
        complete();
    });
    /*
     window[STORE].on("quotaExceededError", function() {
     alert("quotaExceededError");
     });

     window[STORE].on("error", function() {
     alert("error");
     });

     window[STORE].on("save", function() {
     alert("save");
     });

     window[STORE].on("inadequateDimensions", function() {
     alert("inadequateDimensions");
     });
     */
}, {
    //important
    //不能立即运行，ie6 可能会没有 domready 添加 flash 节点
    //导致：operation aborted
    attach:false,
    "requires":["flashutils","flashbridge"]
});