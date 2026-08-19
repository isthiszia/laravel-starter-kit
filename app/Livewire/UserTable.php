<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
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
                'role_name',
                fn (User $user) => $user->getRoleNames()->implode(', ')
            )

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

            Column::make('Role', 'role_name')
                ->searchable()
                ->sortable(),

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
        $actions = [];

        if (! $row->hasRole('super-admin')) {
            if (auth()->user()->can('edit-user')) {
            $actions[] = Button::add('edit')
                ->slot('Edit')
                ->class(
                    'px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700'
                )
                ->dispatch('edit-user-modal', [
                    'user' => [
                        'id' => $row->id,
                        'name' => $row->name,
                        'email' => $row->email,
                        'business_id' => $row->business_id,
                        'role' => $row->getRoleNames()->first(),
                    ],
                ]);
            }

            if (auth()->user()->can('delete-user')) {
            $actions[] = Button::add('delete')
                ->slot('Delete')
                ->class(
                    'px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700'
                )
                ->dispatch('delete-user', [
                    'id' => $row->id,
                ]);
            }
        }

        return $actions;
    }

    // #[On('edit-user')]
    // public function editUser($id): void
    // {
    //     $user = User::with('business')->findOrFail($id);

    //     $role = $user->getRoleNames()->first();

    //     $this->dispatch('edit-user-modal', user: [
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'business_id' => $user->business_id,
    //         'role' => $role,
    //     ]);
    // }

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
