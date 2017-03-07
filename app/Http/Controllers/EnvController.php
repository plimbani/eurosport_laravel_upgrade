<?php

namespace Laraspace\Http\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;

class EnvController extends Controller
{
    public function test()
    {
        $env = new DotenvEditor();
    }
}
