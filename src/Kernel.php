<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use blackknight467\StarRatingBundle\StarRatingBundle as StarRatingBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

//    /**
//     * @return void
//     */
//    public function registerBundles()
//    {
//        $bundles = array(
//            new StarRatingBundle(),
//        );
//    }
}
