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

final class ToolsToProcess extends PowerGridComponent
{
    use WithExport;

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
        return DB::table('extracted_tool_domains')
            ->whereNotIn('domain_name', function ($query) {
                $query->select('domain_name')->from('tools');
            });
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('domain_name')

            /** Example of custom column using a closure **/
            ->addColumn('domain_name_lower', fn ($model) => strtolower(e($model->domain_name)))

            ->addColumn('home_page_url')
            ->addColumn('process_status')
            ->addColumn('process_error')
            ->addColumn('created_at_formatted', fn ($model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('updated_at_formatted', fn ($model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Domain name', 'domain_name')
                ->sortable()
                ->searchable(),

            Column::make('Home page url', 'home_page_url')
                ->sortable()
                ->searchable(),

            // Column::make('Process status', 'process_status'),
            // Column::make('Process error', 'process_error')
            //     ->sortable()
            //     ->searchable(),

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
            Filter::inputText('domain_name')->operators(['contains']),
            Filter::inputText('home_page_url')->operators(['contains']),
            Filter::inputText('process_error')->operators(['contains']),
            Filter::datetimepicker('created_at'),
            Filter::datetimepicker('updated_at'),
        ];
    }

    // public function actions($row): array
    // {
    //     return [
    //         Button::add('edit')
    //             ->slot('edit')
    //             ->class('btn btn-sm btn-outline-primary')
    //             ->route('admin.categories.edit', ['category' => $row->id]),
    //     ];
    // }
}
