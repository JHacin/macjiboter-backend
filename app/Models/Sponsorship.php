<?php

namespace App\Models;

use App\Models\Traits\ClearsGlobalScopes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\SponsorshipFactory;
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
 * App\Models\Sponsorship
 *
 * @property int $id
 * @property int|null $cat_id
 * @property int|null $sponsor_id
 * @property int|null $payer_id
 * @property bool $is_gift
 * @property bool $is_anonymous
 * @property int $payment_type
 * @property string $monthly_amount
 * @property int|null $requested_duration
 * @property bool $is_active
 * @property Carbon|null $ended_at
 * @property string|null $gift_message
 * @property string|null $gift_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Cat|null $cat
 * @property-read string $payment_purpose
 * @property-read string $payment_reference_number
 * @property-read PersonData|null $payer
 * @property-read Collection|Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read PersonData|null $sponsor
 * @method static SponsorshipFactory factory(...$parameters)
 * @method static Builder|Sponsorship newModelQuery()
 * @method static Builder|Sponsorship newQuery()
 * @method static Builder|Sponsorship query()
 * @method static Builder|Sponsorship whereCatId($value)
 * @method static Builder|Sponsorship whereCreatedAt($value)
 * @method static Builder|Sponsorship whereEndedAt($value)
 * @method static Builder|Sponsorship whereGiftMessage($value)
 * @method static Builder|Sponsorship whereGiftNotes($value)
 * @method static Builder|Sponsorship whereId($value)
 * @method static Builder|Sponsorship whereIsActive($value)
 * @method static Builder|Sponsorship whereIsAnonymous($value)
 * @method static Builder|Sponsorship whereIsGift($value)
 * @method static Builder|Sponsorship whereMonthlyAmount($value)
 * @method static Builder|Sponsorship wherePayerId($value)
 * @method static Builder|Sponsorship wherePaymentType($value)
 * @method static Builder|Sponsorship whereRequestedDuration($value)
 * @method static Builder|Sponsorship whereSponsorId($value)
 * @method static Builder|Sponsorship whereUpdatedAt($value)
 * @property-read \App\Models\Cat|null $unscopedCat
 * @mixin Eloquent
 */
class Sponsorship extends Model implements BankTransferFields
{
    use HasFactory, CrudTrait, RevisionableTrait, ClearsGlobalScopes;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_TYPE_BANK_TRANSFER = 1;
    public const PAYMENT_TYPE_DIRECT_DEBIT = 2;

    public const PAYMENT_TYPES = [
        self::PAYMENT_TYPE_BANK_TRANSFER,
        self::PAYMENT_TYPE_DIRECT_DEBIT,
    ];

    public const PAYMENT_TYPE_LABELS = [
        self::PAYMENT_TYPE_BANK_TRANSFER => 'Nakazilo',
        self::PAYMENT_TYPE_DIRECT_DEBIT => 'Trajnik',
    ];

    public const SCOPE_ONLY_ACTIVE = 'sponsorship_onlyActive';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sponsorships';
    protected $guarded = ['id'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ended_at' => 'date',
        'is_anonymous' => 'boolean',
        'is_active' => 'boolean',
        'is_gift' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function cancel()
    {
        $this->update([
            'is_active' => false,
            'ended_at' => Carbon::now()->toDateString(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class, 'cat_id');
    }

    /** @noinspection PhpUnused */
    public function unscopedCat(): BelongsTo
    {
        return $this->cat()->withoutGlobalScopes();
    }

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

    /**
     * @inheritDoc
     */
    protected static function booted()
    {
        static::addGlobalScope(self::SCOPE_ONLY_ACTIVE, function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPaymentPurposeAttribute(): string
    {
        if (!$this->cat) {
            return '/';
        }

        $formattedCatName = mb_strtoupper(str_replace(' ', '-', $this->cat->name));

        return 'BOTER-' . $formattedCatName . '-' . $this->cat->id;
    }

    public function getPaymentReferenceNumberAttribute(): string
    {
        if (!$this->cat || !$this->sponsor) {
            return '/';
        }

        return 'SI00 80-0' . $this->cat_id . '-' . $this->sponsor_id;
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
