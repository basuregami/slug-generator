<?php
/**
 * Created by PhpStorm.
 * User: basu
 * Date: 4/5/18
 * Time: 5:42 PM
 */

namespace OliveMedia\OliveFacade\slug;

use Illuminate\Support\Facades\Facade;


class SlugFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UniqueSlug';
    }

}