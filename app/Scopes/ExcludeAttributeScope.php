<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExcludeAttributeScope implements Scope
{
    protected $attribute;
    protected $value;

    public function __construct($attribute, $value)
    {
        $this->attribute = $attribute;
        $this->value = $value;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->where($this->attribute, '!=', $this->value);
    }

}
