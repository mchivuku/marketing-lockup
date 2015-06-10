<?php namespace App\Http\Middleware;

use App\Models\AppUser;
use App\User;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use Closure;
use PhpSpec\Exception\Example\ExampleException;

class Authenticate  {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if ( empty($_SERVER['HTTP_CAS_USER'])){
            //Redirect to the cas authentication page.
            throw new Exception("You are Not authenticated");
        }
        else{
            $fuser = AppUser::find($_SERVER['HTTP_CAS_USER']);
            if ( empty($fuser)){
                $user = new AppUser();
                $user -> username = $_SERVER['HTTP_CAS_USER'] ;
                $user -> email = $_SERVER['HTTP_CAS_USER'] . "@indiana.edu";
                $user-> save();
            }
           return $next($request);

        }
	}

}
