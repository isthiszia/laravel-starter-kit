<?php

namespace App\Livewire;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class SubscriptionTable extends PowerGridComponent
{
    public string $tableName = 'subscriptionTable';

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
        return Subscription::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')

            ->add(
                'business_name',
                fn (Subscription $subscription) => $subscription->business?->name ?? '-'
            )

            ->add('month')

            ->add(
                'subscription_status',
                function (Subscription $subscription) {

                    $today = Carbon::today();

                    $startDate = Carbon::parse($subscription->start_date);
                    $dueDate = Carbon::parse($subscription->due_date);

                    if ($dueDate->lt($today)) {
                        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                            Expired
                        </span>';
                    }

                    if ($startDate->gt($today)) {
                        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                            Upcoming
                        </span>';
                    }

                    if ($subscription->status === 'paid') {
                        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                            Paid
                        </span>';
                    }

                    return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                        Active
                    </span>';
                }
            )

            ->add(
                'start_date_formatted',
                fn (Subscription $subscription) => Carbon::parse($subscription->start_date)->format('d/m/Y')
            )

            ->add(
                'due_date_formatted',
                fn (Subscription $subscription) => Carbon::parse($subscription->due_date)->format('d/m/Y')
            );
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Business', 'business_name')
                ->searchable()
                ->sortable(),
            Column::make('Month', 'month')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'subscription_status')
                ->sortable()
                ->searchable(),

            Column::make('Start date', 'start_date_formatted', 'start_date')
                ->sortable(),

            Column::make('Due date', 'due_date_formatted', 'due_date')
                ->sortable(),

            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('month')->operators(['contains']),
            Filter::inputText('status')->operators(['contains']),
            Filter::datepicker('start_date'),
            Filter::datepicker('due_date'),
        ];
    }

    #[On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Subscription $row): array
    {
        $actions = [];

        if (auth()->user()->can('delete-subscription')) {
        $actions[] = Button::add('delete')
            ->slot('Delete')
            ->class(
                'px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700'
            )
            ->dispatch('delete-subscription', [
                'id' => $row->id,
            ]);
        }

        return $actions;
    }

    #[On('delete-subscription')]
    public function deleteSubscription($id): void
    {
        Subscription::where('id', $id)->delete();
    }

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
