<?php

use App\Models\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRoleAndPermissionData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //创建权限
        Permission::create(['name'=>'manage_contents']);
        Permission::create(['name'=>'manage_user']);
        Permission::create(['name'=>'edit_setting']);

        //创建站长角色
        $founderRole=Role::create(['name'=>'Founder']);
        $founderRole->givePermissionTo('manage_contents');
        $founderRole->givePermissionTo('manage_user');
        $founderRole->givePermissionTo('edit_setting');

        //创建管理员
        $founderRole=Role::create(['name'=>'Admin']);
        $founderRole->givePermissionTo('manage_contents');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //清除缓存，否则会报错
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        //清空所有数据表数据
        $table_names=config('permission.table_names');
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table($table_names['permissions'])->truncate();
        DB::table($table_names['roles'])->truncate();
        DB::table($table_names['model_has_permissions'])->truncate();
        DB::table($table_names['model_has_roles'])->truncate();
        DB::table($table_names['role_has_permissions'])->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        Model::reguard();

    }
}
