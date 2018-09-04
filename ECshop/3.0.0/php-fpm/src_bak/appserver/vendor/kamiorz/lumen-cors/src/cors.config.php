<?php

return [
    //HTTP响应头

    /**
     * 允许向该服务器提交请求（跨站）的URI，对于一个不带有credentials的请求,可以指定为'*',表示允许来自所有域的请求.
     */
    'allowOrigins' => [
        'http://127.0.0.1:8080',    //换成实际域名
    ],

    /**
     * 在响应预检请求的时候使用.用来指明在实际的请求中,可以使用哪些自定义HTTP请求头.
     */
   // 'allowHeaders' => ['Content-Type', 'Origin', 'accept', 'Authorization'],
    'allowHeaders' => ['*'],

    /**
     * 指明资源可以被请求的方式有哪些(一个或者多个). 这个响应头信息在客户端发出预检请求的时候会被返回.
     */
    'allowMethods' => ['GET','POST','PUT','DELETE','OPTIONS'],

    /**
     * 附带凭证信息的请求，默认是true（附带）
     *
     * 对于跨站请求，浏览器是不会发送凭证信息。
     * XMLHttpRequest的withCredentials标志设置为true，从而使得Cookies可以随着请求发送，但是，如果服务器端的响应中，
     * 如果没有返回Access-Control-Allow-Credentials: true的响应头，那么浏览器将不会把响应结果传递给发出请求的脚步程序，
     * 以保证信息的安全。
     *
     */
    'allowCredentials' => true,

    /**
     * 设置浏览器允许访问的服务器的头信息的白名单:
     */
    'exposeHeaders' => [],

    /**
     * 本次“预请求 OPTIONS ”的响应结果有效时间（单位：秒）， 默认1天
     * 浏览器在处理针对该服务器的跨站请求，都可以无需再发送“预请求”，只需根据本次结果进行处理
     *
     * “预请求”要求必须先发送一个 OPTIONS 请求给目的站点，来查明这个跨站请求对于目的站点是不是安全可接受的
     * 需要发送预请求的方法： POST,PUT,DELETE
     * 从Gecko 2.0开始，text/plain, application/x-www-form-urlencoded 和 multipart/form-data 类型的数据
     * 都可以直接用于跨站请求，而不需要先发起“预请求”了
     *
     */
    'maxAge' => 86400
];
