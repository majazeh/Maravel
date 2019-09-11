<?php

namespace Maravel\Controllers;

use Illuminate\Http\Request;

trait APIMethods
{
    use Methods\Index;
    use Methods\Show;
    use Methods\Store;
    use Methods\Update;
    use Methods\Destroy;
}
