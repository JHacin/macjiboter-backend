<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminSponsorshipMessageRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Mail\Client\TemplateApiClient;
use App\Mail\MailTemplateParser;
use App\Mail\SponsorshipMessageHandler;
use App\Models\Cat;
use App\Models\PersonData;
use App\Models\SponsorshipMessage;
use App\Models\SponsorshipMessageType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SponsorshipMessageCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation { store as traitStore; }

    private MailTemplateParser $mailTemplateParser;
    private TemplateApiClient $templateApiClient;
    private SponsorshipMessageHandler $sponsorshipMessageHandler;

    public function __construct(
        CrudPanel $crud,
        MailTemplateParser $mailTemplateParser,
        TemplateApiClient $templateApiClient,
        SponsorshipMessageHandler $sponsorshipMessageHandler
    ) {
        parent::__construct();
        $this->crud = $crud;
        $this->mailTemplateParser = $mailTemplateParser;
        $this->templateApiClient = $templateApiClient;
        $this->sponsorshipMessageHandler = $sponsorshipMessageHandler;
    }

    /**
     * @throws Exception
     */
    public function setup()
    {
        $this->crud->setModel(SponsorshipMessage::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.sponsorship_messages'));
        $this->crud->setEntityNameStrings('Pismo', 'Pisma');
        $this->crud->enableExportButtons();
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn([
            'name' => 'messageType',
            'label' => trans('sponsorship_message.message_type'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.sponsorship_message_types'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('messageType', function (Builder $query) use ($searchTerm) {
                    $query->where('name', 'like', "%$searchTerm%");
                });
            }
        ]);
        $this->crud->addColumn([
            'name' => 'sponsor',
            'label' => trans('sponsorship_message.sponsor'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.sponsors'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('sponsor', function (Builder $query) use ($searchTerm) {
                    $query->where('email', 'like', "%$searchTerm%");
                });
            }
        ]);
        $this->crud->addColumn([
            'name' => 'cat',
            'label' => trans('sponsorship_message.cat'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.cats'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('cat', function (Builder $query) use ($searchTerm) {
                    $query->where('name', 'like', "%$searchTerm%");
                });
            }
        ]);
        $this->crud->addColumn(CrudColumnGenerator::createdAt(['label' => 'Poslano']));

        $this->addFilters();
    }

    protected function addFilters()
    {
        $this->crud->addFilter(
            [
                'name' => 'messageType',
                'type' => 'select2',
                'label' => trans('sponsorship_message.message_type'),
            ],
            function () {
                return SponsorshipMessageType::all()->pluck('name', 'id')->toArray();
            },
            function ($value) {
                $this->crud->addClause('where', 'message_type_id', $value);
            }
        );
        $this->crud->addFilter(
            [
                'name' => 'sponsor',
                'type' => 'select2',
                'label' => trans('sponsorship_message.sponsor'),
            ],
            function () {
                return PersonData::all()->pluck('email_id', 'id')->toArray();
            },
            function ($value) {
                $this->crud->addClause('where', 'sponsor_id', $value);
            }
        );
        $this->crud->addFilter(
            [
                'name' => 'cat',
                'type' => 'select2',
                'label' => trans('sponsorship_message.cat'),
            ],
            function () {
                return Cat::all()->pluck('name_and_id', 'id')->toArray();
            },
            function ($value) {
                $this->crud->addClause('where', 'cat_id', $value);
            }
        );
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminSponsorshipMessageRequest::class);

        $this->crud->addField([
            'name' => 'messageType',
            'label' => trans('sponsorship_message.message_type'),
            'type' => 'relationship',
            'options' => function (Builder $query) {
                return $query->where('is_active', true)->get();
            },
            'placeholder' => 'Izberi vrsto pisma',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'messageType-wrapper'
            ]
        ]);

        $this->crud->addField([
            'name' => 'sponsor',
            'label' => trans('sponsorship_message.sponsor'),
            'type' => 'relationship',
            'placeholder' => 'Izberi botra',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'sponsor-wrapper'
            ]
        ]);
        $this->crud->addField([
            'name' => 'cat',
            'label' => trans('sponsorship_message.cat'),
            'type' => 'relationship',
            'placeholder' => 'Izberi muco',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'cat-wrapper'
            ]
        ]);
        $this->crud->addField([
            'name'  => 'separator_1',
            'type'  => 'custom_html',
            'value' => '<hr>'
        ]);
        $this->crud->addField([
            'name' => 'sponsor_sent_messages',
            'type' => 'view',
            'view' => 'admin/sponsor-sent-messages',
        ]);
        $this->crud->addField([
            'name'  => 'separator_2',
            'type'  => 'custom_html',
            'value' => '<hr>'
        ]);
        $this->crud->addField([
            'name' => 'parsed_template_preview',
            'type' => 'view',
            'view' => 'admin/parsed-template-preview',
        ]);
        $this->crud->addField([
            'name'  => 'separator_3',
            'type'  => 'custom_html',
            'value' => '<hr>'
        ]);
        $this->crud->addField([
            'name' => 'should_send_email',
            'label' => 'Želim, da se pismo:',
            'type' => 'select2_from_array',
            'options' => [
                true => 'Pošlje na botrov email naslov',
                false => 'Samo zapiše v bazo',
            ],
            'allows_null' => true,
            'wrapper' => [
                'dusk' => 'should_send_email-input-wrapper',
            ],
            'attributes' => [
                'required' => 'required'
            ],
        ]);
    }

    public function store(): RedirectResponse
    {
        $response = $this->traitStore();

        $request = $this->crud->getRequest();

        if ($request->input('should_send_email', false)) {
            /** @var SponsorshipMessage $msg */
            $msg = $this->crud->getCurrentEntry();
            $this->sponsorshipMessageHandler->send($msg);
        }

        return $response;
    }

    public function getMessagesSentToSponsor(PersonData $sponsor): JsonResponse
    {
        $this->crud->hasAccessOrFail('create');

        return response()->json($sponsor->sponsorshipMessages->load('messageType'));
    }

    public function getParsedTemplatePreview(Request $request): JsonResponse
    {
        $messageType = SponsorshipMessageType::find($request->query('message_type'));
        $templateId = $this->templateApiClient->retrieveTemplate($messageType->template_id);

        $sponsor = PersonData::find($request->query('sponsor'));
        $cat = Cat::find($request->query('cat'));

        $parsed = $this->mailTemplateParser->parse($templateId, [
            'ime_botra' => $sponsor->first_name,
            'ime_muce' => $cat->name,
        ]);

        return response()->json(['parsedTemplate' => $parsed]);
    }
}

