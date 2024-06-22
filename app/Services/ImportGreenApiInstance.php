<?php

namespace App\Services;

use App\Enums\InstanceStatus;
use App\Integrations\GreenApi\Data\GreenApiInstanceData;
use App\Integrations\GreenApi\GreenApi;
use App\Models\Instance;

class ImportGreenApiInstance
{
    public function execute(): void
    {
        $greenApi = new GreenApi('', '');
        /**
         * @var GreenApiInstanceData[] $greenInstances
         */
        $greenInstances = $greenApi->partner()->getInstances()->dto();

        $instances = Instance::all();
        foreach ($greenInstances as $instance) {
            $i = $instances->firstWhere('instance_id', $instance->idInstance);
            $status = $instance->deleted
                ? InstanceStatus::DELETED
                : InstanceStatus::ACTIVE;
            if ($i) {
                $i->update([
                    'name' => $instance->name,
                    'status' => $status,
                    'payload' => $instance->toArray(),
                ]);
            } else {
                Instance::create([
                    'instance_id' => $instance->idInstance,
                    'instance_token' => $instance->apiTokenInstance,
                    'name' => $instance->name,
                    'status' => $status,
                    'payload' => $instance->toArray(),
                ]);
            }
        }
    }
}
