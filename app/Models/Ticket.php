<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project;
use App\Models\TicketDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'title', 'status', 'description', 'attachment_path'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function detail()
    {
        return $this->hasOne(TicketDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_CLOSED = 'closed';

    public static function allowedStatuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_CLOSED,
        ];
    }

    public function statusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class);
    }
}
