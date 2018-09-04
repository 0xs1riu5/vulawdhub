<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=$goods['name']?></title>
    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <style media="screen">
        .page-header {
            text-align: center;
        }
        img {
            width: 100%;
            height: auto;
        }
    </style>

  </head>
  <body>
    <!-- Begin page content -->
    <div class="container">
      <p class="lead"><?=$goods['goods_desc']?></p>
    </div>
  </body>
</html>
