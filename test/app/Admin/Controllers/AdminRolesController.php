<?php


namespace App\Admin\Controllers;

use Dcat\Admin\Admin;
use Dcat\Admin\Controllers\RoleController;
use Dcat\Admin\Grid;
use Dcat\Admin\IFrameGrid;
use Dcat\Admin\Models\Repositories\Role;

class AdminRolesController extends RoleController
{
    public function grid()
    {
        if ($mini = request(IFrameGrid::QUERY_NAME)) {
            $grid = new IFrameGrid(new Role());
        } else {
            $grid = new Grid(new Role());
        }

        if(!Admin::user()->isAdministrator()){
            $grid->model()->where('id','!=',1);
        }

        $grid->id('ID')->sortable();
        $grid->slug->label('primary');
        $grid->name;

        if (! $mini) {
            $grid->created_at;
            $grid->updated_at->sortable();
        }

        $grid->disableBatchDelete();
        $grid->disableEditButton();
        $grid->showQuickEditButton();
        $grid->disableFilterButton();
        $grid->quickSearch(['id', 'name', 'slug']);
        $grid->enableDialogCreate();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $roleModel = config('admin.database.roles_model');
            if ($roleModel::isAdministrator($actions->row->slug)) {
                $actions->disableDelete();
            }
        });

        return $grid;
    }

}
