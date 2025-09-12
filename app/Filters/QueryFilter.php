<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QueryFilter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $filters;

    /**
     * QueryFilter constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->filters = $request->all();
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (method_exists($this, $name)) {
                if ($value !== null && $value !== '' && $value !== []) {
                    call_user_func_array([$this, $name], [$value]);
                } else {
                    call_user_func([$this, $name]);
                }
            }
        }

        return $this->builder;
    }

    public function set($name, $value = null)
    {
        $this->filters[$name] = $value;
    }

    public function filters(): array
    {
        return $this->filters;
    }

    public function has($name)
    {
        return isset($this->filters()[$name]);
    }

    public function get($name)
    {
        return $this->filters()[$name];
    }

    public function only($keys)
    {
        $keys = is_string($keys) ? explode(',', $keys) : $keys;

        return Arr::only($this->filters(), $keys);
    }

    public function forSearch(array $data)
    {
        $return = [];
        $only = $this->only(array_keys($data));
        foreach ($data as $key => $value) {
            if (isset($only[$key])) {
                $return[$value] = $only[$key];
            }
        }

        return $return;
    }
}
