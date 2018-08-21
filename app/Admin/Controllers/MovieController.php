<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/20
 * Time: 15:54
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Movie;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use App\Models\User;


class MovieController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('产品列表');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    protected function form()
    {
        return Admin::form(Movie::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('username', '用户名')->rules('required|min:10');

            // 第五列显示为rate字段
//            $form->display();
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    protected function grid()
    {

        return Admin::grid(Movie::class, function(Grid $grid){

            // 第一列显示id字段，并将这一列设置为可排序列
            $grid->id('ID')->sortable();

            // 直接通过字段名`username`添加列
            $grid->title('用户名');

            // 第三列显示director字段，通过display($callback)方法设置这一列的显示内容为users表中对应的用户名
            $grid->director()->display(function($userId) {
                return User::find($userId)->name;
            });

            // 第四列显示为describe字段
            $grid->describe();

            // 第五列显示为rate字段
            $grid->rate();

            // 第六列显示released字段，通过display($callback)方法来格式化显示输出
            $grid->released('上映?')->display(function ($released) {
                return $released ? '是' : '否';
            });

            // 下面为三个时间字段的列显示
            $grid->release_at();
            $grid->created_at();
            $grid->updated_at();

            // filter($callback)方法用来设置表格的简单搜索框
            $grid->filter(function ($filter) {

                // 设置created_at字段的范围查询
                $filter->between('created_at', '创建时间')->datetime();
            });
        });
    }
}