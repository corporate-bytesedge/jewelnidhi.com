<div class="row">

    <div class="col-xs-12 col-sm-12">

        <table class="table table-bordered">
            <tr>
                <th>Product</th>
                <td>{{ $enquire->product->name ? ucwords($enquire->product->name) : '-' }}</td>

            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $enquire->name ? ucwords($enquire->name) : '-' }}</td>

            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $enquire->email ? $enquire->email : '-' }}</td>

            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $enquire->phone ? $enquire->phone : '-' }}</td>

            </tr>
            <tr>
                <th>Query</th>
                <td>{{ $enquire->query ? $enquire->query : '-' }}</td>

            </tr>

        </table>
        
        

    </div>
</div>