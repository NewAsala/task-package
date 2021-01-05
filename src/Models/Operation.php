<?php

namespace Akrad\Bridage\Models;

abstract class Operation extends Enum {

    const operatorForInteger = [ '<','>','=='];

    const operatorForString = ['like','in','=='];

    const operatorForDate = ['Date'];

    const other = ['=='];

    // const operatorForDate = [
    //     'lower' => "Date",
    // ];

}