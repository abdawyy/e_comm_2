<?php

namespace App\Filament\Admin\Resources\Concerns;

trait HandlesTranslations
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        foreach ($this->translationFields() as $field) {
            $data[$field.'_en'] = $record->getTranslation($field, 'en', false);
            $data[$field.'_ar'] = $record->getTranslation($field, 'ar', false);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->packTranslations($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->packTranslations($data);
    }

    /**
     * @return array<int, string>
     */
    protected function translationFields(): array
    {
        $resource = static::getResource();

        return method_exists($resource, 'translatableFields') ? $resource::translatableFields() : [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function packTranslations(array $data): array
    {
        foreach ($this->translationFields() as $field) {
            $data[$field] = [
                'en' => $data[$field.'_en'] ?? '',
                'ar' => $data[$field.'_ar'] ?? '',
            ];
            unset($data[$field.'_en'], $data[$field.'_ar']);
        }

        return $data;
    }
}
