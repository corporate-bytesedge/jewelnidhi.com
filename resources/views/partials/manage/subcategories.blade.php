<ul>
    @foreach($childs as $child)
        @php 
        
        
        
        $product =  \App\Product::whereHas('product_category_styles', function ($query) use($child) {
                     $query->where('category_id',$child->category_id)->where('product_style_id', $child->id);
                    })->where(function ($query) {
                                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('category_id',$child->category_id)->where('is_active','1')->count();

                    
        @endphp
        <li>
        {{ $child->name . ' ('.$product.')' }}
        </li>
    @endforeach
</ul>