<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use JavaScript;
use Laracasts\Utilities\JavaScript\JavaScriptServiceProvider;

class RulesController extends Controller
{
    public function rules() {
      JavaScript::put([
            'foo' => 'bar'
        ]);

        return View::make('rules.rules');
    }
}
