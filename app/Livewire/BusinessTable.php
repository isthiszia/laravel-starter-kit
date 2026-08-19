<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class BusinessTable extends PowerGridComponent
{
    public string $tableName = 'businessTable';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Business::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')

            /** Example of custom column using a closure **/
            ->add('name_lower', fn (Business $model) => strtolower(e($model->name)))

            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('logo')
            ->add('is_active')
            ->add('created_at_formatted', fn (Business $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Logo', 'logo')
                ->sortable()
                ->searchable(),

            Column::make('Is active', 'is_active')
                ->toggleable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('phone')->operators(['contains']),
            Filter::inputText('address')->operators(['contains']),
            Filter::inputText('logo')->operators(['contains']),
            Filter::boolean('is_active'),
            Filter::datetimepicker('created_at'),
        ];
    }

    #[On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Business $row): array
    {
        $actions = [];

        if (auth()->user()->can('edit-business')) {
            $actions[] = Button::add('edit')
                ->slot('Edit')
                ->class(
                    'px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700'
                )
                ->dispatch('edit-business-modal', [
                    'business' => [
                        'id' => $row->id,
                        'name' => $row->name,
                        'email' => $row->email,
                        'phone' => $row->phone,
                        'address' => $row->address,
                        'logo' => $row->logo,
                        'is_active' => $row->is_active,
                    ],
                ]);
        }

        return $actions;
    }

    // #[On('edit-business')]
    // public function editBusiness($id): void
    // {
    //     $business = Business::findOrFail($id);

    //     $this->dispatch(
    //         'edit-business-modal',
    //         business: [
    //             'id' => $business->id,
    //             'name' => $business->name,
    //             'email' => $business->email,
    //             'phone' => $business->phone,
    //             'address' => $business->address,
    //             'logo' => $business->logo,
    //             'is_active' => $business->is_active,
    //         ]
    //     );
    // }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
