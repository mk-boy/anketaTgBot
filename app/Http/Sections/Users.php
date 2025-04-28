<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use App\Models\Role;

/**
 * Class Users
 *
 * @property \App\Models\User $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Users extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-users');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::link('name', 'Name')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::custom(trans('admin.role'), function(\App\Models\User $model) {
                return trans(Role::MAPPING_BY_ID[$model->role_id]);
            })->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('created_at', 'Created / updated', 'updated_at')->setHtmlAttribute('class', 'text-center'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center')
        ;

        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $form = AdminForm::panel()->addBody([
            AdminFormElement::text('name')->setLabel('full_name')->required(),
            AdminFormElement::text('email')->setLabel('Email')->required(),
            AdminFormElement::select('role_id', trans('admin.role'), Role::MAPPING_BY_ID)->required(),
        ]);

        $form->getButtons()->setButtons([
            'save_and_close'  => new SaveAndClose(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        $form = AdminForm::panel()->addBody([
            AdminFormElement::text('name')->setLabel('Name')->required(),
            AdminFormElement::text('email')->setLabel('Email')->required(),
            AdminFormElement::password('password', 'Password')->required()->addValidationRule('min:6')->hashWithBcrypt(),
            AdminFormElement::password('password_confirmation', 'Password confirmation')->required()->setValueSkipped(true)->addValidationRule('min:6')->addValidationRule('same:password', trans('admin.password_mismatch'))->hashWithBcrypt(),
            AdminFormElement::select('role_id', trans('admin.role'), Role::MAPPING_BY_ID)->required(),
        ]);

        $form->getButtons()->setButtons([
            'save_and_close'  => new SaveAndClose(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return true;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
