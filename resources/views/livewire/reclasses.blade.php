<div class="">


    <div class="font-semibold">



        <select>
            @forelse($disciplines as $discipline)
                <option >{{ $discipline->name }}</option>
            @empty

            @endforelse
            <option ></option>
        </select>
    </div>
</div>
