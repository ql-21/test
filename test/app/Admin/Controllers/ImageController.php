<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Image;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class ImageController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Image(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->user_id;
            $grid->column('type','图片类型');
            $grid->path;
            $grid->created_at;
            $grid->updated_at->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Image(), function (Show $show) {
            $show->id;
            $show->user_id;
            $show->type;
            $show->path;
            $show->created_at;
            $show->updated_at;
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Image(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('type');
            $form->image('path');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
