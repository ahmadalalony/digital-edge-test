$(function () {
    let token = localStorage.getItem('auth_token');

    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/users',
            type: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + token);
            },
            dataSrc: function (json) {
                return json.data;
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
                data: 'is_verified',
                render: function (data) {
                    return data ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a href="/admin/users/${data.id}/edit" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                        <button class="btn btn-sm btn-danger delete-user" data-id="${data.id}">{{ __('Delete') }}</button>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.delete-user', function () {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            fetch(`/api/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        alert('User deleted successfully');
                        $('#users-table').DataTable().ajax.reload();
                    } else {
                        alert(data.message || 'Error occurred');
                    }
                });
        }
    });
});
