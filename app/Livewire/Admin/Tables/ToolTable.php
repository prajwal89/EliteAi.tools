<?php

namespace App\Livewire\Admin\Tables;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ToolTable extends PowerGridComponent
{
    use WithExport;

    public function header(): array
    {
        return [
            Button::add('Add New')
                ->slot('Add New')
                ->class('btn btn-light btn-outline-primary')
                ->route('admin.tools.create', []),
            Button::add('Import json')
                ->slot('Import json')
                ->class('btn btn-light btn-success-primary')
                ->route('admin.tools.import', []),
        ];
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return DB::table('tools')
            ->orderBy('id', 'desc');
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('name')

            /** Example of custom column using a closure **/
            ->addColumn('name_lower', fn ($model) => strtolower(e($model->name)))

            ->addColumn('tag_line', function ($model) {
                return str($model->tag_line)->words(10, '...')->toString();
            })
            ->addColumn('summary')
            ->addColumn('domain_name')
            ->addColumn('home_page_url')
            ->addColumn('has_api')
            ->addColumn('top_features')
            ->addColumn('use_cases')
            ->addColumn('uploaded_screenshot')
            ->addColumn('uploaded_favicon')
            ->addColumn('owner_id')
            ->addColumn('created_at_formatted', fn ($model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('updated_at_formatted', fn ($model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Tag line', 'tag_line')
                ->sortable()
                ->searchable(),

            // Column::make('Summary', 'summary')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Domain name', 'domain_name')
            //     ->sortable()
            //     ->searchable(),

            Column::make('Home page url', 'home_page_url')
                ->sortable()
                ->searchable(),

            Column::make('Has api', 'has_api')
                ->toggleable(),

            // Column::make('Top features', 'top_features')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Use cases', 'use_cases')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Uploaded screenshot', 'uploaded_screenshot')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Uploaded favicon', 'uploaded_favicon')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Owner id', 'owner_id'),
            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            // Column::make('Updated at', 'updated_at_formatted', 'updated_at')
            //     ->sortable(),
            Column::action('Action'),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('tag_line')->operators(['contains']),
            Filter::inputText('domain_name')->operators(['contains']),
            Filter::inputText('home_page_url')->operators(['contains']),
            Filter::boolean('has_api'),
            Filter::inputText('uploaded_screenshot')->operators(['contains']),
            Filter::inputText('uploaded_favicon')->operators(['contains']),
            Filter::datetimepicker('created_at'),
            Filter::datetimepicker('updated_at'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('edit')
                ->slot('edit')
                ->class('btn btn-sm btn-outline-primary')
                ->route('admin.tools.edit', ['tool' => $row->id]),
        ];
    }
}
