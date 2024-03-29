<?php

namespace BYanelli\SelfValidatingModels\Tests\TestApp;

use BYanelli\SelfValidatingModels\SelfValidatesWithRules;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use SelfValidatesWithRules;

    // This property takes precedence (in case the name "rules" is taken by a database column, for example)
    public $validationRules = [
        'email' => 'required|string|email',
    ];

    // This has no effect when "validationRules" is present
    public $rules = [
        'email' => 'this_is_not_used|not_actual_validators'
    ];

    // This property takes precedence (in case the name "messages" is taken by a database column, for example)
    public $validationMessages = [
        'email' => 'The :attribute isn\'t a valid email bro',
    ];

    // This has no effect when "validationMessages" is present
    public $messages = [
        'email' => 'zzz'
    ];
}
