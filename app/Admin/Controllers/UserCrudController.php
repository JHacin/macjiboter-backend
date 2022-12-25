<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminUserCreateRequest;
use App\Admin\Requests\AdminUserUpdateRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Admin\Utilities\CrudFieldGenerator;
use App\Admin\Utilities\CrudFilterGenerator;
use App\Mail\UserMail;
use App\Models\User;
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as BackpackUserCrudController;
use Backpack\ReviseOperation\ReviseOperation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserCrudController extends BackpackUserCrudController
{
    use ReviseOperation;

    private UserMail $userMail;

    public function __construct(UserMail $userMail)
    {
        parent::__construct();
        $this->userMail = $userMail;
    }

    /**
     * @inheritDoc
     */
    public function setup()
    {
        parent::setup();
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.users'));
        $this->crud->enableExportButtons();
    }

    /**
     * Define what is displayed in the List view.
     *
     * @return void
     */
    public function setupListOperation()
    {
        parent::setupListOperation();
        $this->crud->addColumn(CrudColumnGenerator::id())->makeFirstColumn();

        $this->crud->removeColumn('permissions');

        $this->crud->modifyColumn('name', ['label' => trans('user.name')]);

        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());
    }

    /**
     * Add common fields (for create & update).
     *
     * @return void
     */
    protected function addUserFields()
    {
        parent::addUserFields();

        $this->crud->modifyField('name', [
            'label' => trans('user.name'),
            'wrapper' => [
                'dusk' => 'name-input-wrapper'
            ],
        ]);
        $this->crud->modifyField('email', [
            'wrapper' => [
                'dusk' => 'email-input-wrapper'
            ],
        ]);
        $this->crud->modifyField('password', [
            'wrapper' => [
                'dusk' => 'password-input-wrapper'
            ],
        ]);
    }

    /**
     * Define what is displayed in the Create view.
     *
     * @return void
     */
    public function setupCreateOperation()
    {
        parent::setupCreateOperation();

        $this->crud->setValidation(AdminUserCreateRequest::class);

        $this->crud->addField([
            'name' => 'should_send_welcome_email',
            'label' => 'Pošlji obvestilo o ustvarjenem računu?',
            'type' => 'checkbox',
            'hint' => 'Uporabnik bo na svoj email naslov prejel sporočilo, v katerem se mu izreče dobrodošlica.',
            'wrapper' => [
                'dusk' => 'should_send_welcome_email-input-wrapper',
            ],
        ]);
    }

    /**
     * Define what is displayed in the Update view.
     *
     * @return void
     */
    public function setupUpdateOperation()
    {
        parent::setupUpdateOperation();
        $this->crud->setValidation(AdminUserUpdateRequest::class);
    }

    /**
     * Actions taken after the user is inserted.
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $response = parent::store();

        /** @var Request $request */
        $request = $this->crud->getRequest();

        if ($request->input('should_send_welcome_email', false)) {
            /** @var User $user */
            $user = $this->crud->getCurrentEntry();

            $this->userMail->sendWelcomeEmail($user);
        }

        return $response;
    }
}
