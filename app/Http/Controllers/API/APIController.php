<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class APIController
 * 
 * @package App\Http\Controllers\API
 * 
 * * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http","https"},
 *     host=L5_SWAGGER_CONST_HOST,
  *    @SWG\SecurityScheme(
 *         securityDefinition="Bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header",
 *     ),
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Form Api",
 *         description="Form API",
 *         @SWG\Contact(
 *         name = "Ramakrishna",
 *         email = "ramakrishna.p.applogiq@gmail.com"
 *           ),
 *         @SWG\License(
 *         name = "Developed by Ramakrishna",
 *         url = "ramakrishna.p.applogiq@gmail.com"
 *           )
 *     )
 * )
 */

class APIController extends Controller
{
    //
}
