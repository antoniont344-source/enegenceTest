<x-app-layout>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Estados de México</h2>
            <button id="btn-synchronize" class="btn btn-primary btn-sm">
                <span class="spinner-border spinner-border-sm d-none" id="spinner-sync"></span>
                Sincronizar COPOMEX
            </button>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="table-states" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Clave</th>
                            <th>Estado</th>
                            <th>Municipios</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="modal fade" id="modalMunicipalities" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Municipios de <span id="name-state"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group" id="municipalities-list">
                            <li class="list-group-item text-center text-muted">
                                Cargando municipios...
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            $('#table-states').DataTable({
                processing: false,
                serverSide: true,
                ajax: '{{ route('states.data') }}',
                order: [[1, 'asc']],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nom_id', name: 'nom_id' },
                    { data: 'name_state', name: 'name_state' },
                    { data: 'actions', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                }
            });

        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabla = $('#table-states').DataTable();

            $('#btn-synchronize').on('click', function () {
                console.log('Synchronize button clicked');

                const btn = $(this);
                const spinner = $('#spinner-sync');

                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: '{{ route('states.synchronize') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            tabla.ajax.reload(null, false);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function (xhr) {
                        alert(
                            xhr.responseJSON?.message ??
                            'Error synchronizing with COPOMEX'
                        );
                    },
                    complete: function () {
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(
                document.getElementById('modalMunicipalities')
            );

            $('#table-states').on('click', '.link-municipalities', function (e) {
                e.preventDefault();

                const stateData = $(this).attr('data-state');
                console.log('State selected:', stateData);

                $('#name-state').text(stateData);
                $('#municipalities-list').html(`
                    <li class="list-group-item text-center text-muted">
                        Cargando municipios...
                    </li>
                `);

                modal.show();

                $.get(`/states/${encodeURIComponent(stateData)}/municipalities`)
                    .done(function (response) {

                        if (!response.municipios.length) {
                            $('#municipalities-list').html(`
                                <li class="list-group-item text-muted text-center">
                                    No hay municipios disponibles
                                </li>
                            `);
                            return;
                        }

                        let html = '';
                        response.municipios.forEach(municipio => {
                            html += `<li class="list-group-item">${municipio}</li>`;
                        });

                        $('#municipalities-list').html(html);
                    })
                    .fail(function () {
                        $('#municipalities-list').html(`
                            <li class="list-group-item text-danger text-center">
                                Error al cargar municipios
                            </li>
                        `);
                    });
            });
        });
    </script>
@endpush
</x-app-layout>
