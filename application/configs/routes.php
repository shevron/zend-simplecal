<?php

return array(
    'month' => array(
        'route' => 'month/:year/:month',
        'defaults' => array(
            'controller' => 'index',
            'action'     => 'index'
        )
    )
);