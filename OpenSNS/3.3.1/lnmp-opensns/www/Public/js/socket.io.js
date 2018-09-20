function SocketIO(address, port) {
    var address = /[\d]{1,3}.[\d]{1,3}.[\d]{1,3}.[\d]{1,3}/.test(address) ? address : '127.0.0.1';
    var port = port | '8000';
    var events = {};
    var callbacks = {};
    var ws = new WebSocket('ws://' + address + ':' + port);
    ws.onmessage = function (msg) {
        var data = JSON.parse(msg.data);
        var event = data.event;
        var etype = data.etype;
        var msg = data.msg;
        if (etype == 'callback' && typeof callbacks[event] !== undefined) {
            callbacks[event].call(this, msg);
        } else if (typeof events[event] !== undefined) {
            events[event].call(this, msg)
        }
    }

    this.on = function (event, callback) {
        switch (event) {
            case 'connect':
                ws.onopen = callback;
                break;
            case 'close':
                ws.onclose = callback;
                break;
            default:
                events[event] = callback;
                break;
        }
    }

    this.emit = function (event, msg, callback) {
        if (typeof callback !== undefined) callbacks[event] = callback;
        var data = {
            'event': event,
            'etype': typeof callback !== undefined ? 'callback' : 'event',
            'msg': msg
        };

        ws.send(JSON.stringify(data));
    }

    this.response = function (event, msg) {
        var data = {
            'event': event,
            'etype': 'callback',
            'msg': msg
        };
        ws.send(JSON.stringify(data));
    }

    this.close = function () {
        ws.close();
    }

}


socket_io = new SocketIO(Config.WEBSOCKET_ADDRESS, Config.WEBSOCKET_PORT);
socket_io.on('connect', function () {
    socket.login();

    socket_io.on('msg', function (msg) {
        $(window).trigger('ws:' + msg.type, [msg]);
    });

    socket_io.on('notice', function (msg) {
        console.log(msg);
    });
    socket_io.on('close', function () {
    });
});


var socket = {
    token:'',
    login: function () {
        var user = {
            uid: MID,
            timestamp: TIMESTAMP,
            signature:  SIGNATURE
        };
        socket_io.emit('login', user, function (rs) {
            this.token = rs.token;
        });
    },
    sendMsg: function (to, content, type, callback) {
        to = to || 'all';
        content = content || '';
        callback = callback || $.noop();
        type = type || '';
        if (content == '') {
            return false;
        }
        var data = {
            to: to,
            content: content,
            type: type,
            token:this.token
        };
        socket_io.emit('msg', data, function (ok) {
            if (ok) callback;
        })
    }
};


$(window).on('focus', function () {
    socket.login();
});