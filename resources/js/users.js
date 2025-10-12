window.usersDataTable = null;

$(function () {
    // Wait for translations to be loaded
    if (typeof window.translations === 'undefined') {
        setTimeout(arguments.callee, 100);
        return;
    }
    let token = window.authToken || localStorage.getItem('auth_token');

    window.usersDataTable = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/users/data',
            type: 'GET',
            data: function (d) {
                // Add filter parameters to DataTables request
                d.country = document.getElementById('filter-country')?.value || '';
                d.city = document.getElementById('filter-city')?.value || '';
                return d;
            },
            beforeSend: function (xhr) {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrf) xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
            },
            dataSrc: function (json) {
                // Handle both DataTables JSON and ApiResponse format
                if (Array.isArray(json?.data)) return json.data;
                if (Array.isArray(json?.data?.data)) return json.data.data;
                return [];
            }
        },
        columns: [
            { data: 'id' },
            { data: 'first_name' },
            { data: 'last_name' },
            { data: 'email', defaultContent: '-' },
            { data: 'phone', defaultContent: '-' },
            { data: 'country', defaultContent: '-' },
            {
                data: 'email_verified_at',
                render: function (data) {
                    return data ? `<span class="badge bg-success">${window.translations.yes}</span>` : `<span class="badge bg-danger">${window.translations.no}</span>`;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a href="/admin/users/${data.id}/edit" class="btn btn-sm btn-primary">${window.translations.edit}</a>
                        <a href="/admin/users/${data.id}/email" class="btn btn-sm btn-secondary">${window.translations.sendEmail || 'Send Email'}</a>
                        <button class="btn btn-sm btn-danger delete-user" data-id="${data.id}">${window.translations.delete}</button>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.delete-user', function () {
        let id = $(this).data('id');
        if (confirm(window.translations.confirmDelete)) {
            fetch(`/admin/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        alert(window.translations.deleteSuccess);
                        $('#users-table').DataTable().ajax.reload();
                    } else {
                        alert(data.message || window.translations.error);
                    }
                });
        }
    });

    $('#export-users').on('click', function () {
        const dt = window.usersDataTable;
        const params = dt && dt.ajax && dt.ajax.params ? dt.ajax.params() : {};
        const query = new URLSearchParams(params).toString();
        const url = `/admin/users/export?${query}`;

        // Simple redirect for web routes
        window.open(url, '_blank');
    });

    // Add event listeners for filter buttons
    $(document).on('click', '#apply-filters', function () {
        window.usersDataTable.ajax.reload();
    });

    $(document).on('click', '#reset-filters', function () {
        document.getElementById('filter-country').value = '';
        document.getElementById('filter-city').value = '';
        window.usersDataTable.ajax.reload();
    });
});
