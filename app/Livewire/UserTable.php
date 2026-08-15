<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use App\Models\Business;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UserTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'userTable';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('export')
                ->striped()
                ->type(
                    Exportable::TYPE_XLS,
                    Exportable::TYPE_CSV
                ),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()
            ->with('business');
    }

    public function relationSearch(): array
    {
        return [
            'business' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')

            ->add(
                'business_name',
                fn (User $user) => $user->business?->name ?? '-'
            )

            ->add('name')
            ->add('email')

            ->add(
                'created_at_formatted',
                fn (User $user) => $user->created_at
                    ? Carbon::parse($user->created_at)
                        ->format('d M Y h:i A')
                    : '-'
            )

            ->add(
                'last_login_at_formatted',
                fn (User $user) => $user->last_login_at
                    ? Carbon::parse($user->last_login_at)
                        ->format('d M Y h:i A')
                    : '-'
            );
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Business', 'business_name')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable()
                ->editOnClick(hasPermission: true),

            Column::make('Email', 'email')
                ->searchable()
                ->sortable()
                ->editOnClick(hasPermission: true),

            Column::make(
                'Created At',
                'created_at_formatted',
                'created_at'
            )
                ->sortable(),

            Column::make(
                'Last Login',
                'last_login_at_formatted',
                'last_login_at'
            )
                ->sortable(),

            Column::action('Actions'),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(User $row): array
    {
        return [
            Button::add('delete')
                ->slot('Delete')
                ->class(
                    'px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700'
                )
                ->dispatch('delete-user', [
                    'id' => $row->id,
                ]),
        ];
    }

    #[On('delete-user')]
    public function deleteUser($id): void
    {
        User::findOrFail($id)?->delete();
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        $user = User::find($id);

        if (! $user) {
            return;
        }

        if (! in_array($field, ['name', 'email'])) {
            return;
        }

        $user->{$field} = $value;
        $user->save();
    }
}
