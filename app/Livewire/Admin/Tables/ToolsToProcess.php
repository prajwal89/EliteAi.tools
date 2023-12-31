<?php

namespace App\Livewire\Admin\Tables;

use App\Models\ExtractedToolDomain;
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

    public function header(): array
    {
        return [
            Button::add('Add New')
                ->slot('Add New')
                ->class('btn btn-light btn-outline-primary')
                ->route('admin.tools-to-process.create', []),
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

    public function datasource()
    {
        return ExtractedToolDomain::whereNotIn('domain_name', function ($query) {
            $query->select('domain_name')->from('tools');
        })
            ->where('should_process', 1)
            ->where('http_status_code', 200)
            ->inRandomOrder();
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('domain_name')

            /** Example of custom column using a closure **/
            ->addColumn('domain_name_lower', fn ($model) => strtolower(e($model->domain_name)))

            ->addColumn('home_page_url', function ($model) {
                return '<a href="' . $model->home_page_url . '" target="_blank">' . $model->home_page_url . '</a>';
            })
            ->addColumn('process_status')
            ->addColumn('process_error');
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

    public function actions($row): array
    {
        return [

            Button::add('Import')
                ->slot('Import')
                ->class('btn btn-sm btn-outline-success')
                ->route('admin.tools.import', ['tool_to_process_id' => $row->id]),

            Button::add('Edit')
                ->slot('Edit')
                ->class('btn btn-sm btn-outline-primary')
                ->route('admin.tools-to-process.edit', ['tools_to_process' => $row->id]),

            Button::add('Do not process')
                ->slot('Do not process')
                ->class('btn btn-sm btn-outline-danger')
                ->method('post')
                ->route('admin.tools-to-process.do-not-process', ['tool_to_process_id' => $row->id]),

        ];
    }
}
