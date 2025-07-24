<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetFile extends Model
{
    protected $fillable = [
        'asset_detail_id',
        'file_attachments',
    ];

    /**
     * Get the asset detail that owns the asset file.
     */
    public function assetDetail(): BelongsTo
    {
        return $this->belongsTo(AssetDetail::class);
    }
}
