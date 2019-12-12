<?php

namespace Lararole\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use RecursiveRelationships\Traits\HasRecursiveRelationships;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Module extends Model
{
    use HasRecursiveRelationships, HasRelationships;

    protected $fillable = [
        'name', 'icon',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->slug = Str::slug($model->name, '_');

            $latestSlug = self::whereRaw("slug = '$model->slug'")->latest('id')->value('slug');
            if ($latestSlug) {
                $pieces = explode('_', $latestSlug);
                $number = intval(end($pieces));
                $model->slug .= '_' . ($number + 1);
            }
        });

        self::deleting(function ($model) {
            foreach ($model->children as $module) {
                $module->delete();
            }
        });
    }

    public function getParentKeyName()
    {
        return 'module_id';
    }

    public function create_modules(array $modules)
    {
        foreach ($modules as $module) {
            $sub_module = $this->children()->create([
                'name' => $module['name'],
                'icon' => @$module['icon'],
            ]);

            if (@$module['modules']) {
                $sub_module->create_modules($module['modules']);
            }
        }
    }

    public function users()
    {
        return $this->hasManyDeep(config('lararole.providers.users.model'), ['module_role', Role::class, 'role_user'])->withPivot('module_role', ['permission'], ModuleRole::class, 'permission');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('permission')->as('permission')->withTimestamps();
    }

    public function user()
    {
        return $this->users->where('id', auth()->user()->id)->first();
    }
}
