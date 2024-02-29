<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminUserCreateRequest;
use App\Admin\Requests\AdminUserUpdateRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Mail\UserMail;
use App\Models\User;
use Backpack\CRUD\app\Exceptions\BackpackProRequiredException;
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as BackpackUserCrudController;
use Backpack\ReviseOperation\ReviseOperation;
use Illuminate\Http\RedirectResponse;

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
     * @throws BackpackProRequiredException
     */
    public function setup(): void
    {
        parent::setup();
        $this->crud->setRoute(config('backpack.base.route_prefix').'/'.config('routes.admin.users'));
        $this->crud->enableExportButtons();
    }

    public function setupListOperation(): void
    {
        parent::setupListOperation();
        $this->crud->addColumn(CrudColumnGenerator::id())->makeFirstColumn();
        $this->crud->removeColumn('permissions');
        $this->crud->modifyColumn('name', ['label' => trans('user.name')]);
        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());
    }

    protected function addUserFields(): void
    {
        parent::addUserFields();

        $this->crud->modifyField('name', [
            'label' => trans('user.name'),
            'wrapper' => [
                'dusk' => 'name-input-wrapper',
            ],
        ]);
        $this->crud->modifyField('email', [
            'wrapper' => [
                'dusk' => 'email-input-wrapper',
            ],
        ]);
        $this->crud->modifyField('password', [
            'wrapper' => [
                'dusk' => 'password-input-wrapper',
            ],
        ]);
    }

    public function setupCreateOperation(): void
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

    public function setupUpdateOperation(): void
    {
        parent::setupUpdateOperation();
        $this->crud->setValidation(AdminUserUpdateRequest::class);
    }

    public function store(): RedirectResponse
    {
        $response = parent::store();
        $request = $this->crud->getRequest();

        if ($request->input('should_send_welcome_email', false)) {
            /** @var User $user */
            $user = $this->crud->getCurrentEntry();
            $this->userMail->sendWelcomeEmail($user);
        }

        return $response;
    }
}
