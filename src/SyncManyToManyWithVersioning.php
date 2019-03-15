<?php

namespace Kiqstyle\EloquentVersionable;

class SyncManyToManyWithVersioning
{
    private $entity;
    private $manyToManyRelation;
    private $fields = [
        'entityKey' => null,
        'relationKey' => null
    ];

    public function run($entity, array $newRelationsIds, $manyToManyRelation, array $fields)
    {
        $this->entity = $entity;
        $this->manyToManyRelation = $manyToManyRelation;
        $this->fields = $fields;

        $oldRelationsIds = $manyToManyRelation->where($this->fields['entityKey'], $this->entity->id)
            ->pluck($this->fields['relationKey'])
            ->toArray();

        $relationsToExclude = array_diff($oldRelationsIds, $newRelationsIds);
        $relationsToInclude = array_diff($newRelationsIds, $oldRelationsIds);

        $this->removeRelations($relationsToExclude);
        $this->createRelations($relationsToInclude);
    }

    private function removeRelations($relationsToExclude)
    {
        foreach ($relationsToExclude as $relationId) {
            $relationToExclude = $this->manyToManyRelation->where($this->fields['relationKey'], $relationId)
                ->where($this->fields['entityKey'], $this->entity->id)
                ->first();

            $relationToExclude->delete();
        }
    }

    private function createRelations($relationsToInclude)
    {
        foreach ($relationsToInclude as $relationId) {
            $data = [
                $this->fields['entityKey'] => $this->entity->id,
                $this->fields['relationKey'] => $relationId
            ];

            $this->manyToManyRelation->create($data);
        }
    }
}
