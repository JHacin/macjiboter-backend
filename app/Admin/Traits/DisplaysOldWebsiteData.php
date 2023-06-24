<?php

namespace App\Admin\Traits;

use Illuminate\Support\Facades\DB;

trait DisplaysOldWebsiteData
{
    public function displayOldWebsiteData(string $entity): void
    {
        $migrationMeta = DB::table("db_migration_meta")
            ->where([
                "entity" => $entity,
                "new_id" => $this->crud->getCurrentEntry()->id,
            ])
            ->first();

        if ($migrationMeta === null) {
            return;
        }

        $this->crud->addField([
            'name' => 'prev_data',
            'type' => 'db_migration_meta',
            'tab' => 'Podatki iz stare strani',
            'data' => json_decode($migrationMeta->prev_data),
        ]);
    }
}
