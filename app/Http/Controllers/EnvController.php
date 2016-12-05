<?php

namespace App\Http\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;

class EnvController extends Controller
{
    public function test()
    {
        $env = new DotenvEditor();

//        $env->changeEnv([
//            'TEST_ENTRY1' => 'one_new_value',
//            'TEST_ENTRY2' => $anotherValue,
//        ]);
    }
}
