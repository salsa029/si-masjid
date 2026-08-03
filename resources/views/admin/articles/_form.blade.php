@csrf

<div class="space-y-8">
    {{-- Judul --}}
    <div>
        <label for="title" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-heading text-xs text-emerald-600"></i> Judul Artikel
        </label>
        <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}"
            class="@error('title') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            placeholder="Masukkan judul artikel">
        @error('title')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    {{-- Isi Artikel (Quill) --}}
    <div>
        <label for="editor" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-align-left text-xs text-emerald-600"></i> Isi Artikel
        </label>
        <div
            class="overflow-hidden rounded-lg border border-gray-300 transition focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100">
            <div id="editor" style="height: 350px;" class="bg-white">{!! old('content', $article->content ?? '') !!}</div>
        </div>
        <textarea name="content" id="content-input" class="hidden">{{ old('content', $article->content ?? '') }}</textarea>
        @error('content')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    {{-- Meta Keyword (SEO) --}}
    <div>
        <label for="meta_keyword" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-key text-xs text-emerald-600"></i> Meta Keyword (SEO)
        </label>
        <input type="text" name="meta_keyword" id="meta_keyword"
            value="{{ old('meta_keyword', $article->meta_keyword ?? '') }}"
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            placeholder="masjid, dakwah, ramadhan">
    </div>

    {{-- Kategori & Tag --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="category_id" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-tags text-xs text-emerald-600"></i> Kategori
            </label>
            <select name="category_id" id="category_id"
                class="@error('category_id') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tags" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-hashtag text-xs text-emerald-600"></i> Tag
            </label>
            <input type="text" name="tags" id="tags"
                value="{{ old('tags', isset($article) ? $article->tags->pluck('name')->implode(', ') : '') }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                placeholder="ramadhan, kajian, zakat">
            <p class="mt-1 text-xs text-gray-400">Pisahkan setiap tag dengan koma.</p>
        </div>
    </div>

    {{-- Status --}}
    <div>
        <label for="status" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-circle-check text-xs text-emerald-600"></i> Status
        </label>
        <select name="status" id="status"
            class="@error('status') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @php $currentStatus = old('status', $article->status ?? 'draft'); @endphp
            <option value="draft" @selected($currentStatus === 'draft')>Draf</option>
            <option value="published" @selected($currentStatus === 'published')>Terbit</option>
            <option value="archived" @selected($currentStatus === 'archived')>Diarsipkan</option>
        </select>
        @error('status')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    {{-- Thumbnail --}}
    <div>
        <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-image text-xs text-emerald-600"></i> Thumbnail
        </label>
        @if (!empty($article) && $article->thumbnail)
            <div class="mb-3">
                <img src="{{ Storage::url($article->thumbnail) }}"
                    class="h-24 w-40 rounded-lg border border-gray-200 object-cover shadow-sm">
            </div>
        @endif
        <div
            class="rounded-xl border border-dashed border-gray-300 bg-gray-50/60 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/40">
            <input type="file" name="thumbnail" accept="image/*"
                class="w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white file:transition hover:file:bg-emerald-700">
        </div>
        <p class="mt-1.5 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
        @error('thumbnail')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    {{-- Tombol --}}
    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('admin.articles.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</div>

{{-- Quill Scripts --}}
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Tulis isi artikel di sini...',
            modules: {
                toolbar: [
                    [{
                        header: [2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['blockquote', 'link', 'image'],
                    ['clean'],
                ],
            },
        });

        const contentInput = document.getElementById('content-input');

        // Salin isi HTML dari Quill ke textarea setiap kali diketik (agar selalu tersinkron)
        quill.on('text-change', function() {
            contentInput.value = quill.root.innerHTML;
        });

        // Jaga-jaga: salin ulang tepat sebelum form disubmit.
        // Dicari lewat closest('form') dari editornya sendiri, BUKAN document.querySelector('form'),
        // karena form pertama di halaman admin adalah form "Keluar" di header, bukan form artikel ini.
        contentInput.closest('form').addEventListener('submit', function() {
            contentInput.value = quill.root.innerHTML;
        });
    });
</script>
