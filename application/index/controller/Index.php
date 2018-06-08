<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        $a='a,b';$b='c';$c='d,e';
        var_dump(zuhe($a,$b,$c)) ;
    }
}
