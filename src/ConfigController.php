<?php

namespace Liuhelong\Config;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ConfigController
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Config')
            ->description('list')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param int     $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Config')
            ->description('edit')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Config')
            ->description('create')
            ->body($this->form());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('Config')
            ->description('detail')
            ->body(Admin::show(ConfigModel::findOrFail($id), function (Show $show) {
                $show->id();
                $show->name();
                $show->value();
                $show->description();
                $show->created_at();
                $show->updated_at();
            }));
    }

    public function grid()
    {
        $grid = new Grid(new ConfigModel());

        $grid->column('id','ID')->sortable()->hide();
        $grid->column('name','键值')->display(function ($name) {
            return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
        });

        $grid->column('value','值')->display(function ($value, $column) {
			if ($this->type == 1) {
				return $value;
			}
			return $column->image();
		});
        $grid->column('description','描述');
        $grid->column('created_at','创建时间')->hide();
        $grid->column('updated_at','更新时间')->hide();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name','键值');
            $filter->like('value','值');
        });

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ConfigModel());

        $form->radio('type','类型')->options(['1'=>'文字','2'=>'图片'])->default(1)->required();
		$form->text('name','键值')->required();
		$form->textarea('value','值')->help('文字选填这个');;
		$form->image('image','图片')->help('图片选填这个');
		$form->textarea('description','描述');

        return $form;
    }
}
