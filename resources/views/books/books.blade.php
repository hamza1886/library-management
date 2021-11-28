@extends('template')

@section('page_title')

@endsection

@section('page_body')
    <div id="message-container" class="alert alert-primary alert-dismissible fade show" role="alert" style="display: none;">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    </div>

    <h3>List of Books</h3>
    <table id="books-table" class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>#</td>
            <td>Title</td>
            <td>Author</td>
            <td>ISBN</td>
            <td>Date of Publication</td>
            <td>Status</td>
            <td>Actions</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="7">No data to show</td>
        </tr>
        </tbody>
    </table>
@endsection

@section('page_script')
    <script type="application/javascript">
        $(document).ready(function () {
            $.ajax({
                url: '/books',
                success: function (response) {
                    let html = '';

                    if (response.data.book.data.length) {
                        response.data.book.data.forEach(function (book) {
                            html +=
                                '<tr>' +
                                '  <td>' + book.id + '</td>' +
                                '  <td>' + book.title + '</td>' +
                                '  <td>' + book.author + '</td>' +
                                '  <td>' + book.isbn + '</td>' +
                                '  <td>' + book.published_at + '</td>' +
                                '  <td class="book-status-cell"><span class="badge ' + (book.status === 'AVAILABLE' ? 'badge-success' : 'badge-secondary') + '">' + book.status.replace('_', '-') + '</span></td>' +
                                '  <td class="book-action-cell"><a data-id="' + book.id + '" class="btn btn-block btn-sm ' + (book.status === 'AVAILABLE' ? 'badge-success' : 'btn-primary') + ' book-action" href="/api/books/' + book.id + (book.status === 'AVAILABLE' ? '/checkout' : '/checkin') + '">' + (book.status === 'AVAILABLE' ? 'CHECK-OUT' : 'CHECK-IN') + '</a></td>' +
                                '</tr>';
                        });
                    } else {
                        html += '<tr>' +
                            '  <td colspan="5">No data to show</td>' +
                            '</tr>';
                    }

                    $('#books-table tbody').html(html);
                },
            });

            $('#books-table').on('click', '.book-action', function (e) {
                e.preventDefault();

                const self = $(this);
                const bookId = self.data('id');
                const bookStatusEl = self.closest('tr').find('.book-status-cell');
                const bookActionEl = self.closest('tr').find('.book-action-cell');

                $.ajax({
                    method: 'POST',
                    url: e.currentTarget.href,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        if (e.currentTarget.href.endsWith('checkout')) {
                            bookStatusEl.html('<span class="badge badge-secondary">CHECKED-OUT</span>');
                            bookActionEl.html('<a class="btn btn-block btn-sm btn-primary book-action" href="/api/books/' + bookId + '/checkin">CHECK-IN</a>');
                        } else if (e.currentTarget.href.endsWith('checkin')) {
                            bookStatusEl.html('<span class="badge badge-success">AVAILABLE</span>');
                            bookActionEl.html('<a class="btn btn-block btn-sm btn-success book-action" href="/api/books/' + bookId + '/checkout">CHECK-OUT</a>');
                        }

                        $('#message-container').text(response.message).slideDown();
                    },
                });
            });
        });
    </script>
@endsection
