import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import Swal from 'sweetalert2';

    document.addEventListener('livewire:init', () => {

        Livewire.on('show-delete-confirmation', (data) => {

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${data.name}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {

                if (result.isConfirmed) {

                    Livewire.dispatch('delete-user', {
                        id: data.id
                    });

                }

            });

        });

        Livewire.on('notify', (data) => {

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: data.type,
                title: data.message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
            });

        });

    });
