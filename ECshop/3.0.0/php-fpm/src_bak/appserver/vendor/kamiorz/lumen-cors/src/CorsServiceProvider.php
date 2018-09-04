<?php
/**
 * HomePage: https://github.com/KamiOrz
 * Fixed by XiaoGai.
 * Date: 2016/3/17
 * Time: 17:00
 */

namespace KamiOrz\Cors;

use Illuminate\Support\ServiceProvider;

class CorsServiceProvider extends ServiceProvider
{
    /**
     * Register OPTIONS route to any requests
     */
    public function register()
    {

        $config = $this->app['config']['cors'];

        $this->app->bind('KamiOrz\Cors\CorsService', function() use ($config){
            return new CorsService($config);
        });

        /** @var \Illuminate\Http\Request $request */
        $request = $this->app->make('request');
            if($request->isMethod('OPTIONS')) {
            $this->app->options($request->path(), function(){
                return response('', 200);
            });
        }
    }

}