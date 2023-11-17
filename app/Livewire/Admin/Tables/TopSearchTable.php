<?php

namespace App\Livewire\Admin\Tables;

use App\Models\TopSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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

final class TopSearchTable extends PowerGridComponent
{
    use WithExport;

    public function header(): array
    {
        return [
            Button::add('Add New')
                ->slot('Add New')
                ->class('btn btn-light btn-outline-primary')
                ->route('admin.top-searches.create', []),
        ];
    }

    public function setUp(): array
    {
        // $this->showCheckBox();

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
        // add total qualified tools on each search
        return TopSearch::query()->orderBy('id', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('query')

            ->addColumn('created_at_formatted', fn (TopSearch $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Query', 'query')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('query')->operators(['contains']),
            Filter::inputText('slug')->operators(['contains']),
            Filter::inputText('model_type')->operators(['contains']),
            Filter::datetimepicker('created_at'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('edit')
                ->slot('edit')
                ->class('btn btn-sm btn-outline-primary')
                ->route('admin.top-searches.edit', ['top_search' => $row->id]),
            Button::add('show')
                ->slot('show')
                ->class('btn btn-sm btn-outline-primary')
                ->route('search.popular.show', ['top_search' => $row->slug]),
        ];
    }
}
