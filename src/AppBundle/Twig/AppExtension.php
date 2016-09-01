<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('money', array($this, 'moneyFilter')),
        ];
    }

    public function moneyFilter($number, $decimal = 2)
    {
        $money = number_format($number, $decimal);

        return '$' . $money;
    }

    public function getName()
    {
        return 'app_extension';
    }
}