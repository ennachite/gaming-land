<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluriel', [$this, 'doSomething']),
        ];
    }

    public function doSomething(int $count, string $singular,string $plural): string
    {
       // $str= ($count ===1) : $plural ? $singular;

        if($count ===1){
            $str= $singular;
        }
        else{
            $str=$plural;
        }
        return "$count $str";
    }
}
