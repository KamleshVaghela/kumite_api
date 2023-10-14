<div class="tbodyDiv">
    <table id="resizeMe" class="table table-bordered table-striped text-center">
        <thead class="sticky-top bg-white">
            <tr>
                <th style="width: 450px;">All Categories</th>
                <th style="width: 450px;">Category</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align: top">
                    <div class="overflow-auto" style="height: 800px;">
                    <ul class="list-group">
                        @forelse($default_category_masters as $key=>$rec)
                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center" 
                                onclick="loadContentDetails(this, 'categories', 'karate_ka')"
                                data-href="{{URL::to('admin/default_category/'.$rec->category_group.'/categories')}}"
                                name="li_categories"
                            >
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{$rec->category_group}}</p>
                                    <footer class="blockquote-footer"></footer>
                                </blockquote>
                            </li>
                        @empty
                            <p class="bg-danger text-white p-1">No Item data found</p>
                        @endforelse
                    </ul>
                    </div>
                </td>
                <td style="vertical-align: top">
                    <div class="overflow-auto"  style="height: 800px;" id="div_categories"></div>
                </td>
                <td style="vertical-align: top">
                    <div class="overflow-auto"  style="height: 800px;" id="div_category"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>