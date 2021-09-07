# MoLDSSP - MongoDB Laravel Datatables SSP

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/flatorb/moldssp.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/flatorb/moldssp.svg?style=flat-square)](https://packagist.org/packages/flatorb/moldssp)

This package library makes it easier to use Laravel server side pagination functionality with MongoDB and Datatables (datatables.net).

## Install
`composer require flatorb/moldssp`

## Usage
### DataTables.net plugin
Add the datatables.net javascript library to your application either using CDN.

### View
On your blade (view) file establish the table with the following base structure.
    
    <table class="table" id="postsTable">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Title</th>
                <th class="text-center">Author</th>
                <th class="text-center">Date</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

And within your script tags of the page:

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#postsTable').DataTable({
                serverSide: true,
                ajax: {
                    url: '/api/posts',
                    data: function(d) {
                        d.page = (d.start/d.length) + 1;
                    },
                    dataFilter: function(data) {
                        var json = jQuery.parseJSON(data);
                        json.recordsTotal = json.total;
                        json.recordsFiltered = json.total;

                        return JSON.stringify(json);
                    }
                },
                language: {
                    lengthMenu: "Show _MENU_",
                },
                dom: "<'row'" + "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" + "<'col-sm-6 d-flex align-items-center justify-content-end'f>" + ">" + "<'table-responsive'tr>" + "<'row'" + "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" + "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" + ">",
                processing: true,
                bStateSave: true,
                fixedHeader: {
                    headerOffset: 119
                },
                lengthMenu: [
                    [10, 25, 50, 100, 150, -1],
                    [10, 25, 50, 100, 150, "All"]
                ],
                pageLength: 25,
                pagingType: "full_numbers",
                order: [[3, "desc"]],
                deferRender: true,
                columns: [
                    { "data": "customID" },
                    { "data": "title" },
                    { "data": {"_":"authorID", "display":"author.name" }},
                    { "data": "date" },
                    { "data": {"_":"statusID", "display":"status.name" }}
                ]
            });
        });
	</script>

### Controller

```
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;

use Flatorb\Moldssp\Moldssp;

class PostsController extends Controller
{
    public function __construct() {
        // your own code here
    }

    public function index(Request $request)
    {
        return Moldssp::paginator(Post::query(), $request);
    }
}
```

## Issues
If you discover any functionality or compatibility issues (other than security issues), please use the [Github bug tracker](https://github.com/Flatorb/moldssp/issues) to report them.

## Security
If you discover any security-related issues, please email packages@flatorb.com instead of using the issue tracker.

## Testing
Need to implement. Contributions to this section will be highly values.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing
We encourage contribution to this package

## Credits

- [Anushan W](https://github.com/anushanw)
- [Flatorb](https://github.com/flatorb)
- [All Contributors](https://github.com/flatorb/moldssp/contributors)

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
