<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->id('ID')->sortable();
        $grid->title('商品名称');
        $grid->column('on_sale', '是否上架')->display(function ($value) {
          return $value == 1 ? '是' : '否';
        });
        $grid->price('价格');
        $grid->rating('评分');
        $grid->sold_count('销量');
        $grid->review_count('评论数');
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        $grid->actions( function ($actions) {
          $actions->disableDelete();
          $actions->disableView();
        });

        $grid->tools(function ($tools) {
          // 禁用批量删除按钮
          $tools->batch(function ($batch) {
            $batch->disableDelete();
          });
        });
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('image', __('Image'));
        $show->field('on_sale', __('On sale'));
        $show->field('rating', __('Rating'));
        $show->field('sold_count', __('Sold count'));
        $show->field('review_count', __('Review count'));
        $show->field('price', __('Price'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('title', '商品名称')->rules('required');
        $form->image('image', '封面图片')->rules('required|image');
        $form->quill('description', '商品描述')->rules('required');
        $form->radio('on_sale', '上架')->options(['1' => '是', '0' => '否'])->default('0');
        $form->hasMany('skus', 'SKU列表', function (Form\NestedForm $form) {
          $form->text('title', 'SKU名称')->rules('required');
          $form->text('description', 'SKU描述')->rules('required');
          $form->text('price', '单价')->rules('required|numeric|min:0.01');
          $form->text('stock', '剩余库存')->rules('required|integer|min:0');
        });

        $form->saving(function (Form $form) {
          $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        return $form;
    }

    public function store()
    {
      return $this->form()->store();
    }
}
