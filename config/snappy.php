<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => '/usr/local/bin/wkhtmltopdf',
        //'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf" --enable-local-file-access', // for windows
        'timeout' => false,
        'options' => array(
            'encoding' => 'utf-8'
        ),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/local/bin/wkhtmltoimage',
        //'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage" --enable-local-file-access', // for windows
        'timeout' => false,
        'options' => array(
            'encoding' => 'utf-8'
        ),
        'env'     => array(),
    ),
);