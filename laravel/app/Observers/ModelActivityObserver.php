<?php

namespace App\Observers;

use App\Models\Order;

class ModelActivityObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        ActivityLog::create([
            'model'    => get_class($model),
            'model_id' => $model->id,
            'action'   => 'created',
            'changes'  => json_encode($model->toArray()),
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        ActivityLog::create([
            'model'    => get_class($model),
            'model_id' => $model->id,
            'action'   => 'updated',
            'changes'  => json_encode([
                'old' => $model->getOriginal(),
                'new' => $model->getChanges(),
            ]),
        ]);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        ActivityLog::create([
            'model'    => get_class($model),
            'model_id' => $model->id,
            'action'   => 'deleted',
            'changes'  => json_encode($model->toArray()),
        ]);
    }

}
