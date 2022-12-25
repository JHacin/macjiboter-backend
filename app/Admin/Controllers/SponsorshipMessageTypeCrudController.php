<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminSponsorshipMessageTypeRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Models\SponsorshipMessageType;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\ReviseOperation\ReviseOperation;
use Exception;
use Illuminate\Support\Facades\Auth;

class SponsorshipMessageTypeCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ReviseOperation;

    /**
     * @throws Exception
     */
    public function setup()
    {
        $this->crud->setModel(SponsorshipMessageType::class);
        $this->crud->setRoute(
            config('backpack.base.route_prefix') . '/' . config('routes.admin.sponsorship_message_types')
        );
        $this->crud->setEntityNameStrings('Vrsta pisma', 'Vrste pisem');
        $this->setupAccessToOperations();
        $this->crud->enableExportButtons();
    }

    protected function setupAccessToOperations()
    {
        $user = Auth::user();

        if (!$user->hasRole(User::ROLE_SUPER_ADMIN)) {
            $this->crud->denyAccess(['create', 'update', 'delete', 'revise']);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn(CrudColumnGenerator::name());
        $this->crud->addColumn([
            'name' => 'template_id',
            'label' => trans('sponsorship_message_type.template_id'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'is_active',
            'label' => trans('sponsorship_message_type.is_active'),
            'type' => 'boolean',
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminSponsorshipMessageTypeRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => trans('model.name'),
            'type' => 'text',
            'hint' => 'Ime mora biti unikatno.',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'name-wrapper'
            ],
        ]);

        $this->crud->addField([
            'name' => 'subject',
            'label' => trans('sponsorship_message_type.subject'),
            'type' => 'text',
            'hint' => 'Naslov, ki bo prikazan v mailu.',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'subject-wrapper'
            ],
        ]);

        $this->crud->addField([
            'name' => 'template_id',
            'label' => trans('sponsorship_message_type.template_id'),
            'type' => 'text',
            'hint' => 'Šifra predloge v storitvi za pošiljanje mailov. Mora biti unikatna.',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'template_id-wrapper'
            ],
        ]);
        $this->crud->addField([
            'name' => 'is_active',
            'label' => trans('sponsorship_message_type.is_active'),
            'type' => 'checkbox',
            'hint' => 'Če pismo ni aktivno, ne bo na voljo med možnostmi pri pošiljanju botrom.',
            'wrapper' => [
                'dusk' => 'is_active-wrapper',
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
