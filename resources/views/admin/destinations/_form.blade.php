@csrf
@if($destination ?? null) @method('PUT') @endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Destination Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $destination->name ?? '') }}" required
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
        <input type="text" name="location" value="{{ old('location', $destination->location ?? '') }}" required
               placeholder="e.g. East Java, ID"
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
            <input type="text" name="latitude" value="{{ old('latitude', $destination->latitude ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
            <input type="text" name="longitude" value="{{ old('longitude', $destination->longitude ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>
    </div>

    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-xs text-gray-400">(English)</span></label>
            <textarea name="description_en" rows="4" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('description_en', $destination->description_en ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-xs text-gray-400">(Indonesia)</span></label>
            <textarea name="description_id" rows="4" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('description_id', $destination->description_id ?? '') }}</textarea>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">What we do there? <span class="text-xs text-gray-400">(English)</span></label>
        <textarea name="what_to_do_en" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('what_to_do_en', $destination->what_to_do_en ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ngapain aja di sini? <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <textarea name="what_to_do_id" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('what_to_do_id', $destination->what_to_do_id ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Unique / Point of Interest <span class="text-xs text-gray-400">(English)</span></label>
        <textarea name="point_of_interest_en" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('point_of_interest_en', $destination->point_of_interest_en ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Keunikan / Hal Menarik <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <textarea name="point_of_interest_id" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('point_of_interest_id', $destination->point_of_interest_id ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Travel Tips <span class="text-xs text-gray-400">(English, one tip per line)</span></label>
        <textarea name="tips_en" rows="5" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('tips_en', $destination->tips_en ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tips Perjalanan <span class="text-xs text-gray-400">(Indonesia, satu tips per baris)</span></label>
        <textarea name="tips_id" rows="5" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('tips_id', $destination->tips_id ?? '') }}</textarea>
    </div>

    {{-- Practical guide: shown on the destination page and in "Before You Go" on packages --}}
    <div class="sm:col-span-2 border-t border-gray-100 pt-4">
        <p class="text-sm font-semibold text-gray-800 mb-1">Travel guide</p>
        <p class="text-xs text-gray-400 mb-3">One point per line. Leave a box empty to hide that part.</p>

        @php $selectedMonths = old('best_months', $destination?->bestMonthNumbers() ?? []); @endphp
        <label class="block text-sm font-medium text-gray-700 mb-2">Best months to visit</label>
        <div class="flex flex-wrap gap-2 mb-4">
            @for($m = 1; $m <= 12; $m++)
                <label class="cursor-pointer">
                    <input type="checkbox" name="best_months[]" value="{{ $m }}" class="peer sr-only" @checked(in_array($m, array_map('intval', $selectedMonths)))>
                    <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200 text-gray-500 peer-checked:bg-brand-500 peer-checked:text-white peer-checked:border-brand-500 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-300">
                        {{ \Illuminate\Support\Carbon::create(2026, $m, 1)->format('M') }}
                    </span>
                </label>
            @endfor
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            @foreach([
                'best_time' => 'Best time notes',
                'packing' => 'What to bring',
                'provided' => 'What we provide (only if you really supply it)',
                'safety' => 'Safety notes',
            ] as $field => $label)
                @foreach(['en' => 'English', 'id' => 'Indonesia'] as $lang => $langLabel)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }} <span class="text-xs text-gray-400">({{ $langLabel }})</span></label>
                    <textarea name="{{ $field }}_{{ $lang }}" rows="4" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old($field . '_' . $lang, $destination->{$field . '_' . $lang} ?? '') }}</textarea>
                </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="sm:col-span-2 grid grid-cols-3 gap-3">
        @foreach(['nature_level' => 'Nature Level', 'culture_level' => 'Culture Level', 'heritage_level' => 'Heritage Level'] as $field => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }} (1-5)</label>
            <input type="number" min="1" max="5" name="{{ $field }}" value="{{ old($field, $destination->$field ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>
        @endforeach
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Photo</label>
        <input type="file" name="cover_photo" accept="image/*" class="w-full text-sm">
        @if(($destination->cover_photo ?? null))
            <img src="{{ str_starts_with($destination->cover_photo, 'http') ? $destination->cover_photo : asset('storage/' . $destination->cover_photo) }}" class="mt-2 h-24 rounded-lg object-cover">
        @endif
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Category Tags</label>
        <div class="flex flex-wrap gap-3">
            @foreach($categories as $category)
                @php $checked = in_array($category->id, old('categories', $destination?->categories?->pluck('id')->toArray() ?? [])); @endphp
                <label class="flex items-center gap-1.5 text-sm border border-gray-200 rounded-lg px-3 py-1.5 cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked($checked)>
                    {{ $category->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="sm:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $destination->is_active ?? true))>
        <label for="is_active" class="text-sm text-gray-700">Show on site (active)</label>
    </div>
</div>

<div class="border-t border-gray-100 mt-6 pt-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-800">Activity ("What we do there")</h3>
        <button type="button" onclick="addActivityRow()" class="text-xs px-3 py-1 rounded-lg border border-brand-200 text-brand-500 hover:bg-brand-50">+ Add Activity</button>
    </div>
    <div id="activity-rows" class="space-y-3">
        @foreach(($destination->activities ?? []) as $activity)
        <div class="activity-row border border-gray-200 rounded-xl p-3 space-y-2">
            <input type="hidden" name="activity_photo_existing[]" value="{{ $activity->photo }}">
            <div class="grid grid-cols-1 sm:grid-cols-[2fr_2fr_1fr_auto] gap-2 items-start">
                <input type="text" name="activity_title[]" value="{{ $activity->getRawOriginal('title') }}" placeholder="Activity title (English)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <input type="text" name="activity_title_id[]" value="{{ $activity->title_id }}" placeholder="Judul aktivitas (Indonesia)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <select name="activity_type[]" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                    @foreach(['tracking', 'tradition', 'adventure'] as $type)
                        <option value="{{ $type }}" @selected($activity->type === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="this.closest('.activity-row').remove()" class="text-red-500 text-xs px-2 py-2">Delete</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input type="text" name="activity_description_en[]" value="{{ $activity->description_en }}" placeholder="Description (English)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <input type="text" name="activity_description_id[]" value="{{ $activity->description_id }}" placeholder="Deskripsi (Indonesia)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            </div>
            <div class="flex items-center gap-3">
                @if($activity->photo_url)
                    <img src="{{ $activity->photo_url }}" class="h-12 w-20 rounded-lg object-cover" alt="">
                @endif
                <input type="file" name="activity_photo[]" accept="image/*" class="text-xs text-gray-500">
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
function addActivityRow() {
    const wrap = document.getElementById('activity-rows');
    const row = document.createElement('div');
    row.className = 'activity-row border border-gray-200 rounded-xl p-3 space-y-2';
    row.innerHTML = `
        <input type="hidden" name="activity_photo_existing[]" value="">
        <div class="grid grid-cols-1 sm:grid-cols-[2fr_2fr_1fr_auto] gap-2 items-start">
            <input type="text" name="activity_title[]" placeholder="Activity title (English)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <input type="text" name="activity_title_id[]" placeholder="Judul aktivitas (Indonesia)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <select name="activity_type[]" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <option value="tracking">Tracking</option>
                <option value="tradition">Tradition</option>
                <option value="adventure">Adventure</option>
            </select>
            <button type="button" onclick="this.closest('.activity-row').remove()" class="text-red-500 text-xs px-2 py-2">Delete</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <input type="text" name="activity_description_en[]" placeholder="Description (English)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <input type="text" name="activity_description_id[]" placeholder="Deskripsi (Indonesia)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
        </div>
        <div class="flex items-center gap-3">
            <input type="file" name="activity_photo[]" accept="image/*" class="text-xs text-gray-500">
        </div>
    `;
    wrap.appendChild(row);
}
</script>

<div class="flex gap-3 pt-6">
    <button type="submit" class="px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">Save</button>
    <a href="{{ route('admin.destinations.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">Cancel</a>
</div>
