<?php

namespace Audit\Interface\Http\Controllers\API\V1;

use Audit\Interface\Http\Requests\V1\AuditLogFilterRequest;
use F9Web\ApiResponseHelpers;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Carbon;
use Dedoc\Scramble\Attributes\Group;


#[Group('Auditoria')]
class AuditLogController
{
    use ApiResponseHelpers;

    /**
     * Listar logs de auditoria
     *
     * Retorna uma lista paginada de logs de auditoria filtráveis por tipo de log, usuário ou data.
     *
     * @response object{AnonymousResourceCollection<LengthAwarePaginator>}
     *
     * @authenticated
     */

    public function index(AuditLogFilterRequest $request)
    {
        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->when($request->log_name, fn($q, $log) => $q->where('log_name', $log))
            ->when($request->user_id, fn($q, $id) => $q->where('causer_id', $id))
            ->when($request->date, function ($q, $date) {
                $carbon = Carbon::parse($date);
                $q->whereDate('created_at', $carbon);
            })
            ->orderByDesc('created_at');

        return $this->respondWithSuccess($query->paginate());
    }
}
