<?php namespace Laraadmin\Entrust;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Laraadmin\Entrust
 */

use Laraadmin\Entrust\Contracts\EntrustRoleInterface;
use Laraadmin\Entrust\Traits\EntrustRoleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class EntrustRole extends Model implements EntrustRoleInterface
{
    use EntrustRoleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust.roles_table');
    }

}
