<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Soft delete + catat user yang membuat, mengubah, dan menghapus data.
 * Membutuhkan kolom created_by, updated_by, deleted_by, dan deleted_at.
 */
trait Auditable
{
    use SoftDeletes;

    public static function bootAuditable(): void
    {
        static::creating(function ($model) {
            $model->created_by ??= Auth::id();
            $model->updated_by ??= Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? $model->updated_by;
        });

        static::softDeleted(function ($model) {
            $model->deleted_by = Auth::id();
            $model->saveQuietly();
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
