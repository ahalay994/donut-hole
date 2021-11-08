<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'sex',
        'salary',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return string[]
     */
    public static function validate(): array
    {
        return [
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'required',
            'salary' => 'integer|required',
            'departments' => 'array|required',
        ];
    }

    /**
     * @return BelongsToMany
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }
}
