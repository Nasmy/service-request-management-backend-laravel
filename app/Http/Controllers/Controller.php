<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
     *  @SWG\SecurityScheme(
     *      securityDefinition="basicAuth",
    *      type="basic"
    *  )
*/

/**
 * @OA\Info(
 *    title="SRM API documentation",
 *    version="1.0.0",
 * )
 */

 class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
