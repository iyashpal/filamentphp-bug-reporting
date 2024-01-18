<?php

namespace App\Filament\Supports\Helpers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;

trait HasResourceFields
{
    public static function routeNameIncludes(string $name) {

        return boolval(preg_match("/{$name}/", request()->route()->getName()));
    }
    /**
     * Get the resource form/table fields/columns.
     *
     * @param array $fields
     * @param Form|Table|null $argument
     * @param string $type
     * @return array
     */
    public static function resolveAttributes(array $fields = [], Form|Table $argument = null, string $type = 'field'): array
    {
        $resolved = [];

        foreach ($fields as $key => $value) {
            $isCallable = gettype($key) === 'string' && $value instanceof \Closure;

            $field = self::getFieldName(field: $isCallable ? $key : $value, type: $type);

            $resolved[] = $isCallable ? $value(static::{$field}($argument)) : static::{$field}($argument);
        }

        return $resolved;
    }

    /**
     * Get the form fields.
     *
     * @param array $fields
     * @param Form|null $form
     * @return array
     */
    public static function getFields(array $fields = [], Form $form = null): array
    {
        return self::resolveAttributes($fields, $form, 'field');
    }

    public static function getColumns(array $columns = [], Table $table = null): array {
        return self::resolveAttributes($columns, $table, 'column');
    }

    /**
     * Get the generated field name.
     *
     * @param string $field
     * @param string $type
     * @return string
     */
    public static function getFieldName(string $field, string $type = 'field'): string
    {
        $type = match ($type) {
            'field' => 'form_field',
            'column' => 'table_column'
        };

        return Str::of("get_{name}_{$type}")->replace("{name}", $field)->camel()->value();
    }
}
