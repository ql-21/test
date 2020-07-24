<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

class UsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '地方用户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
//        $grid->model()->where('id',1);

        $grid->column('id', __('Id'));
        $grid->column('avatar','头像', __('Avatar'))->image('',50);
        $grid->column('name','用户名', __('Name'));
        $grid->column('nickname', '昵称');
        $grid->column('email', __('Email'));
//        $grid->column('email_verified_at','邮箱认证时间', __('Email verified at'));
//        $grid->column('password', __('Password'));
//        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

//        $grid->column('introduction', __('Introduction'));
//        $grid->column('notification_count', __('Notification count'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('avatar', __('Avatar'))->image();
        $show->field('introduction', __('Introduction'));
        $show->field('notification_count', __('Notification count'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
//        $form->text('remember_token', __('Remember token'));
        $form->image('avatar', __('Avatar'));
        $form->text('introduction', __('Introduction'));
        $form->number('notification_count', __('Notification count'));
        $form->saving(function (Form $form) {
            if($form->input('password')==null){
                $form->password=$form->model()->password;
            }
        });

        return $form;
    }
}
