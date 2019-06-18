<?php
/**
 * Created by PhpStorm.
 * User: pgk
 * Date: 6/18/19
 * Time: 3:41 PM
 */

namespace App\Http\Views\Composers;

use Illuminate\View\View;
use Spatie\Permission\Models\Role;


class RoleComposer
{
    /**
     * Available roles
     *
     * @var Role
     */
    protected $roles;

    /**
     * RoleComposer constructor.
     *
     * @param Role $roles
     */
    public function __construct(Role $roles)
    {
        // Dependencies automatically resolved by service container...
        $this->roles = $roles;
    }


    /**
     * Bind roles to the view
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('roles', $this->roles->all());
    }

}