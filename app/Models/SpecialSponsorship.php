<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\SpecialSponsorshipFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\SpecialSponsorship
 *
 * @property int $id
 * @property int $type
 * @property int|null $sponsor_id
 * @property int|null $payer_id
 * @property bool $is_gift
 * @property Carbon|null $confirmed_at
 * @property bool $is_anonymous
 * @property string $amount
 * @property string|null $gift_message
 * @property string|null $gift_notes
 * @property string|null $gift_requested_activation_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $payment_purpose
 * @property-read string $payment_reference_number
 * @property-read string $type_label
 * @property-read PersonData|null $payer
 * @property-read Collection|Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read PersonData|null $sponsor
 * @method static SpecialSponsorshipFactory factory(...$parameters)
 * @method static Builder|SpecialSponsorship newModelQuery()
 * @method static Builder|SpecialSponsorship newQuery()
 * @method static Builder|SpecialSponsorship query()
 * @method static Builder|SpecialSponsorship whereAmount($value)
 * @method static Builder|SpecialSponsorship whereConfirmedAt($value)
 * @method static Builder|SpecialSponsorship whereCreatedAt($value)
 * @method static Builder|SpecialSponsorship whereGiftMessage($value)
 * @method static Builder|SpecialSponsorship whereGiftNotes($value)
 * @method static Builder|SpecialSponsorship whereGiftRequestedActivationDate($value)
 * @method static Builder|SpecialSponsorship whereId($value)
 * @method static Builder|SpecialSponsorship whereIsAnonymous($value)
 * @method static Builder|SpecialSponsorship whereIsGift($value)
 * @method static Builder|SpecialSponsorship wherePayerId($value)
 * @method static Builder|SpecialSponsorship whereSponsorId($value)
 * @method static Builder|SpecialSponsorship whereType($value)
 * @method static Builder|SpecialSponsorship whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SpecialSponsorship extends Model implements BankTransferFields
{
    use CrudTrait, HasFactory, RevisionableTrait;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const TYPE_BOTER_MESECA = 1;

    public const TYPE_MUC_GRE_BREZ_SKRBI_V_NOVE_DNI = 2;

    public const TYPE_MUCA_GRE_BREZ_SKRBI_V_NOVE_DNI = 3;

    public const TYPE_NOV_ZACETEK = 4;

    public const TYPE_FIP_BOJEVNIK_ZA_1_DAN = 5;

    public const TYPE_FIP_BOJEVNIK_ZA_2_DNI = 6;

    public const TYPE_FIP_BOJEVNIK_ZA_1_TEDEN = 7;

    public const TYPE_MAJHNA_ZOBNA_MISKA = 8;

    public const TYPE_VELIKA_ZOBNA_MIS = 9;

    public const TYPES = [
        self::TYPE_BOTER_MESECA,
        self::TYPE_MUC_GRE_BREZ_SKRBI_V_NOVE_DNI,
        self::TYPE_MUCA_GRE_BREZ_SKRBI_V_NOVE_DNI,
        self::TYPE_NOV_ZACETEK,
        self::TYPE_FIP_BOJEVNIK_ZA_1_DAN,
        self::TYPE_FIP_BOJEVNIK_ZA_2_DNI,
        self::TYPE_FIP_BOJEVNIK_ZA_1_TEDEN,
        self::TYPE_MAJHNA_ZOBNA_MISKA,
        self::TYPE_VELIKA_ZOBNA_MIS,
    ];

    public const TYPE_LABELS = [
        self::TYPE_BOTER_MESECA => 'Mesečno botrstvo',
        self::TYPE_MUC_GRE_BREZ_SKRBI_V_NOVE_DNI => 'Muc gre brez skrbi v nove dni',
        self::TYPE_MUCA_GRE_BREZ_SKRBI_V_NOVE_DNI => 'Muca gre brez skrbi v nove dni',
        self::TYPE_NOV_ZACETEK => 'Nov začetek',
        self::TYPE_FIP_BOJEVNIK_ZA_1_DAN => 'FIP bojevnik za en dan',
        self::TYPE_FIP_BOJEVNIK_ZA_2_DNI => 'FIP bojevnik za dva dni',
        self::TYPE_FIP_BOJEVNIK_ZA_1_TEDEN => 'FIP bojevnik za en teden',
        self::TYPE_MAJHNA_ZOBNA_MISKA => 'Majhna zobna miška',
        self::TYPE_VELIKA_ZOBNA_MIS => 'Velika zobna miš',
    ];

    public const TYPE_AMOUNTS = [
        self::TYPE_BOTER_MESECA => 10,
        self::TYPE_MUC_GRE_BREZ_SKRBI_V_NOVE_DNI => 25,
        self::TYPE_MUCA_GRE_BREZ_SKRBI_V_NOVE_DNI => 35,
        self::TYPE_NOV_ZACETEK => 60,
        self::TYPE_FIP_BOJEVNIK_ZA_1_DAN => 25,
        self::TYPE_FIP_BOJEVNIK_ZA_2_DNI => 50,
        self::TYPE_FIP_BOJEVNIK_ZA_1_TEDEN => 175,
        self::TYPE_MAJHNA_ZOBNA_MISKA => 60,
        self::TYPE_VELIKA_ZOBNA_MIS => 120,
    ];

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'special_sponsorships';

    protected $guarded = ['id'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_gift' => 'boolean',
        'confirmed_at' => 'date',
        'gift_requested_activation_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(PersonData::class, 'sponsor_id');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(PersonData::class, 'payer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type];
    }

    public function getPaymentPurposeAttribute(): string
    {
        return self::TYPE_LABELS[$this->type].' - '.$this->sponsor->last_name;
    }

    public function getPaymentReferenceNumberAttribute(): string
    {
        return 'SI00 80-1'.$this->type.'-'.$this->id;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | CRUD-RELATED FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function identifiableName(): string
    {
        return $this->id;
    }
}
